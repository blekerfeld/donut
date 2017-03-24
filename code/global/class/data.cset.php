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

	private $_fields = null, $table = null, $_fieldstring, $_valuestring, $_updateid, $_updatestring;

	public function __construct($table, $fields){
		$this->_fields = $fields;
		$this->_table = $table;
		$fieldString = array('id');
		foreach($this->_fields->get() as $field)
			$fieldString[] = $field->name;
		$this->_fieldstring = implode(",", $fieldString);
	}

	public function getSingleObject($id, $condition = ''){
		return pQuery("SELECT ".$this->_fieldstring." FROM ".$this->_table." WHERE id = ".$id." ".$condition." LIMIT 1");
	}

	public function getObjects($offset, $itemsperpage, $condition = ''){

		return  pQuery("SELECT ".$this->_fieldstring." FROM ".$this->_table." ".$condition." LIMIT ".$offset.",".$itemsperpage.";");
	}

	// Prepare funcition to prepare the dataObject for new data
	public function prepareForInsert($data){
		global $donut;
		if(count($data) != count($this->_fields->get()))
			die("FATAL ERROR from within pDataObject->prepareForInsert(\$data). \$data does not match the field count of the object!");
		$valueString = array('NULL');
		foreach($this->_fields->get() as $key => $field){
			$valueString[] = pQuote($data[$key]);
		}
		$this->_valuestring = implode(', ', $valueString);
	}

	// Prepare funcition to prepare the dataObject for new data
	public function prepareForUpdate($id, $data){
		global $donut;
		if(count($data) != count($this->_fields->get()))
			die("FATAL ERROR from within pDataObject->prepareForUpdate(..., \$data). \$data does not match the field count of the object!");
		
		$updateString = array();
		$key = 0;
		foreach ($this->_fields->get() as $field) {
			if($field->name != 'id')
				$updateString[] = $field->name."= ".pQuote($data[$key]).";";
			$key++; 
		}

		$this->_updatestring = implode(', ', $updateString);
	}

	public function remove($selective = 0, $field = 'id', $follow_up = 0, $follow_up_field = 0){
		// Follow up can be an array of other tables, that have potentionaly have references to the deleted record from the main table. Those are to be deleted as well.
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

			return pQuery("DELETE FROM ".$this->_table." WHERE ".$this->_field = " ".pQuote($selective).";");

		}

	}

	public function update(){
		return pQuery("UPDATE ".$this->_table." SET ".$this->_updatestring." WHERE id = ".$this->_updateid.";");
	}

	public function insert(){
		return pQuery("INSERT INTO ".$this->_table." (".$this->_fieldstring.") VALUES (".$this->_valuestring.");");
	}

}

class pSet{

	private $_fields;

	public function __construct(){
		$this->_fields = array();
	}

	public function add($field){
		$this->_fields[spl_object_hash($field)] = $field;
	}

	public function remove($field){
		unset($this->_fields[spl_object_hash($field)]);
	}

	public function get(){
		return $this->_fields;
	}

}

class pDataField{

	public $name, $width, $surface;

	public function __construct($name, $surface = '',$width= '20%'){
		$this->name = $name;
		$this->width = $width;
		$this->surface = $surface;
	}

}
