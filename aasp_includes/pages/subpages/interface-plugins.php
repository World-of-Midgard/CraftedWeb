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
    $page = new page;
    $server = new server;
    $sql = $server->sqli();
?>
<div class="box_right_title">Plugins</div>
<table>
	<tr>
    	<th>Name</th>
        <th>Description</th>
        <th>Author</th>
        <th>Created</th>
        <th>Status</th>
    </tr>
<?php
	$bad = array('.','..','index.html');
	$folder = scandir('../plugins/');
	foreach($folder as $folderName)
	{
		if(!in_array($folderName,$bad))
		{
			if(file_exists('../plugins/'.$folderName.'/info.php'))
			{
				include('../plugins/'.$folderName.'/info.php');
				?> <tr class="center" onclick="window.location='?p=interface&s=viewplugin&plugin=<?php echo $folderName; ?>'"> <?php
					echo '<td><a href="?p=interface&s=viewplugin&plugin='.$folderName.'">'.$title.'</a></td>';
					echo '<td>'.substr($desc,0,40).'</td>';
					echo '<td>'.$author.'</td>';
					echo '<td>'.$created.'</td>';
					$server->selectDB('webdb');
					$chk = $sql->query("SELECT COUNT(*) AS count FROM disabled_plugins WHERE foldername='".addslashes($folderName)."'");
                    $res_chk = mysqli_fetch_assoc($chk);
					if($res_chk['count'] > 0)
						echo '<td>Disabled</td>';
					else
						echo '<td>Enabled</td>';
				echo '</tr>';
			}
		}
	}
    /* Useless
	if($count == 0)
		$_SESSION['loaded_plugins'] = NULL;
	else
		$_SESSION['loaded_plugins'] = $loaded_plugins;
    */
?>
</table>