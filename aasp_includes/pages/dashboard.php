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
?>
<div class="box_right_title">Dashboard</div>
<table style="width: 605px;">
<tr>
<td><span class='blue_text'>Active Connections</span></td><td><?php echo $server->getActiveConnections(); ?></td>
<td><span class='blue_text'>Active accounts(This month)</span></td><td><?php echo $server->getActiveAccounts(); ?></td>
</tr>
<tr>
     <td><span class='blue_text'>Account logged in today</span></td><td><?php echo $server->getAccountsLoggedToday(); ?></td> 
    <td><span class='blue_text'>Accounts created today</span></td><td><?php echo $server->getAccountsCreatedToday(); ?></td>
</tr>
</table>
</div>
<?php $server->checkForNotifications(); ?>
<div class="box_right">
        <div class="box_right_title">Admin Panel log</div>
        <?php
                    $server->SelectDB('webdb');
                    $result = $sql->query("SELECT * FROM admin_log ORDER BY id DESC LIMIT 10");
                    if(mysqli_num_rows($result) <= 0) {
                        echo "The admin log was empty!";
                    } else { ?>
        <table class="center">
               <tr><th>Date</th><th>User</th><th>Action</th></tr>
                    <?php
                    while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo date("Y-m-d H:i:s",$row['timestamp']); ?></td>
                            <td><?php echo $account->getAccName($row['account']); ?></td>
                            <td><?php echo $row['action']; ?></td>
                        </tr>
                    <?php }
               ?>
        </table><br/>
        <a href="?p=logs&s=admin" title="Get more logs">Older logs...</a>
        <?php } ?>
</div>