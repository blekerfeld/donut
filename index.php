<?php 	
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: index.php

// Doing our thing and kill the page afterwards...
(require 'Configuration.php')->dispatch()->render()->die();
