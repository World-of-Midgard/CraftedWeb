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

if($_POST['action'] == "setTemplate")
{
    $sql->query("UPDATE template SET applied='0' WHERE applied='1'");
    $sql->query("UPDATE template SET applied='1' WHERE id='".(int)$_POST['id']."'");
}

if($_POST['action'] == "installTemplate")
{
    $sql->query("INSERT INTO template VALUES('','".addslashes(trim($_POST['name']))."','".addslashes(trim($_POST['path']))."','0')");
	$server->logThis("Installed the template ".$_POST['name']);
}

if($_POST['action']=="uninstallTemplate") 
{
	$sql->query("DELETE FROM template WHERE id='".(int)$_POST['id']."'");
	$sql->query("UPDATE template SET applied='1' ORDER BY id ASC LIMIT 1");
	$server->logThis("Uninstalled a template");
}

if($_POST['action']=="getMenuEditForm") 
{
	$result = $sql->query("SELECT * FROM site_links WHERE position='".(int)$_POST['id']."'");
	$rows = mysqli_fetch_assoc($result);
	 ?>
    Title<br/>
    <input type="text" id="editlink_title" value="<?php echo $rows['title']; ?>"><br/>
    URL<br/>
    <input type="text" id="editlink_url" value="<?php echo $rows['url']; ?>"><br/>
    Show when<br/>
    <select id="editlink_shownWhen">
             <option value="always" <?php if($rows['shownWhen']=="always") { echo "selected='selected'"; } ?>>Always</option>
             <option value="logged" <?php if($rows['shownWhen']=="logged") { echo "selected='selected'"; } ?>>The user is logged in</option>
             <option value="notlogged" <?php if($rows['shownWhen']=="notlogged") { echo "selected='selected'"; } ?>>The user is not logged in</option>
    </select><br/>
    <input type="submit" value="Save" onclick="saveMenuLink('<?php echo $rows['position']; ?>')">
	
<?php }

if($_POST['action'] == "saveMenu")
{
	$title = addslashes($_POST['title']);
	$url = addslashes($_POST['url']);
	$shownWhen = addslashes($_POST['shownWhen']);
	$id = (int)$_POST['id'];
	
	if(empty($title) || empty($url) || empty($shownWhen))
		die("Please enter all fields.");

    $sql->query("UPDATE site_links SET title='".$title."',url='".$url."',shownWhen='".$shownWhen."' WHERE position='".$id."'");
	$server->logThis("Modified the menu");
	echo true;
}

if($_POST['action'] == "deleteLink")
{
    $sql->query("DELETE FROM site_links WHERE position='".(int)$_POST['id']."'");
	$server->logThis("Removed a menu link");
	echo true;
}

if($_POST['action'] == "addLink")
{
	$title = addslashes($_POST['title']);
	$url = addslashes($_POST['url']);
	$shownWhen = addslashes($_POST['shownWhen']);
	if(empty($title) || empty($url) || empty($shownWhen))
		die("Please enter all fields.");

    $sql->query("INSERT INTO site_links VALUES('','".$title."','".$url."','".$shownWhen."')");
	$server->logThis("Added ".$title." to the menu");
	echo true;
}

if($_POST['action'] == "deleteImage")
{
	$id = (int)$_POST['id'];
    $sql->query("DELETE FROM slider_images WHERE position='".$id."'");
	$server->logThis("Removed a slideshow image");
	return;
}

if($_POST['action'] == "disablePlugin")
{
	$foldername = addslashes($_POST['foldername']);
    $sql->query("INSERT INTO disabled_plugins VALUES('".$foldername."')");
	include('../../plugins/'.$foldername.'/info.php');
	$server->logThis("Disabled the plugin ".$foldername);
}

if($_POST['action'] == "enablePlugin")
{
	$foldername = addslashes($_POST['foldername']);
    $sql->query("DELETE FROM disabled_plugins WHERE foldername='".$foldername."'");
	include('../../plugins/'.$foldername.'/info.php');
	$server->logThis("Enabled the plugin ".$foldername);
}