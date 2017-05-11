<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: MainTemplate.cset.php

class pMainTemplate extends pTemplate{

	protected $_stylesheets, $_scripts;

	public static $title = CONFIG_SITE_TITLE;

	public static function setTitle($title){
		self::$title = $title . ' - ' . CONFIG_SITE_TITLE;
	}

	protected function loadCSS($stylesheet){
		$this->_stylesheets[] = "<link rel='stylesheet' href='".p::Url('library/assets/css/'.$stylesheet)."'>\n";
	}

	protected function loadJavascript($url){
		$this->_scripts[] = "<script type='text/javascript' src='".p::Url('library/assets/js/'.$url)."'></script>\n";

	}

	protected function userBox(){
		return MMENU_LOGGEDIN.(pUser::read('longname') != '' ? pUser::read('longname') : pUser::read('username'))." (<a href='".p::Url('?auth/logout')."'>".MMENU_LOGOUT."</a>) – <em>".MMENU_EDITORLANG."<span class='editorlangname'>".(new pLanguage(pUser::read('editor_lang')))->read('name')."</span> (<a href='".p::Url('?editorlanguage')."'>".MMENU_EDITORLANGCHANGE."</a>)</em>";
	}

	public function __construct(){
		foreach (p::$assets['css'] as $css)
			$this->loadCSS($css);
		foreach (p::$assets['javascript'] as $js)
			$this->loadJavascript($js);
	}
	

	public function render(){
		if(isset(pAdress::arg()['ajax']))
			return die(new p);
		?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo self::$title; ?></title>
    <link rel="shortcut icon" href="<?php echo p::Url('library/images/static/app_icon.png'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
		<?php
		echo implode("\n", $this->_stylesheets);
		echo implode("\n", $this->_scripts);
		?>
	<body class='dashboard'>
    <div class='hStripe'></div>
    <div class="top_area">
      <div class="absolute_header">
        <div class="logotop">
            <div class='user'>
            <?php echo $this->userBox(); ?>
            </div>
            <?php if(!pUser::noGuest()){ echo '<a href="'.p::Url('?auth/login').'">'.MMENU_LOGIN.'</a>'; } ?>
        </div>
        <a class="logotop" href="<?php echo p::Url("?home"); ?>">
        <?php echo p::Markdown(CONFIG_LOGO_TITLE, false); ?></a> 
      </div>
     </div>
    <div class="outerwrap">
        <div class="ulWrap">
          <noscript>
            <div class='notice danger-notice'><i class='fa fa-warning fa-12'></i> This site needs javascript to function, with javascript turned off, most of the functionality won't work!</div>
          </noscript>
          <div class='ajaxOutLoad' id='main'>
          	<?php echo (new pMenuTemplate); ?>
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
        <span class='xxsmall mobilehide'>donut public alpha, version ɑ.1 build
         <?php 
          $head = file_get_contents(sprintf('.git/refs/heads/%s', 'master'));
          echo "<a href='https://github.com/blekerfeld/donut/commit/$head' class='tooltip'><i class='fa fa-github fa-10'></i>".substr($head, 0, 7)."</a>"; 
        ?>
        <br/ ></span> 
        <span class='xxxsmall'>&copy; 2017 Thomas de Roo </span><br />
    </div>
  </body>
 </html><?php
	}
}