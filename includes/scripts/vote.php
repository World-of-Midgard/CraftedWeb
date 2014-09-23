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

    if (isset($_POST['siteid']))
    {
        $siteid = (int)$_POST['siteid'];
        connect::selectDB('webdb');
        if(website::checkIfVoted($siteid,$GLOBALS['connection']['webdb'])==true)
            die("?p=vote");

        connect::selectDB('webdb');
        $check = $sql->query("SELECT COUNT(*) FROM votingsites WHERE id='".$siteid."'");
        if(!$check)
            die("?p=vote");

        if($GLOBALS['vote']['type'] == 'instant')
        {
            $acct_id = account::getAccountID($_SESSION['cw_user']);
            if(empty($acct_id))
                exit();
            $next_vote = time() + $GLOBALS['vote']['timer'];
            connect::selectDB('webdb');
            $sql->query("INSERT INTO votelog (siteid,userid,timestamp,next_vote,ip)
            VALUES('".$siteid."','".$acct_id."','".time()."','".$next_vote."','".$_SERVER['REMOTE_ADDR']."')");
            $getSiteData = $sql->query("SELECT points,url FROM votingsites WHERE id='".$siteid."'");
            $row = mysqli_fetch_assoc($getSiteData);
            $add = $row['points'] * $GLOBALS['vote']['multiplier'];
            $sql->query("UPDATE account_data SET vp=vp + ".$add." WHERE id=".$acct_id);
            echo $row['url'];
        }
        elseif($GLOBALS['vote']['type'] == 'confirm')
        {
            connect::selectDB('webdb');
            $getSiteData = $sql->query("SELECT points,url FROM votingsites WHERE id='".(int)$_POST['siteid']."'");
            $row = mysqli_fetch_assoc($getSiteData);
            $_SESSION['votingUrlID']=(int)$_POST['siteid'];
            echo $row['url'];
        }
        else
            die("Error!");
    }