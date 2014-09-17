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

    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
    require('../ext_scripts_class_loader.php');
    if (isset($_POST['item_entry']))
    {
        $entry = addslashes($_POST['item_entry']);
        $character_realm = addslashes($_POST['character_realm']);
        $type = addslashes($_POST['send_mode']);
        if (empty($entry) || empty($character_realm) || empty($type))
        echo '<b class="red_text">Please specify a character.</b>';
        else
        {
            connect::selectDB('webdb');
            $realm = explode("*", $character_realm);
            $result = $sql->query("SELECT price FROM shopitems WHERE entry='".$entry."'");
            $row = mysqli_fetch_assoc($result);
            $account_id = account::getAccountIDFromCharId($realm[0],$realm[1]);
            $account_name = account::getAccountName($account_id);

            if ($type == 'vote')
            {
                if (account::hasVP($account_name,$row['price']) == false)
                    die('<b class="red_text">You do not have enough Vote Points</b>');
                account::deductVP($account_id,$row['price']);

            }
            elseif ($type == 'donate')
            {
                if (account::hasDP($account_name,$row['price']) == false)
                    die('<b class="red_text">You do not have enough '.$GLOBALS['donation']['coins_name'].'</b>');
                account::deductDP($account_id,$row['price']);
            }
            $shop = new shop;
            $shop->logItem($type,$entry,$realm[0],$account_id,$realm[1],1);
            $result = $sql->query("SELECT * FROM realms WHERE id='".$realm[1]."'");
            $row = mysqli_fetch_assoc($result);

            if($row['sendType'] == 'ra')
            {
                require('../misc/ra.php');
                require('../classes/character.php');
                sendRa("send items ".character::getCharname($account_id, $realm[0])." \"Your requested item\" \"Thanks for supporting us!\" ".$entry." ",
                $row['rank_user'],$row['rank_pass'],$row['host'],$row['ra_port']);
            }
            elseif($row['sendType'] == 'soap')
            {
                require('../misc/soap.php');
                require('../classes/character.php');
                sendSoap("send items ".character::getCharname($account_id, $realm[0])." \"Your requested item\" \"Thanks for supporting us!\" ".$entry." ",
                $row['rank_user'],$row['rank_pass'],$row['host'],$row['soap_port']);
            }
        }
    }