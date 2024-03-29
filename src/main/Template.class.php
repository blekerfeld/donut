<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: Template.class.php

class pTemplate{

	protected $_stylesheets, $_scripts;

	public static $title = CONFIG_SITE_TITLE, $orgTitle = CONFIG_SITE_TITLE, $_searchBoxShown = false, $outside, $no_border = false, $has_tabs = false, $_search = true;

	public static function setTitle($title){
		self::$title = $title . ' - ' . CONFIG_SITE_TITLE;
	}

  public static function setNoBorder(){
    self::$no_border = true;
  }

  public static function setBorder(){
      self::$no_border = false;
  }

  public static function setTabbed(){
    self::$has_tabs = true;
  }

  public static function disableSearch(){
    self::$_search = false;
  }

  public static function throwOutsidePage($content){
    self::$outside .= "\n".$content;
  }

	protected function loadCSS($stylesheet){
		$this->_stylesheets[] = "<link rel='stylesheet' href='".p::Url('library/assets/css/'.$stylesheet)."'>";
	}

  protected function pageWidthCSS(){
    $hashKey = sha1(spl_object_hash($this));
    // Throwing this object's script into a session
    pRegister::session($hashKey, "
      .absolute_header, div.landing-content, div.ultimate_header, .absolute_footer{
        padding-right: calc(".CONFIG_PAGE_MARGIN." + 20px);
        padding-left: calc(".CONFIG_PAGE_MARGIN." + 20px);
      }

      .header{
        padding-right: calc(".CONFIG_PAGE_MARGIN.");
        padding-left: calc(".CONFIG_PAGE_MARGIN.");
      }

      .holder{
        padding-right:calc(".CONFIG_PAGE_MARGIN." + 20px);
        padding-left: calc(".CONFIG_PAGE_MARGIN." + 20px);
      }

      div.header.dictionary{
        padding: calc(".CONFIG_PAGE_MARGIN." +  20px);
        margin-top: 40px;
      }

      div.hStripe {
         border-top: 0px solid ".CONFIG_ACCENT_COLOR_1."
         border-bottom: 3px solid #e6e6e6;
      }

      a{
          color: ".CONFIG_ACCENT_COLOR_1."
      }

      div.nav a.active{
          color: ".CONFIG_ACCENT_COLOR_1."
          border-bottom: 3px solid ".CONFIG_ACCENT_COLOR_1.";
      }

      .header.dictionary.home .hSearch{
        ".CONFIG_HEADER_CSS_HSEARCH.";
      }

    ");
    return "<link rel='stylesheet' href='".p::Url('pol://library/assets/css/key.css.php?key='.$hashKey)."'>";
  }

  public static function loadDots($class = 'center'){
    $dot = ".";
    return "<div class='$class'><p class='dots'><span>".$dot."</span><span>".$dot."</span><span>".$dot."</span></p></div>";
  }

	protected function loadJavascript($url){
		$this->_scripts[] = "<script type='text/javascript' src='".p::Url('library/assets/js/'.$url)."'></script>";
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
     self::$_searchBoxShown = !self::$_searchBoxShown;
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
	

  public function login(){

    return "<a href='javascript:void();' class='small blue ttip_login' title='<div class=\"tooltipster-inner\"><div class=\"loadLogin\"><p class=\"dots\"><span>.</span><span>.</span><span>.</span></p></div></div>'>".MMENU_LOGINSU."</a><script type='text/javascript'>
        $('.ttip_login').tooltipster({animation: 'grow', animationDuration: 100,  distance: 0, contentAsHTML: true, interactive: true, side:'bottom', trigger: 'click', functionReady: function(){
            $('.loadLogin').load('".p::Url('?auth/login/ajaxLoad')."');
        }});
    </script>";

  }

	public function render(){
    if(isset(pRegister::arg()['ajaxLoad']))
      echo "<script type='text/javascript'>document.title = '".self::$title."';</script>";
		if(isset(pRegister::arg()['ajax']) OR isset(pRegister::arg()['ajaxLoad']))
			return die(new pMain);
		?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo self::$title; ?></title>
    <link rel="shortcut icon" href="<?php echo p::Url('library/staticimages/faveicon.png'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
		<?php
		echo implode("", $this->_stylesheets);
		echo implode("", $this->_scripts);
    echo $this->pageWidthCSS();   
		?>
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
    meSpeak.loadConfig(<?php echo '"'.p::Url("donut://library/assets/js/vendors/meSpeak_config.json").'"';?>);
    meSpeak.loadVoice(<?php echo '"'.p::Url("donut://library/assets/js/vendors/en.json").'"';?>);
    </script>
  </head>
	<body class='dashboard'>
    <div class='contents' id='main'>
      <div class="top_area">
        <div class="absolute_header <?php  echo "app_".pRegister::app(); ?>">
  
            <div class='inner'>
            <div class='user'>
              <?php echo '<a href="'.p::Url('?entry/random').'" class="small ssignore text">'.(new pIcon('fa-random'))." ".RANDOM.'</a> | '; ?>
              <?php echo $this->userBox(); 	 ?> 
              <?php if(!pUser::noGuest()){ echo $this->login(); } ?>
            </div>
            <div class='siteTitle'><a class='siteTitle ssignore noselect'  href="<?php echo p::Url("?home"); ?>">
              <span><?php echo (new pIcon(CONFIG_LOGO_SYMBOL))." ".htmlspecialchars(CONFIG_LOGO_TITLE); ?></span></a></div>
              
           <?php echo ((CONFIG_MENU_BREAK == 1) ? '<br /><br />' : '').(new pMenuView); ?>
           </div>
       </div>
     </div>
      <div class='outside'>
      <?php 
          if(self::$_search != false){
            // The home search box! Only if needed!
            $searchBox = new pSearchBox(true);
            $searchBox->toggleNoBackSpace(true);
        
            if(isset(pRegister::arg()['is:preview'], pRegister::arg()['action']) && pRegister::arg()['action'] == 'proper')
              p::Out('<div class="float-right"><a class="preview-close hide tooltip">'.(new pIcon('fa-times-circle', 12)).'</a><br id="cl" /></div>');
        
            if(isset(pRegister::arg()['is:result'], pRegister::freshSession()['searchQuery']))
              $searchBox->setValue(pRegister::freshSession()['searchQuery']);

            if(isset(pRegister::arg()['is:result'], pRegister::freshSession()['searchQuery']))
              $searchBox->setValue(pRegister::freshSession()['searchQuery']);

            if(!isset(pRegister::arg()['ajax'], pRegister::arg()['ajaxLoad']))
              echo $searchBox."<br/>";
          }
                
        ?>
        <?php echo self::$outside; ?>
      </div>
      <div class='holder'>
      <div class=<?php echo "'".(self::$no_border ? 'no-border no-border-h no-background' : '')." ".(self::$has_tabs ? 'tabbed' : '')." "."outerwrap'"; ?>> 
          <div class="ulWrap">
            <noscript>
              <div class='notice danger-notice'><i class='fa fa-exclamation-triangle fa-12'></i> This site needs javascript to function, with javascript turned off, most of the functionality won't work!</div>
            </noscript>

          	<div class='page'>
            		<div class='inner-page'>
              			<?php echo new pMain; ?>
            		</div>
         		</div>

          </div>
    	</div>
    </div>
    </div>
    </div>
    <div class='absolute_footer'>

      <span class='mobilehide'> 
        <span class='float-left'>
         <?php 
          $head = file_get_contents(sprintf('.git/refs/heads/%s', 'master'));
          echo "<a href='https://github.com/blekerfeld/donut/commit/$head' class='tooltip'><i class='fab fa-github fa-10'></i> /donut</a> 0.13-dev</a>"; 
        ?> / Emma de Roo</span><span class='float-right'>
         </span></span><br />
    </div>
  </body>
 </html><?php
	 return $this;
  }

  public function quit($input = null){
    // To make it possible to die on a chained instance of the template...
    return die($input);
  }
}