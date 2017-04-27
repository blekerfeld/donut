<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dispatcher.cset.php


// This class takes the url-querystring, reads and understands it (with help of dispatch.struct.php in the structure-folder) and creates all the parsers and objects needed in order to route to the desired place.

class pDispatch {

	private $_dispatchData, $_magicArguments = array('offset', 'ajax', 'return');
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
		if(isset($urlArguments[0]) AND isset($this->_dispatchData[$urlArguments[0]]))
			$templateArguments = $this->_dispatchData[$urlArguments[0]]['arguments'];

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
			return die("ABORT MISSION. CLASS $structureType DOES NOT EXIST.");

		$this->structureObject = new $structureType($structureName[0], (isset($structureName[1]) ? $structureName[1] : ''), $urlArguments[0], $this->_dispatchData[$urlArguments[0]]['default_section'], $this->_dispatchData[$urlArguments[0]]['page_title']);


		// Handeling offset and ajax
		if(in_array('offset', $urlArguments)){
			// This means the next is the offset

			$keyOffset = array_search('offset', $urlArguments) + 1;

			$arguments['offset'] = $urlArguments[$keyOffset];

			// Unsetting this
			unset($urlArguments[$keyOffset]);
			unset($urlArguments[$keyOffset - 1]);
		}
		if(in_array('position', $urlArguments)){
			// This means the next is the offset

			$keyPos = array_search('position', $urlArguments) + 1;

			$arguments['position'] = $urlArguments[$keyPos];

			// Unsetting this
			unset($urlArguments[$keyPos]);
			unset($urlArguments[$keyPos - 1]);
		}

		if(in_array('search', $urlArguments)){
			// This means the next is the offset

			if(isset($urlArguments[array_search('search', $urlArguments) - 1]) AND $urlArguments[array_search('search', $urlArguments) - 1] == 'entry'){
				$keySearch = array_search('search', $urlArguments) + 2;
				$keyDict = array_search('search', $urlArguments) + 1;

				$arguments['query'] = $urlArguments[$keySearch];
				$arguments['dictionary'] = $urlArguments[$keyDict];

				// Unsetting this
				unset($urlArguments[$keySearch]);
				unset($urlArguments[$keyDict]);
				unset($urlArguments[$keySearch - 2]);

			}
		}

		if(in_array('return', $urlArguments)){
			// This means the next is the offset

			$keyReturn = array_search('return', $urlArguments) + 1;

			$arguments['return'] = $urlArguments[$keyReturn];

			// Unsetting this
			unset($urlArguments[$keyReturn]);
			unset($urlArguments[$keyReturn - 1]);
		}
		if(in_array('ajax', $urlArguments)){
			$arguments['ajax'] = true;
			$keyAjax = array_search('ajax', $urlArguments);
			unset($urlArguments[$keyAjax]);
		}

		//A section can be optional
		if(!in_array('section', $templateArguments) AND (count($urlArguments) == count($templateArguments) + 1)){
			$arguments['section'] = $urlArguments[1];
			unset($urlArguments[1]);
		}
		// If no section is given, we need to correct something
		elseif(!in_array('section', $templateArguments) AND (count($urlArguments)  != count($templateArguments) + 1)){

			$templateArgumentsNew = array();
			foreach($templateArguments as $key => $value)
				$templateArgumentsNew[$key - 1] = $value;
			$templateArguments = $templateArgumentsNew;
		}


		// The default action has to empty
		if(in_array('action', $templateArguments) and !isset($urlArguments[array_search('action', $templateArguments)])){
			$urlArguments[array_search('action', $templateArguments)] = '';
			unset($templateArguments[array_search('action', $templateArguments)]);
			$arguments['action'] = '';
		}

		// Now it's time to go through the arguments!
		$countArguments = 0;
		foreach($templateArguments as $key => $templateArgument)
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