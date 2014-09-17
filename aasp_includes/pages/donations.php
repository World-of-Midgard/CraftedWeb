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
	 
	 $page->validatePageAccess('Donations');
	 
     if($page->validateSubPage())
		 $page->outputSubPage();
     else
     {
		$donationsTotal = $sql->query("SELECT mc_gross FROM payments_log");
		$donationsTotalAmount = 0;
		while($row = mysqli_fetch_assoc($donationsTotal))
		{
			$donationsTotalAmount = $donationsTotalAmount + $row['mc_gross'];
		}
		
		$donationsThisMonth = $sql->query("SELECT mc_gross FROM payments_log WHERE paymentdate LIKE '%".date('Y-md')."%'");
		$donationsThisMonthAmount = 0;
		while($row = mysqli_fetch_assoc($donationsThisMonth))
		{
			$donationsThisMonthAmount = $donationsThisMonthAmount + $row['mc_gross'];
		}
		
		$q = $sql->query("SELECT mc_gross,userid FROM payments_log ORDER BY paymentdate DESC LIMIT 1");
		$row = mysqli_fetch_assoc($q);
		$donationLatestAmount = $row['mc_gross'];
		
		$donationLatest = $account->getAccName($row['userid']);
?>
<div class="box_right_title">Donations Overview</div>
<table style="width: 100%;">
<tr>
<td><span class='blue_text'>Total donations</span></td><td><?php echo mysqli_num_rows($donationsTotal); ?></td>
<td><span class='blue_text'>Total donation amount</span></td><td><?php echo round($donationsTotalAmount,0); ?>$</td>
</tr>
<tr>
    <td><span class='blue_text'>Donations this month</span></td><td><?php echo mysqli_num_rows($donationsThisMonth); ?></td>
    <td><span class='blue_text'>Donation amount this month</span></td><td><?php echo round($donationsThisMonthAmount,0); ?>$</td>
</tr>
<tr>
    <td><span class='blue_text'>Latest donation amount</span></td><td><?php echo round($donationLatestAmount); ?>$</td>
    <td><span class='blue_text'>Latest donator</span></td><td><?php echo $donationLatest; ?></td>
</tr>
</table>
<hr/>
<a href="?p=donations&s=browse" class="content_hider">Browse Donations</a>
<?php }