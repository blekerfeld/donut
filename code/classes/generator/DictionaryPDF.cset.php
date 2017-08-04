<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dictionaryfactory.cset.php

class pDictionaryPDF{

	protected $_sections = array(), $_css = null, $_mPDF;

	public function __construct(){
		// instantiate and use the mPDF class
		$this->_mPDF = new \Mpdf\Mpdf(array('mode' => 'utf-8', 'format' => 'DEMY'));
		$this->_mPDF->setFooter('{PAGENO}');
	}

	public function loadCSS($css){
		$this->_css = $css;
	}

	public function addSection(){
		// This will empty the p-output, but save what we need.
		p::Out("<div style='page-break-before: always;'></div>");
		$newP = new p; 
		$this->_sections[] = (string)$newP;
		p::Empty();
	}


	public function buildHTML(){
		return '<html>
		
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            '."<LINK rel='stylesheet' href='".p::Url('library/assets/css/generate.css')."' type='text/css'>".'
            '."<LINK rel='stylesheet' href='".p::Url('library/assets/css/markdown.css')."' type='text/css'>".'
            '.($this->_css != null ? "<LINK rel='stylesheet' href='".p::Url('library/assets/css/'.$this->_css.'.css')."' type='text/css'>" : '') .'
        </head>
        <body class="markdown-body">
        	'.implode("\n", $this->_sections).'
        </body>
        </html>';
	}

	public function pass(){
		$this->_mPDF->WriteHTML($this->buildHTML());
		return $this->_mPDF;
	}
}