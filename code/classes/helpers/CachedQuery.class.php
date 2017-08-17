<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: CachedQuery.class.php

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




// This class is used to have a collection of classes somewhere inside another class.
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

