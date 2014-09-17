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
    $account = new account;
    $sql = $server->sqli();
    ?>
    <div class="box_right_title">Character Services</div>
    <table class="center">
    <tr><th>Service</th><th>Price</th><th>Currency</th><th>Status</th></tr>
    <?php
    $result = $sql->query("SELECT * FROM service_prices");
    while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['service']; ?></td>
            <td><input type="text" value="<?php echo $row['price']; ?>" style="width: 50px;" id="<?php echo $row['service']; ?>_price" class="noremove"/></td>
            <td><select style="width: 200px;" id="<?php echo $row['service']; ?>_currency">
                 <option value="vp" <?php if ($row['currency']=='vp') echo 'selected'; ?>>Vote Points</option>
                 <option value="dp" <?php if ($row['currency']=='dp') echo 'selected'; ?>><?php echo $GLOBALS['donation']['coins_name']; ?></option>
            </select></td>
            <td><select style="width: 150px;" id="<?php echo $row['service']; ?>_enabled">
                 <option value="true" <?php if ($row['enabled']=='true') echo 'selected'; ?>>Enabled</option>
                 <option value="false" <?php if ($row['enabled']=='false') echo 'selected'; ?>>Disabled</option>
            </select></td>
            <td><input type="submit" value="Save" onclick="saveServicePrice('<?php echo $row['service']; ?>')"/>
        </tr>
    <?php }
    ?>
    </table>