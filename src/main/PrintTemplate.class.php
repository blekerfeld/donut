<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: MainView.class.php

class pPrintTemplate{

	protected $_title;

	
  public function __construct(){
		$this->_title = pTemplate::$title;
	}
	

	public function render(){
		?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $this->_title; ?></title>
    <link rel='stylesheet' href='<?php echo p::Url('library/assets/css/print.css'); ?>'>
    <link rel='stylesheet' href='<?php echo p::Url('library/assets/css/markdown.css'); ?>'>
    <script>
      window.print();setTimeout("window.close()", 100);
    </script>
  </head>
	<body class='print markdown-body'>
      <?php echo (new p); ?>
  </body>
 </html><?php
	}
}