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
define('INIT_SITE', true);
include('../../includes/misc/headers.php');
include('../../includes/configuration.php');
include('../functions.php');

if(isset($_POST['test'])) 
{
    $errors = array();
    if(!$sql)
        $errors[] = "mySQL connection error. Please check your settings.";
    else
    {
        if(!$sql->select_db($GLOBALS['connection']['webdb']))
            $errors[] = "Database error. Could not connect to the website database.";
        if(!$sql->select_db($GLOBALS['connection']['logondb']))
            $errors[] = "Database error. Could not connect to the logon database.";
        if(!$sql->select_db($GLOBALS['connection']['worlddb']))
            $errors[] = "Database error. Could not connect to the world database.";
    }
    if (!empty($errors))
    {
        foreach($errors as $error)
        {
        echo  "<strong>*", $error ,"</strong><br/>";
        }
    }
    else
        echo "No errors occured. Settings are correct.";
}