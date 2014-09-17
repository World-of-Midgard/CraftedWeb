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

$server = new server;
$account = new account;
$server->SelectDB('webdb');

if($GLOBALS['core_expansion'] == 3)
	$guidString = 'playerGuid';
else
	$guidString = 'guid';	

if($GLOBALS['core_expansion'] == 3)
	$closedString = 'closed';
else
	$closedString = 'closedBy';
	
if($GLOBALS['core_expansion'] == 3)

	$ticketString = 'guid';
else
	$ticketString = 'ticketId';

if($_POST['action'] == 'edit')
{
	$id = (int)$_POST['id'];
	$new_id = (int)$_POST['new_id'];
	$name = addslashes(trim($_POST['name']));
	$host = addslashes(trim($_POST['host']));
	$port = (int)$_POST['port'];
	$chardb = addslashes(trim($_POST['chardb']));
	
	if(empty($name) || empty($host) || empty($port) || empty($chardb))
		die("<span class='red_text'>Please enter all fields.</span><br/>");
	
	$server->logThis("Updated realm information for ".$name);
    $sql->query("UPDATE realms SET id='".$new_id."',name='".$name."',host='".$host."',port='".$port."',char_db='".$chardb."' WHERE id='".$id."'");
	return true;
}

if($_POST['action'] == 'delete')
{
	$id = (int)$_POST['id'];
	$sql->query("DELETE FROM realms WHERE id='".$id."'");
	$server->logThis("Deleted a realm");
}

if($_POST['action'] == 'edit_console')
{
	$id = (int)$_POST['id'];
	$type = addslashes($_POST['type']);
	$user = addslashes(trim($_POST['user']));
	$pass = addslashes(trim($_POST['pass']));
	if(empty($id) || empty($type) || empty($user) || empty($pass))
		die();

	$server->logThis("Updated console information for realm with ID: ".$id);
    $sql->query("UPDATE realms SET sendType='".$type."',rank_user='".$user."',rank_pass='".$pass."' WHERE id='".$id."'");
	return true;
}

if($_POST['action'] == 'loadTickets')
{
	$offline = $_POST['offline'];
	$realm = addslashes($_POST['realm']);
	$_SESSION['lastTicketRealm']=$realm;
	$_SESSION['lastTicketRealmOffline']=$offline;
	if($realm == "NULL")
	   die("<pre>Please select a realm.</pre>");
	
	$server->SelectDB($realm);
	$result = $sql->query("SELECT ".$ticketString.",name,message,createtime,".$guidString.",".$closedString." FROM gm_tickets ORDER BY ticketId DESC");
	if(mysqli_num_rows($result) <= 0)
	   die("<pre>No tickets were found!</pre>");
	   
	echo '
	<table class="center">
       <tr>
           <th>ID</th>
           <th>Name</th>
           <th>Message</th>
           <th>Created</th>
		   <th>Ticket Status</th>
           <th>Player Status</th>
           <th>Quick Tools</th>
       </tr>
	';
	
	while($row = mysqli_fetch_assoc($result))
	{
		$get = $sql->query("SELECT COUNT(online) AS count FROM characters WHERE guid='".$row[$guidString]."' AND online='1'");
        $res_get = mysqli_fetch_assoc($get);
		if($res_get['count'] <= 0 && $offline == "on")
        {
		    echo '<tr>';
			echo '<td><a href="?p=tools&s=tickets&guid='.$row[$ticketString].'&db='.$realm.'">'.$row[$ticketString].'</td>';
			echo '<td><a href="?p=tools&s=tickets&guid='.$row[$ticketString].'&db='.$realm.'">'.$row['name'].'</td>';
			echo '<td><a href="?p=tools&s=tickets&guid='.$row[$ticketString].'&db='.$realm.'">'.substr($row['message'],0,15).'...</td>';
			echo '<td><a href="?p=tools&s=tickets&guid='.$row[$ticketString].'&db='.$realm.'">'.date('Y-m-d H:i:s',$row['createtime']).'</a></td>';
			
			if($row[$closedString] == 1)
				echo '<td><font color="red">Closed</font></td>';
			else
				echo '<td><font color="green">Open</font></td>';		
			
			$get = $sql->query("SELECT COUNT(online) AS count FROM characters WHERE guid='".$row[$guidString]."' AND online='1'");
            $res_get = mysqli_fetch_assoc($get);
			if($res_get['count'] > 0)
			   echo '<td><font color="green">Online</font></td>';
			else
			   echo '<td><font color="red">Offline</font></td>';
			   
			echo "<td><a href='' onclick='deleteTicket('".$row[$ticketString]."','".$realm."')'>Delete</a>&nbsp;";
            if($row[$closedString] == 1)
                echo "<a href='' onclick='openTicket('".$row[$ticketString]."','".$realm."')'>Open</a>";
            else
                echo "<a href='' onclick='closeTicket('".$row[$ticketString]."','".$realm."')'>Close</a></td>";
		    echo '<tr>';
		}
	}
	echo '</table>';
}

if($_POST['action'] == 'deleteTicket')
{
	$id = (int)$_POST['id'];
	$db = addslashes($_POST['db']);
    $sql->select_db($db);
    $sql->query("DELETE FROM gm_tickets WHERE ".$ticketString."='".$id."'");
}

if($_POST['action'] == 'closeTicket')
{
	$id = (int)$_POST['id'];
	$db = addslashes($_POST['db']);
    $sql->select_db($db);
    $sql->query("UPDATE gm_tickets SET ".$closedString."=1 WHERE ".$ticketString."='".$id."'");
}

if($_POST['action'] == 'openTicket')
{
	$id = (int)$_POST['id'];
	$db = addslashes($_POST['db']);
    $sql->select_db($db);
    $sql->query("UPDATE gm_tickets SET ".$closedString."=0 WHERE ".$ticketString."='".$id."'");
}

if($_POST['action'] == 'getPresetRealms')
{
	echo '<h3>Select a realm</h3><hr/>';
	$server->SelectDB('webdb');
	$result = $sql->query('SELECT id,name,description FROM realms ORDER BY id ASC');
	while($row = mysqli_fetch_assoc($result))
	{
		echo '<table width="100%">';
			echo '<tr>';
				echo '<td width="60%">';
					echo '<b>'.$row['name'].'</b>';
					echo '<br/>'.$row['description'];
				echo '</td>';
				
				echo '<td>';
					echo '<input type="submit" value="Select" onclick="savePresetRealm('.$row['id'].')">';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
		echo '<hr/>';	
	}
	
}

if($_POST['action'] == 'savePresetRealm')
{
	$rid = (int)$_POST['rid'];
	if(isset($_COOKIE['presetRealmStatus']))
	{
		setcookie('presetRealmStatus',"",time()-3600*24*30*3,'/');
		setcookie('presetRealmStatus',$rid,time()+3600*24*30*3,'/');
	}
	else
		setcookie('presetRealmStatus',$rid,time()+3600*24*30*3,'/');
}