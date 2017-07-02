<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: language.cset.php

class pLanguage{

	public $id, $data, $dataModel; 


	// This would work only with 'new pLanguage'
	public function __toString(){
		return $this->id;
	}

	// Constructure accepts both a language code or a number
	public function __construct($id){
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
	}

	// Makes it possible to do pLanguage::dictionarySelector($class);
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

		$select = '<input type="hidden" class="'.$class.'" value="'.(isset(pRegister::session()['searchLanguage']) ? pRegister::session()['searchLanguage'] : $data[1]['locale'].'-'.$data[0]['locale']).'"/><select class="'.$class.'-selector">';

		$lang_zero = $data[0];

		foreach($data as $key => $language)
			if($key > 0)
				$select .= '<option value="'.$language['locale'].'-'.$lang_zero['locale'].'" '.((isset(pRegister::session()['searchLanguage']) AND (pRegister::session()['searchLanguage'] == $language['locale'].'-'.$lang_zero['locale'])) ? ' selected ' : '').'>'.$language['name'].' - '.$lang_zero['name'].'</option><option value="'.$lang_zero['locale'].'-'.$language['locale'].'" '.((isset(pRegister::session()['searchLanguage']) AND (pRegister::session()['searchLanguage'] == $lang_zero['locale'].'-'.$language['locale'])) ? ' selected ' : '').'>'.$lang_zero['name'].' - '.$language['name'].'</option>';

	    
	  	return $select . '</select><script>$(".'.$class.'-selector").ddslick({
	    onSelected: function(selectedData){
	       $(".'.$class.'").val(selectedData.selectedData.value);
	       $(".'.$class.'").trigger("change");
	    }   
	});</script>';
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