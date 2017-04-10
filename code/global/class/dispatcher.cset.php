<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dispatcher.cset.php


// This class takes the url-querystring, reads and understands it (with help of dispatch.struct.php in the structure-folder) and creates all the parsers and objects needed in order to route to the desired place.

class pDispatch {

	private $_dispatchData, $_magicArguments = array('offset', 'ajax');
	public $query, $structureObject;


	public function __construct($query){
		$this->query = $query;
		pAdress::queryString($this->query);
		$this->_dispatchData = require_once pFromRoot("code/structures/dispatch.struct.php");
	}

	public function compile(){
		// This will be filled with compiled arguments
		$arguments = array();

		$urlArguments = explode("/", $this->query);

		// The first argument decides which structure to use.
		$structureName = explode('-', $urlArguments[0]);

		// We can only create a structure if we have the necessary data!
		if(!isset($this->_dispatchData[$urlArguments[0]]))
			return die("ILLEGAL SECTION REQUESTED, ABORT MISSION.");

		// Generating the structure class name;
		if(isset($structureName[1]))
			$structureType = "p".ucwords($structureName[1])."Structure";
		else
			$structureType = "p".ucwords($structureName[0])."Structure";

		// Struture Type can be overriden
		if(isset($this->_dispatchData[$urlArguments[0]]['override_structure_type']))
			$structureType = $this->_dispatchData[$urlArguments[0]]['override_structure_type'];

		if(!class_exists($structureType))
			return die("ABORT MISSION.");

		$this->structureObject = new $structureType($structureName[0], (isset($structureName[1]) ? $structureName[1] : ''), $urlArguments[0], $this->_dispatchData[$urlArguments[0]]['default_section'], $this->_dispatchData[$urlArguments[0]]['page_title']);


		// Handeling offset and ajax
		if(in_array('offset', $urlArguments)){
			// This means the next is the offset

			$keyOffset = array_search('offset', $urlArguments) + 1;

			$arguments['offset'] = $urlArguments[array_search('offset', $urlArguments) + 1];

			// Unsetting this
			unset($urlArguments[$keyOffset]);
			unset($urlArguments[$keyOffset - 1]);
		}

		// Now it's time to go through the arguments!
		$countArguments = 0;
		foreach($this->_dispatchData[$urlArguments[0]]['arguments'] as $key => $templateArgument)
			// The previous key can't be the offset, or the section for this to work
			if(isset($urlArguments[$key + 1]))
				$arguments[$templateArgument] = $urlArguments[$key + 1];
				$countArguments++;

		// Let's bind this information to our static adress
		pAdress::arg($arguments);

		$this->structureObject->load();
		$this->structureObject->compile();
	}

}


// For jQuery style fetching of the url information! :)
// like this: pAdress::arg()['id'];
class pAdress{

	static $queryString, $arguments;

	public static function arg($set = null){
		if($set != null)
			return self::$arguments = $set;
		return self::$arguments;
	}

	public static function queryString($set = null){
		if($set != null)
			return self::$queryString = $set;
		return self::$queryString;
	}

}