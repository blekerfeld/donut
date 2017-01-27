<!DOCTYPE html>
<html>
  <head>
    <link href="library/assets/css/index.css" rel="stylesheet">
    <link rel="shortcut icon" href="library/images/static/bookmark_icon.png">
    <link rel="stylesheet" href="library/assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" href="library/assets/css/vendors/tooltipster.css">
    <link rel="stylesheet" href="library/assets/css/vendors/tooltipster-noir.css">
    <link rel="stylesheet" href="library/assets/css/vendors/jquery.tagsinput.css">
    <link rel="stylesheet" href="library/assets/css/vendors/jquery.datatables.css">
    <link rel="stylesheet" href="library/assets/css/vendors/jquery.select2.css">
    <link href='http://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
    <script src="library/assets/js/vendors/jquery.js"></script>
    <script src="library/assets/js/vendors/ipakeyboard.js"></script>
    <script src="library/assets/js/vendors/jquery.ddslick.js"></script>
    <script src="library/assets/js/vendors/jquery.tooltip.js"></script>
    <script src="library/assets/js/vendors/jquery.datatables.js"></script>
    <script src="library/assets/js/vendors/jquery.tagsinput.js"></script>
    <script src="library/assets/js/vendors/jquery.select2.js"></script>
    <script src="library/assets/js/vendors/jquery.elastic.js"></script>
    <script>$(document).ready(function(){
        $(".elastic").elastic();
    });
    </script> 
  <?php echo pAjaxLinks($donut['page']['title']); ?>
  <title><?php global $donut; echo $donut['page']['title']; ?></title>
  </head>
  <body class='dashboard'>
    <div class='topstripe'></div>
    <div class='logotop'>

      <img class='logo-center' src='<?php echo pUrl('pol://library/images/static/logo.png') ; ?>' /><?php 
        if($donut['is_beta'] == true){
          echo "<span class='beta'><i class='fa fa-12 fa-flask beta-icon'></i> beta</span>";
        }
      ?><i style="display: none;" id="pageload" class='fa fa-spinner fa-spin'></i>
      <div class='absolute_header'>
       <?php if(pLogged()){ 
          echo "<span style='float:right'>".MMENU_EDITORLANG."<span class='editorlangname'>".pLanguageName(pEditorLanguage($_SESSION['pUser']))."</span> (<a href='".pUrl('?editorlanguage')."'>".MMENU_EDITORLANGCHANGE."</a>)</span>";
          echo MMENU_LOGGEDIN.pUsername($_SESSION['pUser'])." (<a href='".pUrl('?logout')."'>".MMENU_LOGOUT."</a>)";
        }
        else {
          echo"<br />";
          } 
        ?>
      </div>
    </div>
    <div class="outerwrap">
      <div class="ulWrap">
        <noscript>
          <center><div class='notice danger-notice'><i class='fa fa-warning fa-12'></i> This site needs javascript to function, with javascript turned off, most of the functionality won't work!</div></center>
        </noscript>
        <div class='ajaxOutLoad'>
          <div class='nav'>
                <?php echo $donut['page']['menu']; ?>
          </div>
            <?php
                if(!empty($donut['page']['header']))
                  echo "<div class='header'>\n".$donut['page']['header_final']."\n </div>" ;
            ?>
            <div class='page'>
                <div class='ajaxHide'>
                <?php global $donut; echo $donut['page']['content_final']; ?>
                </div>
            </div>
            <?php if(!isset($_REQUEST['wap']))
            { ?>
          </div>
        <div class='footer'>
          <div class='inner_footer'>
            <img src='library/images/static/logobw.png' /> <?php if(!pLogged()){ echo '<a href="'.pUrl('?login').'">'.MMENU_LOGIN.'</a> | '; } ?> <a href='#'>About us</a> | <a href='#'>Terms of use</a> | <a href='#'>Contact</a>  
          </div>  
        </div>
      <?php
      }
      ?>
      </div>
    </div>
    <div class='absolute_footer'>Powered by Donut – &copy; 2017 Thomas de Roo </div>
  </body>
 </html>