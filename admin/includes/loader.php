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

    require('../includes/misc/headers.php'); //Load headers

    define('INIT_SITE', true);
    include('../includes/configuration.php');

    if($GLOBALS['adminPanel_enable']==false)
        exit();

    require('../includes/misc/compress.php'); //Load compression file
    include('../aasp_includes/functions.php');

    $server = new server;
    $account = new account;
    $page = new page;
    $server->connect();

    if(isset($_SESSION['cw_admin']) && !isset($_SESSION['cw_admin_id']) && $_GET['p']!='notice')
    {
        header("Location: ?p=notice&e=It seems like a session was not created! You were logged out to prevent any threat against the site.");
        exit;
    }