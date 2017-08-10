<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dictionaryfactory.class.php

class pDictionaryPDF{

	protected $_sections = array(), $_css = null, $_mPDF, $_titlePage, $_title;

	public function __construct($title){
		// instantiate and use the mPDF class
		$this->_mPDF = new \Mpdf\Mpdf(array('mode' => 'utf-8', 'format' => 'DEMY'));
		$this->_mPDF->setFooter('{PAGENO}');
		$this->_title = $title;
	}

	public function startBuilding(){
		$this->_mPDF->WriteHTML($this->buildHTMLHead(), 0, true, false);
	}

	public function setTitlePage($title){
		$this->_titlePage = $title;
	}

	public function loadCSS($css){
		$this->_css = $css;
	}

	public function setHeader($header){
		$this->_mPDF->setHeader($header);
		$this->_mPDF->addPage();
	}

	public function getPageNum(){
		return $this->_mPDF->docPageNum();
	}

	public function addSection(){
		// This will empty the p-output, but save what we need.
		$newP = new p; 
		$this->_sections[] = (string)$newP;
		$this->_mPDF->WriteHTML(str_replace(array('<a', '</a>'), array('<span', '</span>'), (string)$newP),  0, false, true);
		p::Empty();
	}



	public function buildHTMLHead(){

		return '<html>
		
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            '."<LINK rel='stylesheet' href='".p::Url('library/assets/css/generate.css')."' type='text/css'>".'
            '."<LINK rel='stylesheet' href='".p::Url('library/assets/css/markdown.css')."' type='text/css'>".'
            '.($this->_css != null ? "<LINK rel='stylesheet' href='".p::Url('library/assets/css/'.$this->_css.'.css')."' type='text/css'>" : '') .'
        </head>
        <body class="markdown-body">'.$this->_titlePage;

	}

	public function buildHTMLFooter(){
		return ' 
        </html>';
	}

	public function pass(){
		$this->_mPDF->WriteHTML($this->buildHTMLFooter(), 0, false, true);
		return $this->_mPDF;
	}
}