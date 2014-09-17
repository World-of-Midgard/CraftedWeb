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
$server->SelectDB('logondb');

if($_POST['action'] == 'edit')
{
	$email = addslashes(trim($_POST['email']));
	$password = addslashes(trim(strtoupper($_POST['password'])));
	$vp = (int)$_POST['vp'];
	$dp = (int)$_POST['dp'];
	$id = (int)$_POST['id'];
	$extended = NULL;
	
	$chk1 = $sql->query("SELECT COUNT FROM account WHERE email='".$email."' AND id='".$id."'");
	if($chk1)
		$extended .= "Changed email to".$email."<br/>";

    $sql->query("UPDATE account SET email='".$email."' WHERE id='".$id."'");
	$server->SelectDB('webdb');
    $sql->query("INSERT IGNORE INTO account_data VALUES('".$id."','','','')");
	
    $chk2 = $sql->query("SELECT COUNT FROM account_data WHERE vp='".$vp."' AND id='".$id."'");
    if($chk2)
        $extended .= "Updated Vote Points to ".$vp."<br/>";

    $chk3 = $sql->query("SELECT COUNT FROM account_data WHERE dp='".$dp."' AND id='".$id."'");
    if($chk3)
        $extended .= "Updated Donation Coins to ".$dp."<br/>";

    $sql->query("UPDATE account_data SET vp='".$vp."', dp ='".$dp."' WHERE id='".$id."'");
	
	if(!empty($password)) 
	{
		$username = strtoupper(trim($account->getAccName($id)));
		$password = sha1("".$username.":".$password."");
		$server->SelectDB('logondb');
        $sql->query("UPDATE account SET sha_pass_hash='".$password."' WHERE id='".$id."'");
        $sql->query("UPDATE account SET v='0',s='0' WHERE id='".$id."'");
		$extended .= "Changed password<br/>";
    }
	$server->logThis("Modified account information for ".ucfirst(strtolower($account->getAccName($id))),$extended);
	echo "Settings were saved.";
}

if($_POST['action'] == 'saveAccA')
{
	$id = (int)$_POST['id'];
	$rank = (int)$_POST['rank'];
	$realm = addslashes($_POST['realm']);
    $sql->query("UPDATE account_access SET gmlevel='".$rank."',RealmID='".$realm."' WHERE id='".$id."'");
	$server->logThis("Modified account access for ".ucfirst(strtolower($account->getAccName($id))));
}

if($_POST['action'] == 'removeAccA')
{
	$id = (int)$_POST['id'];
    $sql->query("DELETE FROM account_access WHERE id='".$id."'");
	$server->logThis("Modified GM account access for ".ucfirst(strtolower($account->getAccName($id))));
}

if($_POST['action'] == 'addAccA')
{
	$user = addslashes($_POST['user']);
	$realm = addslashes($_POST['realm']);
	$rank = (int)$_POST['rank'];
	$guid = $account->getAccID($user);
    $sql->query("INSERT INTO account_access VALUES('".$guid."','".$rank."','".$realm."')");
	$server->logThis("Added GM account access for ".ucfirst(strtolower($account->getAccName($guid))));
}

if($_POST['action'] == 'editChar')
{
	$guid = (int)$_POST['guid'];
	$rid = (int)$_POST['rid'];
	$name = addslashes(trim(ucfirst(strtolower($_POST['name']))));
	$class = (int)$_POST['class'];
	$race = (int)$_POST['race'];
	$gender = (int)$_POST['gender'];
	$money = (int)$_POST['money'];
	$accountname = addslashes($_POST['account']);
	$accountid = $account->getAccID($accountname);	
		
	if(empty($guid) || empty($rid) || empty($name) || empty($class) || empty($race))
        exit('Error');
	
	$server->connectToRealmDB($rid);
	$online = $sql->query("SELECT * FROM characters WHERE guid='".$guid."' AND online=1");
	if($online)
		exit('The character must be online for any change to take effect!');

    $sql->query("UPDATE characters SET name='".$name."',class='".$class."',race='".$race."',gender='".$gender."', money='".$money."', account='".$accountid."' WHERE guid='".$guid."'");
	echo 'The character was saved!';

	$chk = $sql->query("SELECT * FROM characters WHERE name='".$name."'");
	if($chk)
		echo '<br/><b>NOTE:</b> It seems like there more than 1 character with this name, this might force them to rename when they log in.';
	
	$server->logThis("Modified character data for ".$name);
}