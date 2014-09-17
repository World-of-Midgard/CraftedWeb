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

    require('includes/misc/headers.php'); //Load sessions, erorr reporting & ob.
    if(file_exists('install/index.php'))
    {
        header("Location: install/index.php");
        exit;
    }

    define('INIT_SITE', true);
    require('includes/configuration.php'); //Load configuration file
    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
    if(isset($GLOBALS['not_installed']) && $GLOBALS['not_installed'] == true)
    {
        if(file_exists('install/index.php'))
        {
            header("Location: install/index.php");
            exit;
        }
        exit('<b>Error</b>. It seems like your website is not yet installed, but no installer could be found!');
    }

    if($GLOBALS['maintainance'] == true && !in_array($_SERVER['REMOTE_ADDR']  ,$GLOBALS['maintainance_allowIPs']))
    {
        die("<center><h3>Website Maintenance</h3><BR />
        We are currently undergoing some maintenance and will return shortly.
        <br/><br/>Sincerely
        </center>");
    }

    require('includes/misc/connect.php');
    require('includes/misc/func_lib.php');
    require('includes/misc/compress.php');
    require('includes/classes/account.php');
    require('includes/classes/server.php');
    require('includes/classes/website.php');
    require('includes/classes/shop.php');
    require('includes/classes/character.php');
    require('includes/classes/cache.php');
    require('includes/classes/plugins.php');
    plugins::globalInit();
    plugins::init('classes');
    plugins::init('javascript');
    plugins::init('modules');
    plugins::init('styles');
    plugins::init('pages');

    if($GLOBALS['enablePlugins'] == true)
    {
        if($_SESSION['loaded_plugins'] != NULL)
        {
            foreach($_SESSION['loaded_plugins'] as $folderName)
            {
                if(file_exists('plugins/'.$folderName.'/config.php'))
                include_once('plugins/'.$folderName.'/config.php');
            }
        }
    }
    $account = new account;
    $account->getRemember();
    if (!isset($_GET['p']))
        $_GET['p'] = 'home';

    if(isset($_SESSION['votingUrlID']) && $_SESSION['votingUrlID']!=0 && $GLOBALS['vote']['type']=='confirm')
    {
        if(website::checkIfVoted((int)$_SESSION['votingUrlID'],$GLOBALS['connection']['webdb']) == true)
            die("?p=vote");
        $acct_id = account::getAccountID($_SESSION['cw_user']);
        $next_vote = time() + $GLOBALS['vote']['timer'];
        connect::selectDB('webdb');
        $sql->query("INSERT INTO votelog VALUES('','".(int)$_SESSION['votingUrlID']."',
        '".$acct_id."','".time()."','".$next_vote."','".$_SERVER['REMOTE_ADDR']."')");
        $getSiteData = $sql->query("SELECT points,url FROM votingsites WHERE id='".(int)$_SESSION['votingUrlID']."'");
        $row = mysqli_fetch_assoc($getSiteData);

        if(mysqli_num_rows($getSiteData) == 0)
        {
            header('Location: index.php');
            unset($_SESSION['votingUrlID']);
            exit;
        }

        $add = $row['points'] * $GLOBALS['vote']['multiplier'];
        $sql->query("UPDATE account_data SET vp=vp + ".$add." WHERE id=".$acct_id);
        unset($_SESSION['votingUrlID']);
        header("Location: ?p=vote");
        exit;
    }

    if(!isset($_SESSION['last_ip']) && isset($_SESSION['cw_user']))
        $_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
    elseif(isset($_SESSION['last_ip']) && isset($_SESSION['cw_user']))
    {
        if($_SESSION['last_ip']!=$_SERVER['REMOTE_ADDR'])
        {
            header("Location: ?p=logout");
            exit;
        }
        $_SESSION['last_ip']=$_SERVER['REMOTE_ADDR'];
    }