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
$sql = $server->sqli();
if($_POST['function'] == 'post')
{
	if(empty($_POST['title']) || empty($_POST['author']) || empty($_POST['content']))
		die('<span class="red_text">Please enter all fields.</span>');

	$sql->query("INSERT INTO news (title,body,author,image,date) VALUES
	('".addslashes($_POST['title'])."','".addslashes(trim(htmlentities($_POST['content'])))."',
	'".addslashes($_POST['author'])."','".addslashes($_POST['image'])."',
	'".date("Y-m-d H:i:s")."')");

    echo '<META http-equiv="refresh" content="1;URL=?p=news&s=manage">';
    $server->logThis("Posted a news post");
}

elseif($_POST['function'] == 'delete')
{
	if(empty($_POST['id']))
		die('No ID specified. Aborting...');

    $sql->query("DELETE FROM news WHERE id='".addslashes($_POST['id'])."'");
    $sql->query("DELETE FROM news_comments WHERE id='".addslashes($_POST['id'])."'");
	$server->logThis("Deleted a news post");
}

elseif($_POST['function'] == 'edit')
{
	$id = (int)$_POST['id'];
	$title = ucfirst(addslashes($_POST['title']));
	$author = ucfirst(addslashes($_POST['author']));
	$content = addslashes(($_POST['content']));
	
	if(empty($id) || empty($title) || empty($content))
	 	die("Please enter both fields.");
    else 
	{
        $sql->query("UPDATE news SET title='".$title."', author='".$author."', body='".$content."' WHERE id='".$id."'");
		$server->logThis("Updated news post with ID: <b>".$id."</b>");
        die();
	}
}

elseif($_POST['function']=='getNewsContent') 
{
	$result = $sql->query("SELECT * FROM news WHERE id='".(int)$_POST['id']."'");
	$row = mysqli_fetch_assoc($result);
	$content = str_replace('<br />', "\n", $row['body']);
	echo "Title: <br/><input type='text' id='editnews_title' value='".$row['title']."'><br/>Content:<br/><textarea cols='55' rows='8' id='wysiwyg'>"
	.$content."</textarea><br/>Author:<br/><input type='text' id='editnews_author' value='".$row['author']."'><br/><input type='submit' value='Save' onclick='editNewsNow(".$row['id'].")'>";
}