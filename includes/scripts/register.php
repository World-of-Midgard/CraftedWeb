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

    require('../ext_scripts_class_loader.php');
    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
    connect::selectDB('logondb');
    if (isset($_POST['register']))
    {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $repeat_password = trim($_POST['password_repeat']);
        $captcha = (int)$_POST['captcha'];
        $raf = $_POST['raf'];
        $account->register($username,$email,$password,$repeat_password,$captcha,$raf);
        echo true;
    }

    if(isset($_POST['check']))
    {
        if($_POST['check'] == "username")
        {
            $username = addslashes($_POST['value']);
            $result = $sql->query("SELECT * FROM account WHERE username='".$username."'");
            if(!$result)
            echo "<i class='green_text'>This username is available</i>";
            else
            echo "<i class='red_text'>This username is not available</i>";
        }
    }