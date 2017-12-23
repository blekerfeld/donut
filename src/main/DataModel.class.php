<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: datamodel.class.php

class pDataModel {

	public $_fields = null, $_table, $_fieldstring, $_valuestring, $_updateid, $_updatestring, $_singleId, $_paginated, $_data, $_condition, $_order = '1', $_limit = null;

	public function __construct($table, $fields = null, $paginated = false, $shortcutToSingleID = null){
		$this->_fields = $fields;
		$this->_table = $table;
		$this->_paginated = $paginated;
		$this->generateFieldString();
		if($shortcutToSingleID != null)
			$this->getSingleObject($shortcutToSingleID);
	}

	public function generateFieldString(){
		if($this->_fields != null){
			$fieldString = array('id');
			foreach($this->_fields->get() as $field)
				$fieldString[] = $field->name;
			$this->_fieldstring = implode(",", $fieldString);
		}
		else{
			$this->_fieldstring = "";
		}
		return $this;
	}

	public function setFields($fields){
		$this->_fields = $fields;
		$this->generateFieldString();
		return $this;
	}

	// Might have been through 3 functions already, but yeah, it is how it is.
	public function setCondition($condition){
		$this->_condition = $condition;
		return $this;
	}

	public function setWhereID($condition){
		$this->_condition = " WHERE id = $condition";
		return $this;
	}

	public function setLimit($limit){
		$this->_limit = $limit;
		return $this;
	}

	public function setOrder($order){
		$this->_order = $order;
		return $this;
	}


	public static function getRecord($table, $id){
		return p::$db->cacheQuery("SELECT * FROM ".$table." WHERE id = ".$id." ORDER BY 1 LIMIT 1")->fetchAll()[0];
	}

	public function getSingleObject($id){

		if(!is_numeric($id))
			$id = p::HashId($id, true)[0];

		// Condition can't start with WHERE
		$condition = p::Str($this->_condition)->replacePrefix("WHERE", ' AND ');

		$this->_singleId = $id;

		// Maybe we just need all fields?
		if($this->_fieldstring == "")
			$fieldString = "*";
		else
			$fieldString = $this->_fieldstring;

		$this->_data =  p::$db->cacheQuery("SELECT ".$fieldString." FROM ".$this->_table." WHERE id = ".$id." ".$condition." ORDER BY $this->_order LIMIT 1");

		return  $this->_data;
	}

	public function data(){
		return $this->_data;
	}

	public function getObjects($offset = null, $itemsperpage = null){

		// Maybe we just need all fields?
		if($this->_fieldstring == "")
			$fieldString = "*";
		else
			$fieldString = $this->_fieldstring;

		$this->_data = p::$db->cacheQuery("SELECT ".$fieldString." FROM ".$this->_table." ".$this->_condition.(($this->_paginated) ? " ORDER BY $this->_order LIMIT ".$offset.",".$itemsperpage : " ORDER BY $this->_order ".($this->_limit != null ? ' LIMIT '.$this->_limit : '')).";");

		return  $this->_data;
	}

	public function getAndFetch(){
		$get = $this->getObjects();
		if($get->rowCount() == 0)
			return array();
		else
			return $get->fetchAll()[0];
	}

	public function getAndFetchAll(){
		$get = $this->getObjects();
		if($get->rowCount() == 0)
			return array();
		else
			return $get->fetchAll();
	}

	public function paginateQuery($query, $offset, $itemsperpage){
		if(p::EndsWith($query, ';'))
			$query = substr($query, -1);
		return $query . " LIMIT ".$offset.", ".$itemsperpage ." ;";
	}

	// Prepare funcition to prepare the dataModel for new data
	public function prepareForInsert($data){
		if(!is_array($data))
			$data = func_get_args();
		if($this->_fields != null)
			if(count($data) != count($this->_fields->get()))
				die("FATAL ERROR from within pDataModel->prepareForInsert(\$data). \$data does not match the field count of the object!");
		$valueString = array('NULL');
		$key = 0;
		if($this->_fields != null)
			foreach($this->_fields->get() as $field){
				$valueString[] = ($data[$key] != 'NOW()' ? p::Quote($data[$key]) : 'NOW()');;
				$key++;
			}
		else
			foreach($data as $value)
				$valueString[] = ($value != 'NOW()' ? p::Quote($value) : 'NOW()');
		$this->_valuestring = implode(', ', $valueString);

		return $this;
	}

	public function changeField($id, $field, $value, $original_value = null){

		if($original_value != null AND $value == $original_value)
			return false;

		$fields = new pSet;
		$fields->add($field);
		$this->setFields($fields);
		$this->prepareForUpdate(array($value), $id);
		return $this->update();
	}

