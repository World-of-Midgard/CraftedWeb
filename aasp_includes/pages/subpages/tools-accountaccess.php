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
    $account = new account;
    $page = new page;
    $sql = $server->sqli();
    $page->validatePageAccess('Tools->Account Access');

    ?>
    <div class="box_right_title">Account Access</div>
    All GM accounts are listed below.
    <br/>&nbsp;
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Rank</th>
            <th>Realms</th>
            <th>Status</th>
            <th>Last Login</th>
            <th>Actions</th>
        </tr>
        <?php
        $server->SelectDB('logondb');
        $result = $sql->query("SELECT * FROM account_access");
        if(!$result)
            echo "<b>No GM accounts found!</b>";
        else
        {
            while($row = mysqli_fetch_assoc($result))
            {
                ?>
                <tr style="text-align:center;">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $account->getAccName($row['id']); ?></td>
                    <td><?php echo $row['gmlevel']; ?></td>
                    <td>
                    <?php
                        if($row['RealmID'] == '-1')
                            echo 'All';
                        else
                        {
                            $server->SelectDB('webdb');
                            $getRealm = $sql->query("SELECT * FROM realms WHERE id='".$row['RealmID']."'");
                            if(!$getRealm)
                                echo 'Unknown';
                            $rows = mysqli_fetch_assoc($getRealm);
                            echo $rows['name'];
                        }
                    ?>
                    </td>
                    <td>
                    <?php
                        $server->SelectDB('logondb');
                        $getData = $sql->query("SELECT last_login,online FROM account WHERE id='".$row['id']."'");
                        $rows = mysqli_fetch_assoc($getData);
                        if($rows['online'] == 0)
                            echo '<font color="red">Offline</font>';
                        else
                            echo '<font color="green">Online</font>';
                    ?>
                    </td>
                    <td>
                    <?php
                        echo $rows['last_login'];
                    ?>
                    </td>
                    <td>
                        <a href="#" onclick="editAccA(<?php echo $row['id']; ?>,<?php echo $row['gmlevel']; ?>,<?php echo $row['RealmID']; ?>)">Edit</a>
                        &nbsp;
                        <a href="#" onclick="removeAccA(<?php echo $row['id']; ?>)">Remove</a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <hr/>
    <a href="#" class="content_hider" onclick="addAccA()">Add account</a>