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
<div class="box_right_title"><?php echo $page->titleLink(); ?> &raquo; Menu</div>
<table class="center">
        <tr><th>Position</th><th>Title</th><th>Url</th><th>Shown When</th><th>Actions</th></tr>
        <?php 
        $x = 1;
            $result = $sql->query("SELECT * FROM site_links ORDER BY position ASC");
            while($row = mysqli_fetch_assoc($result)) { ?>
                <tr><td><?php echo $x; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['url']; ?></td>
                <td><?php 
						if($row['shownWhen']=='logged') {
							echo "Logged in";
						} elseif($row['shownWhen']=='notlogged') {
							echo "Not logged in";
						}  else {
							echo ucfirst($row['shownWhen']);
						}
                   ?>
                </td>
                <td>
                    <a href="#" onclick="editMenu(<?php echo $row['position']; ?>)"
                    >Edit</a> &nbsp; <a href="#" onclick="deleteLink(<?php echo $row['position']; ?>)">Delete</a>
                </td>
                </tr>
            <?php $x++; }
        ?>
 </table>
 <br/>
 <a href="#" onclick="addLink()" class="content_hider">Add a new link</a>