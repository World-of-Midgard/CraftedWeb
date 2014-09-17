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
    $page->validatePageAccess('Shop');
    if($page->validateSubPage())
        $page->outputSubPage();
    else
    {
        $server->SelectDB('webdb');
        $inShop = $sql->query("SELECT COUNT(*) AS count FROM shopitems");
        $res_inShop = mysqli_fetch_assoc($inShop);
        $purchToday = $sql->query("SELECT COUNT(*) AS count FROM shoplog WHERE date LIKE '%".date('Y-m-d')."%'");
        $res_purchToday = mysqli_fetch_assoc($purchToday);
        $getAvg = $sql->query("SELECT AVG(price) AS count FROM shopitems");
        $res_getAvg = mysqli_fetch_assoc($getAvg);
        $totalPurch = $sql->query("SELECT COUNT(*) AS count FROM shoplog");
        $res_totalPurch = mysqli_fetch_assoc($totalPurch);
        ?>
        <div class="box_right_title">Shop Overview</div>
        <table style="width: 100%;">
        <tr>
        <td><span class='blue_text'>Items in shop</span></td><td><?php echo round($res_inShop['count']);?></td>
        </tr>
        <tr>
            <td><span class='blue_text'>Purchases today</span></td><td><?php echo round($res_purchToday['count']); ?></td>
            <td><span class='blue_text'>Total purchases</span></td><td><?php echo round($res_totalPurch['count']); ?></td>
        </tr>
        <tr>
            <td><span class='blue_text'>Average item cost</span></td><td><?php echo round($res_getAvg['count']); ?></td>
        </tr>
        </table>
        <hr/>
        <a href="?p=shop&s=add" class="content_hider">Add Items</a>
        <a href="?p=shop&s=manage" class="content_hider">Manage Items</a>
        <a href="?p=shop&s=tools" class="content_hider">Tools</a>
<?php }