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
	$server = new server;
    $sql = $server->sqli();
	$filename = $_GET['plugin']; 
	include('../plugins/'.$filename.'/info.php');
?>
<div class="box_right_title"><a href="?p=interface&s=plugins">Plugins</a> &raquo; <?php echo $title; ?></div>
<b><?php echo $title; ?></b><br/>
<?php echo $desc; ?>
<hr/>
Author: <?php echo $author; ?> - <?php echo $created; ?>
<p/>
<?php
    $sql->select_db($GLOBALS['connection']['webdb']);
    $chk = $sql->query("SELECT * FROM disabled_plugins WHERE foldername='".addslashes($filename)."'");
    $chk_res = mysqli_fetch_assoc($chk);
    if($chk_res > 0)
        echo '<input type="submit" value="Enable Plugin" onclick="enablePlugin(\''.$filename.'\')">';
    else
        echo '<input type="submit" value="Disable Plugin" onclick="disablePlugin(\''.$filename.'\')">';