	// Prepare funcition to prepare the dataModel for new data
	public function prepareForUpdate($data, $id = -1, $overwriteFields = null){

		global $donut;


		if($id == -1)
			$id = $this->_singleId;
		if($this->_fields != null)
			if(count($data) != count($this->_fields->get()))
				die("FATAL ERROR from within pDataModel->prepareForUpDate(..., \$data). \$data does not match the field count of the object!");
		
		$updateString = array();

		$key = 0;

		$forEachFields = ($overwriteFields != null ? (is_a($overwriteFields, @pSet) ? $overwriteFields->get() : $overwriteFields) : $this->_fields);

		foreach ($forEachFields as $field) {
			if($field->name != 'id')
				$updateString[] = $field->name."= ".p::Quote($data[$key]);
			$key++; 
		}

		$this->_updatestring = implode(', ', $updateString);
		$this->_updateid = $id;
	}

	public function remove($follow_up = 0, $follow_up_field = 0, $selective = -1, $field = 'id'){


		// Follow up can be an array of other tables, that have potentionaly have references to the deleted record from the main table. Those are to be deleted as well.

		if($selective == -1)
			$selective = $this->_singleId;

		if($selective == 0)
			return p::$db->cacheQuery("DELETE FROM ".$this->_table,";");
		
		// This will go through follow_up, to delete any records that need to be gone first. 
		if(is_array($follow_up)){
			foreach($follow_up as $key => $table){
				if(is_array($follow_up_field)){
					if(count($follow_up_field) != count ($follow_up))
						die("FATAL ERROR from within pDataModel->remove(...\$follow_up_field...). \$follow_up_field does not match the field count of the follow_up!");
					$field = $follow_up_field[$key];
				}
				else
					$field = follow_up_field;

				$tempSet = new pSet;
				$tempSet->add(new pDataField($field)); 
				$tempObject = new pDataModel($table, $tempSet);
				$tempObject->remove($selective, $field);
			}

		}

		return p::$db->cacheQuery("DELETE FROM ".$this->_table." WHERE id = ".$this->_field = " ".p::Quote($selective).";");

	}

	public function update(){

		return p::$db->cacheQuery("UPDATE ".$this->_table." SET ".$this->_updatestring." WHERE id = '".$this->_updateid."';");


	}

	public function count(){
		return $this->_data->rowCount();
	}

	public function countAll(){

		$count = p::$db->cacheQuery("SELECT count(id) AS total FROM ".$this->_table." ".$this->_condition.";")->fetchObject();

		return $count->total;
	}

	public function insert($data = NULL){

		if($data != NULL AND is_array($data))
			$this->prepareForInsert($data);
		elseif($data != NULL)
			$this->prepareForInsert(func_get_args());

		// Maybe we just need all fields?
		if($this->_fieldstring == "")
			$fieldString = "";
		else
			$fieldString = "(".$this->_fieldstring.")";

		p::$db->cacheQuery("INSERT INTO ".$this->_table." $fieldString  VALUES (".$this->_valuestring.");");

		

		return p::$db->lastInsertId();
	}

	public function insertIgnore($data = NULL){

		if($data != NULL AND is_array($data))
			$this->prepareForInsert($data);
		elseif($data != NULL)
			$this->prepareForInsert(func_get_args());

		// Maybe we just need all fields?
		if($this->_fieldstring == "")
			$fieldString = "";
		else
			$fieldString = "(".$this->_fieldstring.")";

		p::$db->cacheQuery("INSERT IGNORE INTO ".$this->_table." $fieldString  VALUES (".$this->_valuestring.");");

		

		return p::$db->lastInsertId();
	}




	// Only works with all columns
	public function insertSelectCount($table, $where){

		p::$db->cacheQuery("INSERT INTO ".$this->_table." SELECT ".$this->_valuestring." FROM ".$table." WHERE ".$where.";");

		return p::$db->lastInsertId();
	}

	public function changePagination($value){
		$this->_paginated = $value;
	}

	public function overrideSelectSQL($sql){
		$this->_newSQL = $sql;
	}

	public function join($table, $field){
		$this->_joined[] = array('table' => $table, 'field' => $field);
	}

	public function complexQuery($query){
		$this->_data = p::$db->cacheQuery($query);
		return $this->_data;
	}

	public function cleanCache($table){
		return p::$db->CleanCache('queries', $table);
	}

	protected function resultToSingleArray($query, $field){
		$query = $this->complexQuery($query);
		$array = array();
		foreach($query->fetchAll() as $result)
			$array[] = $result[$field];
		return $array;
	}

}

