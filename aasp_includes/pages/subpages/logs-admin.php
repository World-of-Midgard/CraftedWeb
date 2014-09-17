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
$sql = $server->sqli();
$per_page = 20;
$server->SelectDB('webdb');
$pages_query = $sql->query("SELECT COUNT(*) AS count FROM admin_log");
if (!$pages_query)
    echo "fail";
$results = mysqli_fetch_assoc($pages_query);
$pages = ceil($results['count'] / $per_page );
$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

if(isset($_SESSION['cw_staff']) && !isset($_SESSION['cw_admin']))
{
	if($_SESSION['cw_staff_level'] < $GLOBALS['adminPanel_minlvl'])
		exit('Hey! You shouldn\'t be here!');
}
?>
<div class="box_right_title">Admin log</div>
<table class="center">
       <tr><th>Date</th><th>User</th><th>Action</th><th>IP</th></tr>
       <?php
            $server->SelectDB('webdb');
            $result = $sql->query("SELECT * FROM admin_log ORDER BY id DESC LIMIT ".$start.",".$per_page);
            if (!$result)
               echo "fail";
            while($row = mysqli_fetch_assoc($result))
            { ?>
                <tr>
                    <td><?php echo date("Y-m-d H:i:s",$row['timestamp']); ?></td>
                    <td><?php echo $account->getAccName($row['account']); ?></td>
                    <td><?php echo $row['action']; ?></td>
                    <td><?php echo $row['ip']; ?></td>
                </tr>
      <?php } ?>
</table>
<hr/>
<?php
    if($pages >= 1 && $page <= $pages)
    {
        if($page > 1)
        {
           $prev = $page-1;
           echo '<a href="?p=logs&s=admin&page='.$prev.'" title="Previous">Previous</a> &nbsp;';
        }
        for($x = 1; $x <= $pages; $x++)
        {
            if($page == $x)
               echo '<a href="?p=logs&s=admin&page='.$x.'" title="Page '.$x.'"><b>'.$x.'</b></a> ';
            else
               echo '<a href="?p=logs&s=admin&page='.$x.'" title="Page '.$x.'">'.$x.'</a> ';
        }
        if($page < $x - 1)
        {
           $next = $page+1;
           echo '&nbsp; <a href="?p=logs&s=admin&page='.$next.'" title="Next">Next</a> &nbsp; &nbsp;';
        }
    }
