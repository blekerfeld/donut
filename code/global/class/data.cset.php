<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: data.cset.php

class pCachedQuery implements Iterator{


	// The few variables this class needs
	private $_row_count = 0;
	private $_array_count = 0;
	private $_db_objects = null;
	private $position = -1;
	private $_query = null;


   function __construct($db_objects = array(), $row_count = 0, $query = '') {
       $this->_row_count = $row_count;
       $this->_db_objects = $db_objects;
       $this->_db_objects = new ArrayObject($db_objects);
       $this->_query = $query;
   }

   // Returns the fixed row count of the original query
   function rowCount(){
   		return $this->_row_count;
   }

   function fetchAll(){
   		$array = array();
   		foreach ($this->_db_objects as $object) {
   			$array[] = (array)$object;
   		}
   		return $array;
   }

   // Return the next object, as the original query would
   function fetchObject(){
   		return $this->current(true);
   }

   // Rewind up to -1, so that the object can be used again.
    function rewind() {
        $this->position = -1;
    }

    function current($object = false) {
    	$this->next();
    	if($object)
        	return @$this->_db_objects[$this->position];
        else
        	return @get_object_vars($this->_db_objects[$this->position]);
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->_db_objects[$this->position]);
    }

}


class pDataObject {

	private $_fields = null, $_table, $_fieldstring, $_valuestring, $_updateid, $_updatestring, $_singleId, $_paginated, $_data;

	public function __construct($table, $fields, $paginated = true){
		$this->_fields = $fields;
		$this->_table = $table;
		$this->_paginated = $paginated;
		$fieldString = array('id');
		foreach($this->_fields->get() as $field)
			$fieldString[] = $field->name;
		$this->_fieldstring = implode(",", $fieldString);
	}

	public function getSingleObject($id, $condition = ''){
		$this->_singleId = $id;
		$this->_data =  pQuery("SELECT ".$this->_fieldstring." FROM ".$this->_table." WHERE id = ".$id." ".$condition." LIMIT 1");
		return  $this->_data;
	}

	public function getObjects($offset, $itemsperpage, $condition = ''){
		$this->_data = pQuery("SELECT ".$this->_fieldstring." FROM ".$this->_table." ".$condition.(($this->_paginated) ? " LIMIT ".$offset.",".$itemsperpage : '').";");
		return  $this->_data;
	}

	// Prepare funcition to prepare the dataObject for new data
	public function prepareForInsert($data){
		global $donut;
		if(count($data) != count($this->_fields->get()))
			die("FATAL ERROR from within pDataObject->prepareForInsert(\$data). \$data does not match the field count of the object!");
		$valueString = array('NULL');
		$key = 0;
		foreach($this->_fields->get() as $field){
			$valueString[] = pQuote($data[$key]);
			$key++;
		}
		$this->_valuestring = implode(', ', $valueString);
	}

	// Prepare funcition to prepare the dataObject for new data
	public function prepareForUpdate($data, $id = -1){

		global $donut;

		if($id == -1)
			$id = $this->_singleId;

		if(count($data) != count($this->_fields->get()))
			die("FATAL ERROR from within pDataObject->prepareForUpdate(..., \$data). \$data does not match the field count of the object!");
		
		$updateString = array();
		$key = 0;
		foreach ($this->_fields->get() as $field) {
			if($field->name != 'id')
				$updateString[] = $field->name."= ".pQuote($data[$key]);
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
			return pQuery("DELETE FROM ".$this->_table,";");
		
		// This will go through follow_up, to delete any records that need to be gone first. 
		if(is_array($follow_up)){
			foreach($follow_up as $key => $table){
				if(is_array($follow_up_field)){
					if(count($follow_up_field) != count ($follow_up))
						die("FATAL ERROR from within pDataObject->remove(...\$follow_up_field...). \$follow_up_field does not match the field count of the follow_up!");
					$field = $follow_up_field[$key];
				}
				else
					$field = follow_up_field;

				$tempSet = new pSet;
				$tempSet->add(new pDataField($field)); 
				$tempObject = new pDataObject($table, $tempSet);
				$tempObject->remove($selective, $field);
			}

		}

		return pQuery("DELETE FROM ".$this->_table." WHERE id = ".$this->_field = " ".pQuote($selective).";");

	}

	public function update(){

		return pQuery("UPDATE ".$this->_table." SET ".$this->_updatestring." WHERE id = '".$this->_updateid."';");
	}

	public function count(){
		return $this->_data->rowCount();
	}

	public function countAll($condition = '1'){

		$count = (pQuery("SELECT count(id) AS total FROM ".$this->_table." WHERE ".$condition.";"))->fetchObject();

		return $count->total;
	}

	public function insert(){
		return pQuery("INSERT INTO ".$this->_table." (".$this->_fieldstring.") VALUES (".$this->_valuestring.");");
	}

	public function changePagination($value){
		$this->_paginated = $value;
	}

}

class pSet{

	private $_fields;

	public function __construct(){
		$this->_fields = array();
	}

	public function add($field){
		if(isset($field->name))
			$this->_fields[$field->name] = $field;
		else
			$this->_fields[] = $field;
	}

	public function remove($field){
		unset($this->_fields[$field->name]);
	}

	public function get(){
		return $this->_fields;
	}

}

class pDataField{

	public $name, $width, $surface, $type, $showInTable, $showInForm, $required, $selectionValues, $class, $disableOnNull;

	public function __construct($name, $surface = '', $width= '20%', $type = '', $showTable = true, $showForm = true, $required = false, $class = '', $disableOnNull, $selection_values = null){
		$this->name = $name;
		$this->width = $width;
		$this->type = $type;
		$this->surface = $surface;
		$this->showInTable = $showTable;
		$this->showInForm = $showForm;
		$this->required = $required;
		$this->class = $class;
		$this->disableOnNull = $disableOnNull;
		$this->selectionValues = $selection_values;
	}

	public function parse($value = '', $output = ''){
		
		if($this->type == 'flag')
			$output = "<img class='$this->class flagimage' src='".(trim($value) == '' ? pUrl('pol://library/images/flags/undef.png') : pUrl('pol://library/images/flags/'.$value.'.png'))."' />";
		
		elseif($this->type == 'image')
			$output = "<img class='$this->class' src='".$value."' />";

		elseif($this->type == 'boolean')
			$output = (new pFieldBoolean($value))->render();

		else
			$output = "<span class='".$this->class."'>".$value."</span>";

		return $output;
	}

}
