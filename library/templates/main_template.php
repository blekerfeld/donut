<!DOCTYPE html>
<html>
  <head>
    <link href="<?php echo pUrl('library/assets/css/main.css'); ?>" rel="stylesheet">
    <link href="<?php echo pUrl('library/assets/css/markdown.css'); ?>" rel="stylesheet">
    <?php echo (pCheckMobile() ? '<link href="library/assets/css/mobile.css" rel="stylesheet">' : '' ); ?>
    <link rel="shortcut icon" href="<?php echo pUrl('library/images/static/favicon.png'); ?>">
    <link rel="stylesheet" href="<?php echo pUrl('library/assets/css/vendors/font-awesome.css'); ?>">
    <link rel="stylesheet" href="<?php echo pUrl('library/assets/css/vendors/materialdesignicons.css'); ?>">
    <link rel="stylesheet" href="<?php echo pUrl('library/assets/css/vendors/tooltipster.css'); ?>">
    <link rel="stylesheet" href="<?php echo pUrl('library/assets/css/vendors/jquery.tagsinput.css'); ?>">
    <link rel="stylesheet" href="<?php echo pUrl('library/assets/css/vendors/pace.css'); ?>">
    <link rel="stylesheet" href="<?php echo pUrl('library/assets/css/vendors/jquery.select2.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <link href='http://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.pace.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.tooltipster.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.playsound.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.typewatch.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.pulsate.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.tagsinput.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.select2.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.elastic.js'); ?>"></script>
    <script src="<?php echo pUrl('library/assets/js/index.js"'); ?>></script>
  <title><?php global $donut;  echo $donut['page']['title']; ?></title>
  <?php global $donut;  echo $donut['page']['head']['final']; ?>
  </head>
  <body class='dashboard'>
    <div class='hStripe'></div>
    <div class="top_area">
      <div class="absolute_header">
        <div class="logotop">
            <?php echo pAbsHeader(); ?>
            <?php if(!pLogged()){ echo '<a href="'.pUrl('?login').'">'.MMENU_LOGIN.'</a>'; } ?>
        </div>
        <a class="logotop" href="<?php echo pUrl("?home"); ?>">
        <?php echo pMarkDownParse(CONFIG_LOGO_TITLE); ?></a> 
      </div>
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
          <img src='<?php echo pUrl('library/images/static/logobw.png'); ?>' />
        <span class='xxsmall'>donut public alpha, version ɑ.1 build
         <?php 
          $head = file_get_contents(sprintf('.git/refs/heads/%s', 'master'));
          echo "<a href='https://github.com/blekerfeld/donut/commit/$head' class='tooltip'><i class='fa fa-github fa-10'></i>".substr($head, 0, 7)."</a>"; 
        ?></span> <br/ >
        <span class='xxxsmall'>&copy; 2017 Thomas de Roo </span><br />

    </div>

    <script src="<?php echo pUrl('library/assets/js/vendors/jquery.smoothstate.js'); ?>"></script>
      <script src="<?php echo pUrl('library/assets/js/smoothstate.js'); ?>"></script>  
  </body>

 </html>