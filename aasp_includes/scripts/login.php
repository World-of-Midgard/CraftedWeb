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
include('../../includes/configuration.php');;
include('../functions.php');

if(isset($_POST['login'])) 
{
	 $username = addslashes(strtoupper(trim($_POST['username'])));
	 $password = addslashes(strtoupper(trim($_POST['password'])));
	 if(empty($username) || empty($password))
		 die("Please enter both fields.");
	 
		 $password = sha1("".$username.":".$password."");
		 $sql->select_db($GLOBALS['connection']['logondb']) or die("couldnt select db");
		 
		 $result = $sql->query("SELECT * FROM account WHERE username='".$username."' AND
		 sha_pass_hash='".$password."'");
		 if(mysqli_num_rows($result) == 0)
			 die("Invalid username/password combination.");
		 
		 $getId = $sql->query("SELECT id FROM account WHERE username='".$username."'");
		 $row = mysqli_fetch_assoc($getId);
		 $uid = $row['id'];
		 $result = $sql->query("SELECT gmlevel FROM account_access WHERE id='".$uid."'
		 AND gmlevel >= '".$GLOBALS[$_POST['panel'].'Panel_minlvl']."'");
			 
		 $rank = mysqli_fetch_assoc($result);
		 
		 $_SESSION['cw_'.$_POST['panel']] = ucfirst(strtolower($username));
		 $_SESSION['cw_'.$_POST['panel'].'_id'] = $uid;
		 $_SESSION['cw_'.$_POST['panel'].'_level'] = $rank['gmlevel'];
		 
		 if(empty($_SESSION['cw_'.$_POST['panel']]) || empty($_SESSION['cw_'.$_POST['panel'].'_id'])
		 || empty($_SESSION['cw_'.$_POST['panel'].'_level']))
		 	die('The scripts encountered an error. (1 or more Sessions was set to NULL)');
		 
		 sleep(1);
		 die(true);
  }