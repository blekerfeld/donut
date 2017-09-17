<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      MainTemplate.class.php

class pMainTemplate extends pTemplate{

	protected $_stylesheets, $_scripts;

	public static $title = CONFIG_SITE_TITLE, $orgTitle = CONFIG_SITE_TITLE, $_searchBoxShown = false, $outside, $no_border = false;

	public static function setTitle($title){
		self::$title = $title . ' - ' . CONFIG_SITE_TITLE;
	}

  public static function setNoBorder(){
    self::$no_border = true;
  }

  public static function throwOutsidePage($content){
    self::$outside .= "\n".$content;
  }

	protected function loadCSS($stylesheet){
		$this->_stylesheets[] = "<link rel='stylesheet' href='".p::Url('library/assets/css/'.$stylesheet)."'>";
	}

  protected function pageWidthCSS(){
    $hashKey = spl_object_hash($this);
    // Throwing this object's script into a session
    pRegister::session($hashKey, "
      .absolute_header{
        padding-right: calc(".CONFIG_PAGE_MARGIN."% + 40px);
        padding-left: calc(".CONFIG_PAGE_MARGIN."% + 40px);
      }

      .header.dictionary.home-search{
        margin-right: calc(".CONFIG_PAGE_MARGIN."% + 45px);
      }

      .header{
        padding-right: calc(".CONFIG_PAGE_MARGIN."% + 40px);
        padding-left: calc(".CONFIG_PAGE_MARGIN."% + 40px);
      }
      .outerwrap{
        margin-left: calc(".CONFIG_PAGE_MARGIN."% + 40px);
        margin-right: calc(".CONFIG_PAGE_MARGIN."% + 40px);
      }
      .absolute_footer{
        margin-right: calc(".CONFIG_PAGE_MARGIN."% + 40px);
        margin-left: calc(".CONFIG_PAGE_MARGIN."% + 40px);
      }
    ");
    return "<link rel='stylesheet' href='".p::Url('pol://library/assets/css/key.css.php?key='.$hashKey)."'>";
  }

  public static function loadDots($class = 'center'){
    return "<div class='$class'><p class='dots'><span>.</span><span>.</span><span>.</span></p></div>";
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
    // if(empty(p::$Out))
    //   p::Url('?', true);
		if(isset(pRegister::arg()['ajax']) OR isset(pRegister::arg()['ajaxLoad']))
			return die(new Donut);
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
              <span style='font-family: koliko;font-size: 15px;'><?php echo CONFIG_LOGO_SYMBOL; ?></span>  
              <?php echo CONFIG_LOGO_TITLE; ?></a> 
           <?php echo (new pMenuTemplate); ?><br id="cl" />  
       </div>
     </div>
      <div class='outside'>
        <?php echo self::$outside; ?>
      </div>
      <div class=<?php echo "'".(self::$no_border ? 'no-border' : '')." outerwrap'"; ?>> 
          <div class="ulWrap">
            <noscript>
              <div class='notice danger-notice'><i class='fa fa-warning fa-12'></i> This site needs javascript to function, with javascript turned off, most of the functionality won't work!</div>
            </noscript>

          	<div class='page'>
            		<div class='inner-page'>
              			<?php echo (new Donut); ?>
            		</div>
         		</div>

          </div>
    	</div>
    </div>
    </div>
    <div class='absolute_footer'>
      
      <img src='<?php echo p::Url('library/images/static/logobw.png'); ?>' />
      <span class='mobilehide'> 
        <span class='float-left'>
         
         <?php 
          $head = file_get_contents(sprintf('.git/refs/heads/%s', 'master'));
          echo "<a href='https://github.com/blekerfeld/donut/commit/$head' class='tooltip'><i class='fa fa-github fa-10'></i> /donut</a> 0.11-dev"; 
        ?><br />&copy; 2017 Thomas de Roo</span><span class='float-right'>
         </span></span><br />
    </div>
  </body>
 </html><?php
	}
}