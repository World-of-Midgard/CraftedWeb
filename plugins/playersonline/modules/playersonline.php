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
    connect::selectDB('webdb');
    if (!isset($GLOBALS['playersOnline']))
        return;

    $result = $sql->query("SELECT id,name FROM realms WHERE id='".$GLOBALS['playersOnline']['realm_id']."'");
    $row = mysqli_fetch_assoc($result);
    $rid = $row['id'];
    $realmname = $row['name'];
    connect::connectToRealmDB($rid);

    $count = $sql->query("SELECT COUNT(*) FROM characters WHERE name!='' AND online=1");
    echo '<div class="box_one"><div class="box_one_title">Online Players - '.$realmname.'</div>';

    if(!$count)
        echo '<b>No players are online right now!</b>';
    else
    {
        echo '<table width="100%">
        <tr>
        <th>Name</th>
        <th>Guild</th>
        <th>Hks</th>
        <th>Level</th>
        </tr>';
        if($GLOBALS['playersOnline']['moduleResults']>0)
        {
            $count = mysqli_fetch_assoc($count);
            if($count > 10)
                $count = $count - 10;

            $rand = rand(1,$count);
            $result = $sql->query("SELECT guid, name, totalKills, level, race, class, gender, account FROM characters WHERE name!=''
            AND online=1 LIMIT ".$rand.",".$GLOBALS['playersOnline']['moduleResults']);
        }
        else
            $result = $sql->query("SELECT guid, name, totalKills, level, race, class, gender, account FROM characters WHERE name!='' AND online=1");
        while($row = mysqli_fetch_assoc($result))
        {
            connect::connectToRealmDB($rid);
            $getGuild = $sql->query("SELECT guildid FROM guild_member WHERE guid='".$row['guid']."'");
            if(!$getGuild)
                $guild = "None";
            else
            {
                $g = mysqli_fetch_assoc($getGuild);
                $getGName = $sql->query("SELECT name FROM guild WHERE guildid='".$g['guildid']."'");
                $x = mysqli_fetch_assoc($getGName);
                $guild = '&lt; '.$x['name'].' &gt;';
            }

            if($GLOBALS['playersOnline']['display_GMS'] == false)
            {
                connect::selectDB('logondb');
                $checkGM = $sql->query("SELECT COUNT(*) FROM account_access WHERE id='".$row['account']."' AND gmlevel >0");
                if($checkGM)
                    echo '<tr style="text-align: center;"><td>'.$row['name'].'</td><td>'.$guild.'</td><td>'.$row['totalKills'].'</td><td>'.$row['level'].'</td></tr>';
            }
        }
        echo "</table>";
        if($GLOBALS['playersOnline']['enablePage'] == true)
            echo '<hr/><a href="?p=playersonline">View more...</a>';
    }
    echo '</div>';