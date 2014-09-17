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
 
function exit_page() 
{
    die("<h1>Website Error</h1>
    Something went wrong. Please contact the webmaster of this page if the problem persists.
    <br/>
    <br/>
    <br/>
    <i>CraftedWeb</i>");
}

function RandomString() 
{
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = '';
    for ($p = 0; $p < $length; $p++)
    {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

function convTime($time)
{
    if($time<60)
        $string = 'Seconds';
    elseif ($time > 60)
    {
        $time = $time / 60;
        $string = 'Minutes';
        if ($time > 60)
        {
            $string = 'Hours';
            $time = $time / 60;
            if ($time > 24)
            {
                $string = 'Days';
                $time = $time / 24;
            }
        }
        $time = ceil($time);
    }
    return $time." ".$string;
}