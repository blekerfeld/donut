<?php

  //  Donut         🍩 
  //  Dictionary Toolkit
  //    Version a.1
  //    Written by Emma de Roo
  //    Licensed under MIT

  //  ++  File: manifest.json.php

header('Content-Type: application/manifest+json');
require 'config.php';
?>
{
  "name": "<?php echo CONFIG_SITE_TITLE; ?>",
  "short_name": "",
  "start_url": ".",
  "display": "standalone",
  "background_color": "#e1e1e1",
  "description": "<?php echo CONFIG_SITE_DESC; ?>",
  "icons": [{
    "src": "<?php echo p::Url("pol://library/images/static/app_icon.png"); ?>",
    "sizes": "210x210",
    "type": "image/png"
  }], 
  "orientation": "portrait-primary"
}
