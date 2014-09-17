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
    echo '<div class="box_right_title">Vote Shop logs</div>';
    $per_page = 40;
    $pages_query = $sql->query("SELECT COUNT(*) AS count FROM shoplog WHERE shop='vote'");
    $result_pages = mysqli_fetch_assoc($pages_query);
    $pages = ceil($result_pages['count'] / $per_page );
    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $per_page;
    $result = $sql->query("SELECT * FROM shoplog WHERE shop='vote' ORDER BY id DESC LIMIT ".$start.",".$per_page);
    if(!$result)
        echo "Seems like the vote shop log was empty!";
    else
    {
    ?>
        <input type='text' value='Search...' id="logs_search" onkeyup="searchLog('vote')">
        <?php echo "<br/><b>Showing ".$start."-".($start + $per_page)."</b>"; ?>
        <hr/>
        <div id="logs_content">
        <table width="100%">
                <tr><th>User</th><th>Character</th><th>Realm</th><th>Item</th><th>Date</th></tr>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr class="center">
                    <td><?php echo $account->getAccName($row['account']); ?></td>
                    <td><?php echo $account->getCharName($row['char_id'],$row['realm_id']); ?></td>
                    <td><?php echo $server->getRealmName($row['realm_id']); ?></td>
                    <td><a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                    <?php echo $server->getItemName($row['entry']); ?></a></td>
                    <td><?php echo $row['date']; ?></td>
                </tr>
                <?php } ?>
        </table>
        <hr/>

        <?php
        if($pages>=1 && $page <= $pages)
        {
            if($page>1)
            {
               $prev = $page-1;
               echo '<a href="?p=logs&s=voteshop&page='.$prev.'" title="Previous">Previous</a> &nbsp;';
            }
            for($x=1; $x<=$pages; $x++)
            {
                if($page == $x)
                   echo '<a href="?p=logs&s=voteshop&page='.$x.'" title="Page '.$x.'"><b>'.$x.'</b></a> ';
                else
                   echo '<a href="?p=logs&s=voteshop&page='.$x.'" title="Page '.$x.'">'.$x.'</a> ';
            }
            if($page<$x - 1)
            {
               $next = $page+1;
               echo '&nbsp; <a href="?p=logs&s=voteshop&page='.$next.'" title="Next">Next</a> &nbsp; &nbsp;';
            }
        }
        echo "</div>";
    }