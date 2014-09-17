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

if($_POST['action'] == 'toggle')
 {
	if($_POST['value'] == 1)
        $sql->query("DELETE FROM disabled_pages WHERE filename='".addslashes($_POST['filename'])."'");
	elseif($_POST['value'] == 2)
        $sql->query("INSERT IGNORE disabled_pages values('".addslashes($_POST['filename'])."')");
}

if($_POST['action'] == 'delete')
{
    $sql->query("DELETE FROM custom_pages WHERE filename='".addslashes($_POST['filename'])."'");
	return;
}

if($_POST['action'] == 'saveVoteLink')
{
	$id = (int)$_POST['id'];
	$title = addslashes($_POST['title']);
	$points = (int)$_POST['points'];
	$image = addslashes($_POST['image']);
	$url = addslashes($_POST['url']);
	if(!empty($id))
        $sql->query("UPDATE votingsites SET title='".$title."',points='".$points."',image='".$image."',url='".$url."' WHERE id='".$id."'");
}

if($_POST['action'] == 'removeVoteLink')
{
	$id = (int)$_POST['id'];
    $sql->query("DELETE FROM votingsites WHERE id='".$id."'");
}

if($_POST['action'] == 'addVoteLink')
{
	$title = addslashes($_POST['title']);
	$points = (int)$_POST['points'];
	$image = addslashes($_POST['image']);
	$url = addslashes($_POST['url']);
	if(!empty($title) && !empty($points) && !empty($image) && !empty($url))
        $sql->query("INSERT INTO votingsites VALUES('','".$title."','".$points."','".$image."','".$url."')");
}

if($_POST['action'] == 'saveServicePrice')
{
	$service = addslashes($_POST['service']);
	$price = (int)$_POST['price'];
	$currency = addslashes($_POST['currency']);
	$enabled = addslashes($_POST['enabled']);
    $sql->query("UPDATE service_prices SET price='".$price."',currency='".$currency."',enabled='".$enabled."'
	WHERE service='".$service."'");
}