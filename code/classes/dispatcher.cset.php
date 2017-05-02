<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dispatcher.cset.php


// This class takes the url-querystring, reads and understands it (with help of dispatch.struct.php in the structure-folder) and creates all the parsers and objects needed in order to route to the desired place.

class pDispatcher {

	private $_dispatchData, $_magicArguments = array(array('is:result', 'ajax', 'nosearch'), array('offset', 'return', 'position'), array(array('search', 'dictionary', 'query'))), $_urlArguments, $_arguments;

	public $query, $structureObject;

	public static $active, $structure;

	public function __construct($query){
		$this->query = $query;
		pAdress::queryString($this->query);
		$this->_dispatchData = require_once pFromRoot("code/Dispatch.php");
		self::$structure = $this->_dispatchData;
		unset($this->_dispatchData['META_MENU']);
	}

	public function compile(){
		// This will be filled with compiled arguments
		$this->_arguments = array();
		$this->_urlArguments = explode("/", $this->query);

		self::$active = $this->_urlArguments[0];

		if(isset($this->_urlArguments[0]) AND isset($this->_dispatchData[$this->_urlArguments[0]]))
			$templateArguments = $this->_dispatchData[$this->_urlArguments[0]]['arguments'];

		// The first argument decides which structure to use.
		$structureName = explode('-', $this->_urlArguments[0]);

		// We can only create a structure if we have the necessary data!
		if(!isset($this->_dispatchData[$this->_urlArguments[0]])){
			if(file_exists(pFromRoot("static/".$this->_urlArguments[0].".md")))
				pOut("<div class='home-margin'>".pMarkdown(file_get_contents(pFromRoot("static/".$this->_urlArguments[0].".md")), true, true, true)."</div>");
			// DEBUG MODE ONLY
			elseif($this->_urlArguments[0] == 'debug' AND file_exists(pFromRoot("debug.php"))){
				pOut("<div class='home-margin'>");
				require pFromRoot("debug.php");
				pOut("</div>");
			}
			elseif($this->_urlArguments[0] == 'README' AND file_exists(pFromRoot("README.md")))
				pOut("<div class='home-margin'>".pMarkdown(file_get_contents(pFromRoot("README.md")), true)."</div>");
			else
				$this->do404();
			return false;
		}

		// Generating the structure class name;
		if(isset($structureName[1]))
			$structureType = "p".ucwords($structureName[1])."Structure";
		else
			$structureType = "p".ucwords($structureName[0])."Structure";

		// Struture Type can be overriden
		if(isset($this->_dispatchData[$this->_urlArguments[0]]['override_structure_type']))
			$structureType = $this->_dispatchData[$this->_urlArguments[0]]['override_structure_type'];


		if(!class_exists($structureType)){
			$this->do404("<br /> `CLASS $structureType DOES NOT EXIST.`");
			return false;
		}

		$this->structureObject = new $structureType($structureName[0], (isset($structureName[1]) ? $structureName[1] : ''), $this->_urlArguments[0], $this->_dispatchData[$this->_urlArguments[0]]['default_section'], $this->_dispatchData[$this->_urlArguments[0]]['page_title'], $this->_dispatchData[$this->_urlArguments[0]]);


		// Handeling the magic arguments
		foreach($this->_magicArguments[0] as $single)
			$this->takeApartSingle($single);
		foreach($this->_magicArguments[1] as $double)
			$this->takeApartDouble($double);
		foreach($this->_magicArguments[2] as $tripple)
			$this->takeApartTripple($tripple[0], $tripple[1], $tripple[2]);

		// Optional trailing slashes
		if(!isset($this->_urlArguments[1]))
			$this->_urlArguments[1] = '';

		//A section can be optional
		if(!in_array('section', $templateArguments) AND (count($this->_urlArguments) == count($templateArguments) + 1)){
			@$this->_arguments['section'] = $this->_urlArguments[1];
			unset($this->_urlArguments[1]);
		}
		// If no section is given, we need to correct something
		elseif(!in_array('section', $templateArguments) AND (count($this->_urlArguments)  != count($templateArguments) + 1)){

			$templateArgumentsNew = array();
			foreach($templateArguments as $key => $value)
				$templateArgumentsNew[$key - 1] = $value;
			$templateArguments = $templateArgumentsNew;
		}


		// The default action has to empty
		if(in_array('action', $templateArguments) and !isset($this->_urlArguments[array_search('action', $templateArguments)])){
			$this->_urlArguments[array_search('action', $templateArguments)] = '';
			unset($templateArguments[array_search('action', $templateArguments)]);
			$this->_arguments['action'] = '';
		}

		// Now it's time to go through the arguments!
		$countArguments = 0;
		foreach($templateArguments as $key => $templateArgument)
			// The previous key can't be the offset, or the section for this to work
			if(isset($this->_urlArguments[$key + 1]))
				$this->_arguments[$templateArgument] = $this->_urlArguments[$key + 1];
				$countArguments++;

		// Let's bind this information to our static adress
		pAdress::arg($this->_arguments);

		$this->structureObject->load();
		$this->structureObject->compile();

		return true;
	}

	protected function do404($extra = ''){
		pOut("<div class='home-margin'>".pMarkdown("# ".(new pIcon('fa-warning'))." ".ERROR_404_TITLE."\n".
				ERROR_404_MESSAGE."\n".$extra)."</div>");
	}

	protected function takeApartSingle($arg){
		// This means the next is the offset
		if(in_array($arg, $this->_urlArguments)){
			$keyIs = array_search($arg, $this->_urlArguments);
			$this->_arguments[$arg] = true;
			// Unsetting this
			unset($this->_urlArguments[$keyIs]);
		}
	}

	protected function takeApartDouble($arg){
		if(in_array($arg, $this->_urlArguments)){
			// This means the next is the offset

			$keyReturn = array_search($arg, $this->_urlArguments) + 1;

			$this->_arguments[$arg] = $this->_urlArguments[$keyReturn];

			// Unsetting this
			unset($this->_urlArguments[$keyReturn]);
			unset($this->_urlArguments[$keyReturn - 1]);
		}
	}

	protected function takeApartTripple($arg, $val1, $val2){

			if(isset($this->_urlArguments[array_search($arg, $this->_urlArguments) - 1]) AND $this->_urlArguments[array_search($arg, $this->_urlArguments) - 1] == 'entry'){
				$keySearch = array_search($arg, $this->_urlArguments) + 2;
				$keyDict = array_search($arg, $this->_urlArguments) + 1;

				$this->_arguments[$val2] = $this->_urlArguments[$keySearch];
				$this->_arguments[$val1] = $this->_urlArguments[$keyDict];

				// Unsetting this
				unset($this->_urlArguments[$keySearch]);
				unset($this->_urlArguments[$keyDict]);
				unset($this->_urlArguments[$keySearch - 2]);
			}
	}

	public static function active(){
		return self::$active;
	}

	public static function structure(){
		return self::$structure;
	}

}
