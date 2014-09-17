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
	if(isset($_POST['update_alert']))
	{
		$alert_enable = $_POST['alert_enable'];
		$alert_message = trim($_POST['alert_message']);
		$alert_enable =($alert_enable == 'on') ? 'true' : 'false';
		$file_content = '<?php $alert_enabled = '.$alert_enable.'; $alert_message = "'.$alert_message.'";?>';
		$fp = fopen('../documents/alert.php', 'w');
		if(fwrite($fp, $file_content))
			$msg = 'The alert message was updated!';
		else
			$msg = '[Failure]Could not write to file!';
		fclose($fp);
	}

	include('../documents/alert.php');
?>
<div class="box_right_title"><?php echo $page->titleLink(); ?> &raquo; Alert Message</div>
<form action="?p=interface&s=alert" method="post">
<table>
	<tr>
    	<td>Enable Alert Message</td>
        <td><input name="alert_enable" type="checkbox" <?php if ($alert_enabled==true) echo 'checked'; ?> /></td>
    </tr>
    <tr>
    	<td>Alert Message</td>
        <td><textarea name="alert_message" cols="60" rows="3"><?php echo $alert_message; ?></textarea>
    </tr>
    <tr>
    	<td></td>
        <td><input type="submit" value="Save" name="update_alert">
        <?php
			if(isset($msg))
				echo $msg;
		?>
        </td>
    </tr>
</table>
    </form>
</td>