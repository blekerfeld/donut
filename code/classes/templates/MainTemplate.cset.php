<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: MainTemplate.cset.php

class pMainTemplate extends pTemplate{

	protected $_stylesheets, $_scripts;

	public static $title = CONFIG_SITE_TITLE, $orgTitle = CONFIG_SITE_TITLE, $_searchBoxShown = false, $outside;

	public static function setTitle($title){
		self::$title = $title . ' - ' . CONFIG_SITE_TITLE;
	}

  public static function throwOutsidePage($content){
    self::$outside .= "\n".$content;
  }

	protected function loadCSS($stylesheet){
		$this->_stylesheets[] = "<link rel='stylesheet' href='".p::Url('library/assets/css/'.$stylesheet)."'>\n";
	}

	protected function loadJavascript($url){
		$this->_scripts[] = "<script type='text/javascript' src='".p::Url('library/assets/js/'.$url)."'></script>\n";

	}

  public static function allowTabs(){
    return "$(document).delegate('.allowtabs', 'keydown', function(e) {
      var keyCode = e.keyCode || e.which;

      if (keyCode == 9) {
        e.preventDefault();
        var start = $(this).get(0).selectionStart;
        var end = $(this).get(0).selectionEnd;

        // set textarea value to: text before caret + tab + text after caret
        $(this).val($(this).val().substring(0, start)
                    + '\t'
                    + $(this).val().substring(end));

        // put caret at right position again
        $(this).get(0).selectionStart =
        $(this).get(0).selectionEnd = start + 1;
      }
    });";
  }

	protected function userBox(){
    if(pUser::noGuest())
		  return (pUser::read('longname') != '' ? pUser::read('longname') : pUser::read('username'))." <a href='".p::Url('?auth/logout')."'>(".MMENU_LOGOUT.")</a>";
	}

  public static function toggleSearchBox(){
    self::$_searchBoxShown = true;
  }

  public static function NoticeBox($icon, $message, $type='notice', $id=''){
    return '<div class="'.$type.'" id="'.$id.'"><i class="fa '.$icon.'"></i> '.$message.'</div>';
  }

  public function renderMinimal(){
    echo implode("\n", $this->_stylesheets);
    echo implode("\n", $this->_scripts);
    echo (new p);
  }

	public function __construct(){
		foreach (p::$assets['css'] as $css)
			$this->loadCSS($css);
		foreach (p::$assets['javascript'] as $js)
			$this->loadJavascript($js);
	}
	

	public function render(){
		if(isset(pRegister::arg()['ajax']) OR isset(pRegister::arg()['ajaxLoad']))
			return die(new p);
		?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo self::$title; ?></title>
    <link rel="shortcut icon" href="<?php echo p::Url('library/images/static/favicon.png'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
		<?php
		echo implode("\n", $this->_stylesheets);
		echo implode("\n", $this->_scripts);
		?>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#1B2B34"
    },
    "button": {
      "background": "#289B2B"
    }
  },
  "showLink": false,
  "position": "bottom-left",
  "content": {
    "message": "<?php echo COOKIES_MSG; ?>",
    "dismiss": "<?php echo COOKIES_ALRIGHT; ?>"
  }
})});
</script>
  </head>
	<body class='dashboard'>
    <div class='contents' id='main'>
      <div class='hStripe'></div>
      <div class="top_area">
        <div class="absolute_header">
            <div class='user'>
              <?php echo '<a href="'.p::Url('?entry/random').'" class="small ssignore text">'.(new pIcon('fa-random'))." ".RANDOM.'</a> | '; ?>
              <?php echo $this->userBox(); ?> 
              <?php if(!pUser::noGuest()){ echo '<a href="'.p::Url('?auth/login').'">'.MMENU_LOGIN.'</a> '; } ?>
            </div>
            <a class='float-left siteTitle noselect'  href="<?php echo p::Url("?home"); ?>">
              <span style='font-family: koliko;font-size: 15px;'>[/]</span>  
              <?php echo CONFIG_LOGO_TITLE; ?></a> 
           <?php echo (new pMenuTemplate); ?><br id="cl" />  
       </div>
      <div class='outside'>
        <?php echo self::$outside; ?>
      </div>
      <div class="outerwrap"> 
          <div class="ulWrap">
            <noscript>
              <div class='notice danger-notice'><i class='fa fa-warning fa-12'></i> This site needs javascript to function, with javascript turned off, most of the functionality won't work!</div>
            </noscript>

          	<div class='page'>
            		<div class='inner-page'>
              			<?php echo (new p); ?>
            		</div>
         		</div>

          </div>
    	</div>
    </div>
    <div class='absolute_footer'>
    	<img src='<?php echo p::Url('library/images/static/logobw.png'); ?>' />
        <span class='mobilehide'>build 
         <?php 
          $head = file_get_contents(sprintf('.git/refs/heads/%s', 'master'));
          echo "<a href='https://github.com/blekerfeld/donut/commit/$head' class='tooltip'><i class='fa fa-github fa-10'></i>".substr($head, 0, 7)."</a>"; 
        ?> – </span> 
        <span class=''>&copy; 2017 Thomas de Roo </span><br />
    </div>
  </body>
 </html><?php
	}
}