<?php

	header('Content-Type: image/png');

	$count = 0;
	$dest= imagecreatetruecolor(40, 16);


	$black = imagecolorallocate($dest, 0, 0, 0);

	// Make the background transparent
	imagecolortransparent($dest, $black);


	$image = imagecreatefrompng("library/images/flags/" . $_GET['flag_1'] . ".png");
    imagecopymerge($dest, $image, (2), 3, 0, 0, imagesx($image), imagesy($image), 100);

    $image = imagecreatefrompng("library/images/flags/" . $_GET['flag_2'] . ".png");
    imagecopymerge($dest, $image, (21), 3, 0, 0, imagesx($image), imagesy($image), 100);


	imagepng($dest);


	die();

?>
