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
    echo '<div class="box_right_title">'. $page->titleLink() .' &raquo; Browse</div>';
    $server = new server;
    $account = new account;
    $per_page = 20;
    $sql = $server->sqli();
    $pages_query = $sql->query("SELECT COUNT(*) AS count FROM payments_log");
    $page_result = mysqli_fetch_array($pages_query);
    $pages = ceil($page_result['count'] / $per_page );

    if($page_result <= 0)
       echo "Seems like the donation log was empty!";
    else
    {
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $per_page;
    ?>
    <table class="center">
           <tr><th>Date</th><th>User</th><th>Email</th><th>Amount</th><th>Status</th></tr>
           <?php
                $server->SelectDB('webdb');
                $result = $sql->query("SELECT * FROM payments_log ORDER BY userid DESC LIMIT ".$start.",".$per_page);
                while($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><?php echo $row['datecreation']; ?></td>
                        <td><?php echo $account->getAccName($row['userid']); ?></td>
                        <td><?php echo $row['buyer_email']; ?></td>
                        <td><?php echo $row['mc_gross']; ?></td>
                        <td><?php echo $row['paymentstatus']; ?></td>
                    </tr>
                <?php }
           ?>
        </table>
        <hr/>
        <?php
        if($pages>=1 && $page <= $pages)
        {
            if($page>1)
            {
               $prev = $page-1;
               echo '<a href="?p=donations&s=browse&page='.$prev.'" title="Previous">Previous</a> &nbsp;';
            }
            for($x=1; $x<=$pages; $x++)
            {
                if($page == $x)
                   echo '<a href="?p=donations&s=browse&page='.$x.'" title="Page '.$x.'"><b>'.$x.'</b></a> ';
                else
                   echo '<a href="?p=donations&s=browse&page='.$x.'" title="Page '.$x.'">'.$x.'</a> ';
            }
            if($page<$x - 1)
            {
               $next = $page+1;
               echo '&nbsp; <a href="?p=donations&s=browse&page='.$next.'" title="Next">Next</a> &nbsp; &nbsp;';
            }
        }
    }
