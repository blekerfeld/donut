<?php

// We need to include 

include 'config.php';
p::initialize();

p::Out("HOI");

(new pMainTemplate)->render();