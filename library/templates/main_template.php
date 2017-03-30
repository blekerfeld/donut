<!DOCTYPE html>
<html>
  <head>
    <link href="library/assets/css/index.css" rel="stylesheet">
    <?php echo (pCheckMobile() ? '<link href="library/assets/css/mobile.css" rel="stylesheet">' : '' ); ?>
    <link rel="shortcut icon" href="library/images/static/bookmark_icon.png">
    <link rel="stylesheet" href="library/assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" href="library/assets/css/vendors/tooltipster.css">
    <link rel="stylesheet" href="library/assets/css/vendors/jquery.tagsinput.css">
    <link rel="stylesheet" href="library/assets/css/vendors/pace.css">
    <link rel="stylesheet" href="library/assets/css/vendors/jquery.select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script src="library/assets/js/vendors/jquery.js"></script>
    <script src="library/assets/js/vendors/pace.min.js"></script>
    <script src="library/assets/js/vendors/jquery.tooltip.js"></script>
    <script src="library/assets/js/vendors/jquery.playsound.js"></script>
    <script src="library/assets/js/vendors/jquery.pulsate.js"></script>
    <script src="library/assets/js/vendors/jquery.tagsinput.js"></script>
    <script src="library/assets/js/vendors/jquery.select2.js"></script>
    <script src="library/assets/js/vendors/jquery.elastic.js"></script>
    <script src="library/assets/js/index.js"></script>
  <title><?php global $donut;  echo $donut['page']['title']; ?></title>
  <?php global $donut;  echo $donut['page']['head']['final']; ?>
  </head>
  <body class='dashboard'>
    <div class="absolute_header">
      <div class="logotop">
          <?php echo pAbsHeader(); ?>
          <?php if(!pLogged()){ echo '<a href="'.pUrl('?login').'">'.MMENU_LOGIN.'</a>'; } ?>
      </div>
      <a class="logotop" href="<?php echo pUrl("?home"); ?>">
      <img src='library/images/static/donut_white.png' /> 
      <?php echo pMarkDownParse(CONFIG_LOGO_TITLE); ?></a> 
    </div>
    <div class="outerwrap">
      <div class="fill_gaps">
        <div class="ulWrap">
          <noscript>
            <div class='notice danger-notice'><i class='fa fa-warning fa-12'></i> This site needs javascript to function, with javascript turned off, most of the functionality won't work!</div>
          </noscript>
          <div class='ajaxOutLoad' id='main'>
            <div class='nav <?php echo (isset($_REQUEST['home']) ? 'home' : '');?>'>
                  <?php echo $donut['page']['menu']; ?>
            </div>
              <?php
                  if(!empty($donut['page']['header']))
                    echo "<div class='header'>\n".$donut['page']['header_final']."\n </div>" ;
                  echo $donut['page']['outofinner'];
              ?>
              <div class='page'>
                <div class='inner-page'>
                  <?php global $donut; echo $donut['page']['content_final']; ?>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class='absolute_footer'>
      <img src='library/images/static/logobw.png' /> 
    <a target="_blank" href="https://github.com/blekerfeld/donut" class='tooltip'><i class='fa fa-github fa-10'></i>/blekerfeld/donut</a> – &copy; 2017 Thomas de Roo</div>

    <script src="library/assets/js/vendors/jquery.smoothstate.js"></script>
      <script src="library/assets/js/smoothstate.js"></script>  
  </body>

 </html>