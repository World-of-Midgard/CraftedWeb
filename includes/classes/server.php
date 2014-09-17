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
class server
{
	public static function getRealmId($char_db)
	{
        global $sql;
		connect::SelectDB('webdb');
		$get = $sql->query("SELECT id FROM realms WHERE char_db='".addslashes($char_db)."'");
		$row = mysqli_fetch_assoc($get);
		return $row['id'];
	}
	
	public static function getRealmName($char_db)
	{
        global $sql;
        connect::SelectDB('webdb');
		$get = $sql->query("SELECT name FROM realms WHERE char_db='".addslashes($char_db)."'");
		$row = mysqli_fetch_assoc($get);
		return $row['name'];
	}
	
	public static function serverStatus($realm_id) 
	{
        global $sql;
        $fp = fsockopen($GLOBALS['realms'][$realm_id]['host'], $GLOBALS['realms'][$realm_id]['port'], $errno, $errstr, 1);
        if (!$fp)
            echo $status = '<h4 class="realm_status_title_offline">'.$GLOBALS['realms'][$realm_id]['name'].' -  Offline</h4>';
        else
        {
            echo $status = '<h4 class="realm_status_title_online">'.$GLOBALS['realms'][$realm_id]['name'].' - Online</h4>';
            echo '<span class="realm_status_text">';
            if($GLOBALS['serverStatus']['factionBar']==true)
            {
                connect::connectToRealmDB($realm_id);
                $getChars = $sql->query("SELECT COUNT(online) FROM characters WHERE online=1");
                $total_online = mysqli_fetch_assoc($getChars);
                $getAlliance = $sql->query("SELECT COUNT(online) FROM characters WHERE online=1 AND race IN('3','4','7','11','1','22')");
                $alliance = mysqli_fetch_assoc($getAlliance);
                $getHorde = $sql->query("SELECT COUNT(online) FROM characters WHERE online=1 AND race IN('2','5','6','8','10','9')");
                $horde = mysqli_fetch_assoc($getHorde);
                if($total_online == 0)
                {
                    $per_alliance = 50;
                    $per_horde = 50;
                }
                else
                {
                    if($alliance == 0)
                        $per_alliance = 0;
                    else
                        $per_alliance = round(($alliance / $total_online) * 100);
                    if($horde == 0)
                        $per_horde = 0;
                    else
                        $per_horde = round(($horde / $total_online) * 100);
                }
                if($per_alliance + $per_horde > 100)
                    $per_horde = $per_horde - 1 ;

                ?>
                <div class='srv_status_po'>
                <div class='srv_status_po_alliance' style="width: <?php echo $per_alliance; ?>%;"></div>
                <div class='srv_status_po_horde' style="width: <?php echo $per_horde; ?>%;"></div>
                <div class='srv_status_text'>Alliance: <?php echo $alliance;?> &nbsp;  Horde: <?php echo $horde;?></div>
                </div>
                <?php
            }
            echo '<table width="100%"><tr>';

            if ($GLOBALS['serverStatus']['playersOnline']==true)
            {
                connect::connectToRealmDB($realm_id);
                $getChars = $sql->query("SELECT COUNT(online) AS count FROM characters WHERE online=1");
                $pOnline = mysqli_fetch_assoc($getChars);
                echo '<td>
                <b>',$pOnline['count'],'</b> Players online
                </td>';
            }

            if ($GLOBALS['serverStatus']['uptime']==true)
            {
                connect::SelectDB('logondb');
                $getUp = $sql->query("SELECT starttime FROM uptime WHERE realmid='".$realm_id."' ORDER BY starttime DESC LIMIT 1");
                $row = mysqli_fetch_assoc($getUp);
                $time = time();
                $uptime = $time - $row['starttime'];
                echo '
                <td>
                <b>'.convTime($uptime).'</b> uptime
                </td>
                </tr>';
            }
        }
        if ($GLOBALS['serverStatus']['nextArenaFlush']==true)
        {
            connect::connectToRealmDB($realm_id);
            $getFlush = $sql->query("SELECT value FROM worldstates WHERE comment='NextArenaPointDistributionTime'");
            $row = mysqli_fetch_assoc($getFlush);
            $flush = date('d M H:i', $row['value']);
            echo '<tr>
            <td>
            Next arena flush: <b>'.$flush.'</b>
            </td>';
        }
        echo '</tr>
        </table>
        </span>';
    }
}