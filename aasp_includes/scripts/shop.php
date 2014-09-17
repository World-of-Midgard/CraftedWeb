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

if($_POST['action'] == 'addsingle')
{
	$entry = (int)$_POST['entry'];
	$price = (int)$_POST['price'];
	$shop = addslashes($_POST['shop']);
	if(empty($entry) || empty($price) || empty($shop))
		die("Please enter all fields.");

	$server->SelectDB('worlddb');
	$get = $sql->query("SELECT name,displayid,ItemLevel,quality,AllowableRace,AllowableClass,class,subclass,Flags FROM item_template WHERE entry='".$entry."'");
	$row = mysqli_fetch_assoc($get);
	$server->SelectDB('webdb');
	if($row['AllowableRace'] == "-1")
		$faction = 0;
	elseif($row['AllowableRace'] == 690)
		$faction = 1;
	elseif($row['AllowableRace'] == 1101)
		$faction = 2;
	else
		$faction = $row['AllowableRace'];

    $sql->query("INSERT INTO shopitems (entry,name,in_shop,displayid,type,itemlevel,quality,price,class,faction,subtype,flags) VALUES (
	'".$entry."','".addslashes($row['name'])."','".$shop."','".$row['displayid']."','".$row['class']."','".$row['ItemLevel']."'
	,'".$row['quality']."','".$price."','".$row['AllowableClass']."','".$faction."','".$row['subclass']."','".$row['Flags']."'
	)");
	
	$server->logThis("Added ".$row['name']." to the ".$shop." shop");
	echo 'Successfully added item';
}

if($_POST['action'] == 'addmulti')
{
	$il_from = (int)$_POST['il_from'];
	$il_to = (int)$_POST['il_to'];
	$price = (int)$_POST['price'];
	$quality = addslashes($_POST['quality']);
	$shop = addslashes($_POST['shop']);
	$type = addslashes($_POST['type']);
	if(empty($il_from) || empty($il_to) || empty($price) || empty($shop))
		die("Please enter all fields.");
		
	$advanced = "";
	if($type!="all") 
	{
		if($type=="15-5" || $type=="15-5")  
		{
			$type = explode('-',$type);
			$advanced.= "AND class='".$type[0]."' AND subclass='".$type[1]."'";
		} 
		else	
			$advanced.= "AND class='".$type."'";
	} 	

	if($quality!="all")
		$advanced .= " AND quality='".$quality."'";
	        
	$server->SelectDB('worlddb');
	$get = $sql->query("SELECT entry,name,displayid,ItemLevel,quality,class,AllowableRace,AllowableClass,subclass,Flags
	FROM item_template WHERE itemlevel>='".$il_from."'
	AND itemlevel<='".$il_to."' ".$advanced);
	
	$server->SelectDB('webdb');
	
	$c = 0;
	while($row = mysqli_fetch_assoc($get))
	{
		$faction = 0;
		if($row['AllowableRace'] == 690)
			$faction = 1;
		elseif($row['AllowableRace'] == 1101)
			$faction = 2;
		else
			$faction = $row['AllowableRace'];

        $sql->query("INSERT INTO shopitems (entry,name,in_shop,displayid,type,itemlevel,quality,price,class,faction,subtype,flags) VALUES (
        '".$row['entry']."','".addslashes($row['name'])."','".$shop."','".$row['displayid']."','".$row['class']."','".$row['ItemLevel']."'
        ,'".$row['quality']."','".$price."','".$row['AllowableClass']."','".$faction."','".$row['subclass']."','".$row['Flags']."')");
        $c++;
	}
	$server->logThis("Added multiple items to the ".$shop." shop");
	echo 'Successfully added '.$c.' items';
}

if($_POST['action']=='clear') 
{
	$shop = (int)$_POST['shop'];
	if($shop == 1)
		$shop = "vote";
	elseif($shop == 2)
		$shop = "donate";
    $sql->query("DELETE FROM shopitems WHERE in_shop='".$shop."'");
    $sql->query("TRUNCATE shopitems");
	return;
}

if($_POST['action'] == 'modsingle')
{
	$entry = (int)$_POST['entry'];
	$price = (int)$_POST['price'];
	$shop = addslashes($_POST['shop']);
	if(empty($entry) || empty($price) || empty($shop))
		die("Please enter all fields.");
    $sql->query("UPDATE shopitems SET price='".$price."' WHERE entry='".$entry."' AND in_shop='".$shop."'");
	echo 'Successfully modified item';
}

if($_POST['action'] == 'delsingle')
{
	$entry = (int)$_POST['entry'];
	$shop = addslashes($_POST['shop']);
	if(empty($entry) || empty($shop))
		die("Please enter all fields.");
    $sql->query("DELETE FROM shopitems WHERE entry='".$entry."' AND in_shop='".$shop."'");
	echo 'Successfully removed item';
}

if($_POST['action']=='modmulti') 
{
	$il_from = (int)$_POST['il_from'];
	$il_to = (int)$_POST['il_to'];
	$price = (int)$_POST['price'];
	$quality = addslashes($_POST['quality']);
	$shop = addslashes($_POST['shop']);
	$type = addslashes($_POST['type']);
	if(empty($il_from) || empty($il_to) || empty($price) || empty($shop))
		die("Please enter all fields.");
	$advanced = "";
	if($type != "all")
	{
		if($type == "15-5" || $type == "15-5")
		{
			$type = explode('-',$type);
			$advanced.= "AND type='".$type[0]."' AND subtype='".$type[1]."'";
		} 
		else	
			$advanced.= "AND type='".$type."'";
	} 	

	if($quality != "all")
		$advanced .= "AND quality='".$quality."'";
	$count = $sql->query("COUNT(*) AS count FROM shopitems WHERE itemlevel >='".$il_from."' AND itemlevel <='".$il_to."' ".$advanced);
    $res_count = mysqli_fetch_assoc($count);
    $sql->query("UPDATE shopitems SET price='".$price."' WHERE itemlevel >='".$il_from."' AND itemlevel <='".$il_to."' ".$advanced);
	echo 'Successfully modified '.$res_count['count'].' items!';
}

if($_POST['action'] == 'delmulti')
{
	$il_from = (int)$_POST['il_from'];
	$il_to = (int)$_POST['il_to'];
	$quality = addslashes($_POST['quality']);
	$shop = addslashes($_POST['shop']);
	$type = addslashes($_POST['type']);
	if(empty($il_from) || empty($il_to) || empty($shop))
		die("Please enter all fields.");
	$advanced = "";
	if($type!="all") 
	{
		if($type=="15-5" || $type=="15-5")  
		{
			$type = explode('-',$type);
			$advanced.= "AND type='".$type[0]."' AND subtype='".$type[1]."'";
		} 
		else	
			$advanced.= "AND type='".$type."'";
	}
	if($quality!="all")
		$advanced .= "AND quality='".$quality."'";
	$count = $sql->query("COUNT(*) AS count FROM shopitems WHERE itemlevel >='".$il_from."' AND itemlevel <='".$il_to."' ".$advanced);
    $result_count = mysqli_fetch_assoc($count);
    $sql->query("DELETE FROM shopitems WHERE itemlevel >='".$il_from."' AND itemlevel <='".$il_to."' ".$advanced);
	echo 'Successfully removed '.$result_count['count'].' items!';
}