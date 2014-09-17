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

 	 $server->SelectDB('webdb');
 	 $page = new page;
	 $page->validatePageAccess('Users');
     if($page->validateSubPage())
		 $page->outputSubPage();
	 else
     {
		 $server->SelectDB('logondb');
		 $usersTotal = $sql->query("SELECT COUNT(*) AS count FROM account");
         $res_usersTotal = mysqli_fetch_assoc($usersTotal);
		 $usersToday = $sql->query("SELECT COUNT(*) AS count FROM account WHERE joindate LIKE '%".date("Y-m-d")."%'");
         $res_usersToday = mysqli_fetch_assoc($usersToday);
		 $usersMonth = $sql->query("SELECT COUNT(*) AS count FROM account WHERE joindate LIKE '%".date("Y-m")."%'");
         $res_usersMonth = mysqli_fetch_assoc($usersMonth);
		 $usersOnline = $sql->query("SELECT COUNT(*) AS count FROM account WHERE online=1");
         $res_usersOnline = mysqli_fetch_assoc($usersOnline);
		 $usersActive = $sql->query("SELECT COUNT(*) AS count FROM account WHERE last_login LIKE '%".date("Y-m")."%'");
         $res_usersActive = mysqli_fetch_assoc($usersActive);
		 $usersActiveToday = $sql->query("SELECT COUNT(*) AS count FROM account WHERE last_login LIKE '%".date("Y-m-d")."%'");
         $res_usersActiveToday = mysqli_fetch_assoc($usersActiveToday);
        ?>
        <div class="box_right_title">Users Overview</div>
        <table style="width: 100%;">
        <tr>
        <td><span class='blue_text'>Total users</span></td><td><?php echo $res_usersTotal['count']; ?></td>
        <td><span class='blue_text'>New users today</span></td><td><?php echo $res_usersToday['count']; ?></td>
        </tr>
        <tr>
            <td><span class='blue_text'>New users this month</span></td><td><?php echo $res_usersMonth['count']; ?></td>
            <td><span class='blue_text'>Users online</span></td><td><?php echo $res_usersOnline['count']; ?></td>
        </tr>
        <tr>
            <td><span class='blue_text'>Active users (this month)</span></td><td><?php echo $res_usersActive['count']; ?></td>
            <td><span class='blue_text'>Users logged in today</span></td><td><?php echo $res_usersActiveToday['count']; ?></td>
        </tr>
        </table>
        <hr/>
        <a href="?p=users&s=manage" class="content_hider">Manage Users</a>
<?php }