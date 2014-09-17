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
    <div class="box_right_title">Manage Realms</div>
    <table class="center">
    <tr><th>ID</th><th>Name</th><th>Host</th><th>Port</th><th>Character DB</th><th>Actions</th></tr>
    <?php
        $server->SelectDB('webdb');
        $result = $sql->query("SELECT * FROM realms ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($result)) { ?>
              <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><?php echo $row['name']; ?></td>
                  <td><?php echo $row['host']; ?></td>
                  <td><?php echo $row['port']; ?></td>
                  <td><?php echo $row['char_db']; ?></td>
                  <td><a href="#" onclick="edit_realm(<?php echo $row['id']; ?>,'<?php echo $row['name']; ?>','<?php echo $row['host']; ?>',
                  '<?php echo $row['port']; ?>','<?php echo $row['char_db']; ?>')">Edit</a> &nbsp;
                  <a href="#" onclick="delete_realm(<?php echo $row['id']; ?>,'<?php echo $row['name']; ?>')">Delete</a><br/>
                  <a href="#" onclick="edit_console(<?php echo $row['id']; ?>,'<?php echo $row['sendType']; ?>','<?php echo $row['rank_user']; ?>',
                  '<?php echo $row['rank_pass']; ?>')">Edit Console settings</a>
                  </td>
              </tr>
        <?php }
    ?>
    </table>