<?php
/*
           ___           __ _           _ __    __     _
          / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__
         / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \
        / /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
        \____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/
                          --[ Build 1.5 ]--
                    - coded and revised by Faded -

    CraftedWeb is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This is distributed in the hope that it will be useful, but
    WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    included license for more details.

    Support/FAQ #EmuDevs - http://emudevs.com
*/
 
    session_start();
    header('Content-type: image/jpeg');
	$font_size = 20;
	$img_width = 100;
	$img_height = 40;
	$image = imagecreate($img_width,$img_height);
	imagecolorallocate($image, 255, 255, 255);
	$text_color = imagecolorallocate($image, 0, 0, 0);
	for($x=1; $x<=30; $x++) 
	{
		$x1 = rand(1,100);
		$y1 = rand(1,100);
		$x2 = rand(1,100);
		$y2 = rand(1,100);
		imageline($image, $x1, $y1, $x2, $x2, $text_color);
	}
	imagettftext($image, $font_size, 0, 15, 30, $text_color, 'arial.ttf', $_SESSION['captcha_numero']);
	imagejpeg($image);