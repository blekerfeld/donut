<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      dispatcher.class.php


// This class takes the url-querystring, reads and understands it (with help of dispatch.struct.php in the structure-folder) and creates all the parsers and objects needed in order to route to the desired place.

class pDispatcher {

	private $_dispatchData, $_magicArguments = array(array('is:result', 'ajax', 'ajaxLoad', 'ajaxLoader', 'showtabs', 'nosearch', 'print'), array('offset', 'return', 'position', 'language', 'token', 'pre-filled'), array(array('search', 'dictionary', 'query'))), $_urlArguments, $_arguments, $_markdownNames;

	public $query, $structureObject;

	public static $active, $structure;

	public function __construct($overrideQueryStringIfEmpty = false){
		$query = $_SERVER['QUERY_STRING'];
		if($overrideQueryStringIfEmpty == true AND $query == '')
				$query = 'home';
		$this->query = $query;
		pRegister::queryString($this->query);		
		// Let's pack some superglobals inside pRegister
		pRegister::session($_SESSION);
		pRegister::post($_POST);
		$this->_dispatchData = require_once p::FromRoot("src/Guide.php");
		$this->_markdownNames = $this->_dispatchData['MAGIC_MARKDOWN'];
		self::$structure = $this->_dispatchData;
		unset($this->_dispatchData['MAGIC_MENU']);
		unset($this->_dispatchData['MAGIC_MARKDOWN']);
	}

	public function compile(){

		if(!(isset($this) && get_class($this) == __CLASS__))
			return (new self)->compile();

		// This will be filled with compiled arguments
		$this->_arguments = array();
		$this->_urlArguments = explode("/", $this->query);

		self::$active = $this->_urlArguments[0];

		if(isset($this->_urlArguments[0]) AND isset($this->_dispatchData[$this->_urlArguments[0]]))
			$viewArguments = $this->_dispatchData[$this->_urlArguments[0]]['arguments'];

		// Handeling the magic arguments
		foreach($this->_magicArguments[0] as $single)
			$this->takeApartSingle($single);
		foreach($this->_magicArguments[1] as $double)
			$this->takeApartDouble($double);
		foreach($this->_magicArguments[2] as $tripple)
			$this->takeApartTripple($tripple[0], $tripple[1], $tripple[2]);

		// The first argument decides which structure to use.
		$structureName = explode('-', $this->_urlArguments[0]);

		// We can only create a structure if we have the necessary data!

		if(p::EndsWith($this->_urlArguments[0], '.md'))
			$this->_urlArguments[0] = substr($this->_urlArguments[0], 0, -3);

		if(!isset($this->_dispatchData[$this->_urlArguments[0]])){
			if(file_exists(p::FromRoot("static/md/".$this->_urlArguments[0].".md"))){
				pRegister::arg($this->_arguments);
				$searchBox = new pSearchBox;
				$searchBox->enablePentry();
				if(isset(pRegister::arg()['is:result'], pRegister::freshSession()['searchQuery']))
					$searchBox->setValue(pRegister::freshSession()['searchQuery']);
				pTemplate::throwOutsidePage($searchBox);
				p::Out("<div class='home-margin pEntry'>");

				if(isset($this->_markdownNames[$this->_urlArguments[0]]['name'], $this->_markdownNames[$this->_urlArguments[0]]['icon']))
					p::Out((new pTabBar($this->_markdownNames[$this->_urlArguments[0]]['name'],$this->_markdownNames[$this->_urlArguments[0]]['icon'], true, 'titles pEntry-fix-50'))->addHome()->addSearch());

				p::Out(p::Markdown(file_get_contents(p::FromRoot("static/md/".$this->_urlArguments[0].".md")), true, true, true)."</div>");
			}
			elseif($this->_urlArguments[0] == 'README' AND file_exists(p::FromRoot("README.md"))){
				pRegister::arg($this->_arguments);
				p::Out("<div class='home-margin'>".p::Markdown(file_get_contents(p::FromRoot("README.md")), true)."</div>");
			}
			else
				$this->do404();
			return new pDispatchException;
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
			$this->structureObject = new pStructure($structureName[0], (isset($structureName[1]) ? $structureName[1] : ''), $this->_urlArguments[0], $this->_dispatchData[$this->_urlArguments[0]]['default_section'], $this->_dispatchData[$this->_urlArguments[0]]['page_title'], $this->_dispatchData[$this->_urlArguments[0]]);
		}
		else{
			// Else we need to create the almighty structure
			$this->structureObject = new $structureType($structureName[0], (isset($structureName[1]) ? $structureName[1] : ''), $this->_urlArguments[0], $this->_dispatchData[$this->_urlArguments[0]]['default_section'], $this->_dispatchData[$this->_urlArguments[0]]['page_title'], $this->_dispatchData[$this->_urlArguments[0]]);
		}

		pRegister::app($this->_urlArguments[0]);
		
		// Let's prepare our structureObject a little, so that we can access the full structure here
		$this->structureObject->load();

		// The section can be optional
		if(isset($this->_urlArguments[1], $viewArguments[0]))
			if($viewArguments[0] != 'section'){
				if(array_key_exists($this->_urlArguments[1], $this->structureObject->_prototype)){
					// Now we have a section after all
					$viewArguments = array_combine(range(1, count($viewArguments)), array_values($viewArguments));
					$viewArguments[0] = 'section';
				}
			}
		// Optional trailing slashes
		if(!isset($this->_urlArguments[1]))
			$this->_urlArguments[1] = '';

		// Now it's time to go through the arguments!
		$countArguments = 0;

		foreach($viewArguments as $key => $viewArgument)
			if(isset($this->_urlArguments[$key + 1]) AND $this->_urlArguments[$key + 1] != '')
				$this->_arguments[$viewArgument] = $this->_urlArguments[$key + 1];
				$countArguments++;

		// Let's bind this information to our static adress
		pRegister::arg($this->_arguments);

		$this->structureObject->compile();

		return $this;
	}

	public function dispatch(){
		// Let's render our object and return the view :D
		$this->structureObject->render();
		// It might be that we need to call the print view instead...
		if(isset(pRegister::arg()['print']))
			return new pPrintTemplate;
		// Calling the view
		return new pTemplate;
	}

	protected function do404($extra = ''){
		pTemplate::setNoBorder();
		p::Out("<div class='danger-notice'><strong>".(new pIcon('fa-warning'))." ".ERROR_404_TITLE."</strong><br /><br />".
				ERROR_404_MESSAGE."</div>");
	}

	public static function abortError($message){
		pTemplate::setNoBorder();
		p::Out("<div class='danger-notice'><strong>".(new pIcon('fa-warning'))." ".ERROR_TITLE."</strong><br /><br />".
				$message."</div>");
		return new pDispatchException;
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
		// If we are not dispatching, we don't have a structure...
		if(self::$structure == null)
			// ...therefore we need to load it.
			self::$structure = require_once p::FromRoot("code/Dispatch.php");
		return self::$structure;
	}

	public static function addMenuItem(){
		$structure = self::structure();
	}

}

class pDispatchException{

	public function dispatch(){
		// We will just have to do with an empty view then.
		return (new pTemplate);	
	}

}