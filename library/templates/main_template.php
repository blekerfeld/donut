<!DOCTYPE html>
<html>
  <head>
    <link href="library/assets/css/index.css" rel="stylesheet">
    <?php echo (pCheckMobile() ? '<link href="library/assets/css/mobile.css" rel="stylesheet">' : '' ); ?>
    <link rel="shortcut icon" href="library/images/static/donutico.png">
    <link rel="stylesheet" href="library/assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" href="library/assets/css/vendors/tooltipster.css">
    <link rel="stylesheet" href="library/assets/css/vendors/tooltipster-noir.css">
    <link rel="stylesheet" href="library/assets/css/vendors/jquery.tagsinput.css">
    <link rel="stylesheet" href="library/assets/css/vendors/jquery.datatables.css">
    <link rel="stylesheet" href="library/assets/css/vendors/jquery.select2.css">
    <link href='http://fonts.googleapis.com/css?family=Slabo+27px' rel='stylesheet' type='text/css'>
    <script src="library/assets/js/vendors/jquery.js"></script>
    <script src="library/assets/js/vendors/jquery.ddslick.js"></script>
    <script src="library/assets/js/vendors/jquery.tooltip.js"></script>
    <script src="library/assets/js/vendors/jquery.datatables.js"></script>
    <script src="library/assets/js/vendors/jquery.playsound.js"></script>
    <script src="library/assets/js/vendors/jquery.pulsate.js"></script>
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
    <div class="absolute_header">
      <?php echo pAbsHeader(); ?>
    </div>
    <div class="outerwrap">
      <div class="fill_gaps">
        <div class="ulWrap">
          <noscript>
            <center><div class='notice danger-notice'><i class='fa fa-warning fa-12'></i> This site needs javascript to function, with javascript turned off, most of the functionality won't work!</div></center>
          </noscript>
          <div class='ajaxOutLoad'>
            <div class='nav <?php echo (isset($_REQUEST['home']) ? 'home' : '');?>'>
                  <?php echo $donut['page']['menu']; ?>
            </div>
              <?php
                  if(!empty($donut['page']['header']))
                    echo "<div class='header'>\n".$donut['page']['header_final']."\n </div>" ;
                  echo $donut['page']['outofinner'];
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
              <img src='library/images/static/logobw.png' /> <?php if(!pLogged()){ echo '<a href="'.pUrl('?login').'">'.MMENU_LOGIN.'</a> | '; } ?> <a href=''>About us</a> | <a href=''>Terms of use</a> | <a href=''>Contact</a>  
            </div>  
          </div>
        <?php
        }
        ?>
        </div>
      </div>
    </div>
    <div class='absolute_footer'><a target="_blank" href="https://github.com/blekerfeld/donut" class='tooltip'><i class='fa fa-github fa-10'></i>/blekerfeld/donut</a> – &copy; 2017 Thomas de Roo</div>
  </body>
 </html>