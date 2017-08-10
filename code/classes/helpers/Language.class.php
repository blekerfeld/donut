<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: language.class.php

class pLanguage{

	public $id, $data, $dataModel; 
	public static $_languageCache = array();

	// This would work only with 'new pLanguage'
	public function __toString(){
		return $this->id;
	}

	// Constructure accepts both a language code or a number
	public function __construct($id){
		if(isset(self::$_languageCache[$id]))
			return $this->load(self::$_languageCache[$id]);
		$this->dataModel = new pDataModel('languages');
		if(is_numeric($id))
			$this->dataModel->getSingleObject($id);
		else{
			$this->dataModel->setCondition(" WHERE locale = '".$id."'");
			$this->dataModel->getObjects();
			if($this->dataModel->data()->rowCount() == 0 OR $this->dataModel->data()->rowCount() > 1)
				die("Could not fetch language");
		}
		$this->load($this->dataModel->data()->fetchAll()[0]);
		self::$_languageCache[$id] = $this->dataModel->data()->fetchAll()[0];
	}

	public static function allActive($notIs = 0){

		$data = (new pDataModel('languages'));
		$data->setCondition(" WHERE activated = 1 AND id <> ".$notIs);
		$array = array();
		foreach($data->getObjects()->fetchAll() as $lang){
			$array[] = new self($lang['id']);
		}
		return $array;
	}


	// Makes it possible to do pLanguage::dictionarySelector($class);
	public static function dictionarySelector($class){

		$dM = (new pDataModel('languages'));
		$dM->setCondition(" WHERE activated = 1 ");
		$data = $dM->getObjects()->fetchAll();

		$select = '<select class="'.$class.'">';

		$lang_zero = $data[0];

		$select .= '<option data-native="1" value="'.$lang_zero['locale'].'-'.$lang_zero['locale'].'" '.((isset(pRegister::session()['searchLanguage']) AND (pRegister::session()['searchLanguage'] == $lang_zero['locale'].'-'.$lang_zero['locale'])) ? ' selected ' : '').'>'.$lang_zero['showname'].'</option>';

		foreach($data as $key => $language)
			if($language['id'] > 0)
				$select .= '<option data-native="0" value="'.$language['locale'].'-'.$lang_zero['locale'].'" '.((isset(pRegister::session()['searchLanguage']) AND (pRegister::session()['searchLanguage'] == $language['locale'].'-'.$lang_zero['locale'])) ? ' selected ' : '').'>'.$language['showname'].'/'.$lang_zero['showname'].'</option><option  data-native="1" value="'.$lang_zero['locale'].'-'.$language['locale'].'" '.((isset(pRegister::session()['searchLanguage']) AND (pRegister::session()['searchLanguage'] == $lang_zero['locale'].'-'.$language['locale'])) ? ' selected ' : '').'>'.$lang_zero['showname'].'/'.$language['showname'].'</option>';

	    
	  	return $select . '</select><script>$(".'.$class.'").selectorTabs({"afterclick": function(){
	  			doSearch(false);
	  			var act = $(".'.$class.' option:selected").attr("data-native");
	  			if(act == 1){
	  				$(".word-search").addClass("native");
	  			}
	  			else{
					$(".word-search").removeClass("native");
	  			}
	  	}});</script>';
	}

	private function load($data){
		$this->id = $data['id'];
		$this->data = $data; 
	}

	public function parse(){
		return $this->data['name'];
	}

	// This will read out the given field
	public function read($key){
		if(array_key_exists($key, $this->data))
			return $this->data[$key];
		return false;
	}
	

}