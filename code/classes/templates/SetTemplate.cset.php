<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: SetTemplate.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pSetTemplate extends pTemplate{



	public function renderTable(){

		p::Out(new pAjaxLoader(p::Url('?'.$this->_data->_app.'/'.$this->_data->_section.'/new/'.(isset(pAdress::arg()['id']) ? pAdress::arg()['id'].'/' : 'rules/').'ajaxLoad'), false, true, array('btAction no-float small', 'New folder'), 'hSearchResults-inner padding-5 form-new', true));

		$data = $this->_data->_dataModel->data()->fetchAll()[0];
		p::Out("<br /><span class='pSectionTitle extra'>".(new pIcon('fa-folder', 12))." <strong>".$this->breakDownName($data['name'], $this->_data->_app)."</strong></span><div class='pSectionWrapper'><table class='rules'>
					");
		// One level up
		if($data['parent'] != -1){
			p::Out("<tr><td style='width:15px;'></td><td><a href='".p::Url('?'.$this->_data->_app.'/view/'.str_replace('/', ":", dirname($data['name'])))."'>...</a></td><td></td></tr>");
		}

		p::Out($this->renderFolders());
		p::Out($this->renderRules());

		// A placeholder for emptiness
		if(count($this->_data->_rules) == 0 AND count($this->_data->_ruleSets) == 0)
			p::Out("<tr><td style='width:15px;'></td><td><span class='dType'>".DA_NO_RECORDS."</span></td><td></td></tr>");


		p::Out("</table></div>");
	}

	protected function renderFolders(){
		$output = '';

		foreach($this->_data->_ruleSets AS $ruleset)
			$output .= "<tr><td style='width: 15px;'>".(new pIcon('fa-folder', 12))."</td>
						<td style='width: 40%;'>".$this->breakDownName($ruleset['name'], $this->_data->_app, true)."</td>
			<td></td></tr>";

		return $output;
	}

	public static function breakDownName($name, $app, $last = false){
		$output = "";
		$count = 0;
		// No slash at start then
		if(p::StartsWith($name, '/'))
			$name = substr($name, 1);
		$explode = explode('/', $name);
		$size = count($explode);
		$string = ':';
		if($last){
			$explode = array($explode[max(array_keys($explode))]);
			$size = 0;
		}
		foreach($explode as $subname){
			$output .= "<a href='".p::Url('?'.$app.'/view/'.($last ? str_replace('/', ':', $name) : $string.$subname.":"))."'>".($last ? '' : '/')."$subname</a>";
			$string .= $subname.":";
			if($size > 1 AND $count != $size - 1)
				$output .=" → ";
			$count++;
		}
		return $output;
	}

	protected function renderRules(){

		$output = '';

		foreach($this->_data->_rules AS $rule)
			$output .= "<tr><td style='width: 15px;'>".(new pIcon($this->_data->_activeSection['sets'][$rule['set_type']][2], 12))."</td>
						<td style='width:40%'><a href='".p::Url('?'.$this->_data->_activeSection['editor'].'/'.$rule['set_type'].'/edit/'.$rule['id'])."'>".$rule['name']."</a></td>
			<td><span class='dType'>".$this->_data->_activeSection['sets_strings'][$rule['set_type']]."</span></td></tr>";

		return $output;

	}	

	public function renderNew(){
		
	}

}