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
 
define('INIT_SITE', true);
include('../../includes/misc/headers.php');
include('../../includes/configuration.php');
include('../functions.php');
$server = new server;
$account = new account;
$server->SelectDB('webdb');

if($_POST['action'] == "payments")
{
		$result = $sql->query("SELECT paymentstatus,mc_gross,datecreation FROM payments_log WHERE userid='".(int)$_POST['id']."'");
		if(mysqli_num_rows($result) <= 0)
			echo "<b color='red'>No payments was found for this account.</b>";
		else 
		{
		?> <table width="100%">
               <tr>
                   <th>Amount</th>
                   <th>Payment Status</th>
                   <th>Date</th>
               </tr>
           <?php
		while($row = mysqli_fetch_assoc($result))
		{ ?>
			<tr>
                 <td><?php echo $row['mc_gross'];?>$</td>
                 <td><?php echo $row['paymentstatus']; ?></td>
                 <td><?php echo $row['datecreation']; ?></td>   
            </tr>
		<?php }
		echo '</table>';
		}
	}

elseif($_POST['action'] == 'dshop')
{
		$result = $sql->query("SELECT entry,char_id,date,amount,realm_id FROM shoplog WHERE account='".(int)$_POST['id']."' AND shop='donate'");
		if(mysqli_num_rows($result) == 0)
			echo "<b color='red'>No logs was found for this account.</b>";
		else 
		{
		?> <table width="100%">
               <tr>
                   <th>Item</th>
                   <th>Character</th>
                   <th>Date</th>
                   <th>Amount</th>
               </tr>
           <?php
		while($row = mysqli_fetch_assoc($result)) { ?>
			<tr>
                 <td><a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
				 	 <?php echo $server->getItemName($row['entry']);?></a></td>
                 <td><?php echo $account->getCharName($row['char_id'],$row['realm_id']); ?></td>
                 <td><?php echo $row['date']; ?></td>   
                 <td>x<?php echo $row['amount']; ?></td>
            </tr>
		<?php }
		echo '</table>';
		}
	}

elseif($_POST['action'] == 'vshop')
{
		$result = $sql->query("SELECT entry,char_id,realm_id,date,amount FROM shoplog WHERE account='".(int)$_POST['id']."' AND shop='vote'");
		if(mysqli_num_rows($result) == 0)
			echo "<b color='red'>No logs was found for this account.</b>";
		else 
		{
		?> <table width="100%">
               <tr>
              	 <th>Item</th>
                 <th>Character</th>
                 <th>Date</th>
                 <th>Amount</th>
               </tr>
           <?php
		while($row = mysqli_fetch_assoc($result)) { ?>
			<tr>
                 <td><a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
				 	 <?php echo $server->getItemName($row['entry']);?></a></td>
                 <td><?php echo $account->getCharName($row['char_id'],$row['realm_id']); ?></td>
                 <td><?php echo $row['date']; ?></td>
                 <td>x<?php echo $row['amount']; ?></td>   
            </tr>
		<?php }
		echo '</table>';
		}
}

elseif($_POST['action'] == "search")
{
	$input = addslashes($_POST['input']);
	$shop = addslashes($_POST['shop']);
	?>
    <table width="100%">
    <tr>
        <th>User</th>
        <th>Character</th>
        <th>Realm</th>
        <th>Item</th>
        <th>Date</th>
        <th>Amount</th>
    </tr>
	
	<?php 

	$loopRealms = $sql->query("SELECT id FROM realms");
	while($row = mysqli_fetch_assoc($loopRealms))
	{
		   $server->connectToRealmDB($row['id']);
		   $result = $sql->query("SELECT guid FROM characters WHERE name LIKE '%".$input."%'");
		   if(mysqli_num_rows($result) > 0) {
		   $row = mysqli_fetch_assoc($result);
		   $server->SelectDB('webdb');
		   $result = $sql->query("SELECT * FROM shoplog WHERE shop='".$shop."' AND char_id='".$row['guid']."'");
           
            while($row = mysqli_fetch_assoc($result))
            { ?>
                <tr class="center">
                <td><?php echo $account->getAccName($row['account']); ?></td>
                <td><?php echo $account->getCharName($row['char_id'],$row['realm_id']); ?></td>
                <td><?php echo $server->getRealmName($row['realm_id']); ?></td>
                <td><a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                <?php echo $server->getItemName($row['entry']); ?></a></td>
                <td><?php echo $row['date']; ?></td>
                <td>x<?php echo $row['amount']; ?></td>
                </tr>
		    <?php
            }
           }
    }

	       $server->SelectDB('logondb');
		   $result = $sql->query("SELECT id FROM account WHERE username LIKE '%".$input."%'");
		   if(mysqli_num_rows($result) > 0) {
		   $row = mysqli_fetch_assoc($result);
		   $server->SelectDB('webdb');
		   $result = $sql->query("SELECT * FROM shoplog WHERE shop='".$shop."' AND account='".$row['id']."'");
           
            while($row = mysqli_fetch_assoc($result))
            { ?>
                <tr class="center">
                <td><?php echo $account->getAccName($row['account']); ?></td>
                <td><?php echo $account->getCharName($row['char_id'],$row['realm_id']); ?></td>
                <td><?php echo $server->getRealmName($row['realm_id']); ?></td>
                <td><a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                <?php echo $server->getItemName($row['entry']); ?></a></td>
                <td><?php echo $row['date']; ?></td>
                <td>x<?php echo $row['amount']; ?></td>
                </tr>
		    <?php
            }
           }

	       $server->SelectDB('worlddb');
		   $result = $sql->query("SELECT entry FROM item_template WHERE name LIKE '%".$input."%'");
		   if(mysqli_num_rows($result) > 0) {
		   $row = mysqli_fetch_assoc($result);
		   $server->SelectDB('webdb');
		   $result = $sql->query("SELECT * FROM shoplog WHERE shop='".$shop."' AND entry='".$row['entry']."'");
           
            while($row = mysqli_fetch_assoc($result))
            { ?>
                <tr class="center">
                <td><?php echo $account->getAccName($row['account']); ?></td>
                <td><?php echo $account->getCharName($row['char_id'],$row['realm_id']); ?></td>
                <td><?php echo $server->getRealmName($row['realm_id']); ?></td>
                <td><a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                <?php echo $server->getItemName($row['entry']); ?></a></td>
                <td><?php echo $row['date']; ?></td>
                <td>x<?php echo $row['amount']; ?></td>
                </tr>
		    <?php
            }
           }

			$server->SelectDB('webdb');
		    $result = $sql->query("SELECT * FROM shoplog WHERE shop='".$shop."' AND date LIKE '%".$input."%'");
            while($row = mysqli_fetch_assoc($result))
            { ?>
                <tr class="center">
                <td><?php echo $account->getAccName($row['account']); ?></td>
                <td><?php echo $account->getCharName($row['char_id'],$row['realm_id']); ?></td>
                <td><?php echo $server->getRealmName($row['realm_id']); ?></td>
                <td><a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                <?php echo $server->getItemName($row['entry']); ?></a></td>
                <td><?php echo $row['date']; ?></td>
                <td>x<?php echo $row['amount']; ?></td>
                </tr>
        <?php
        }
        if($input == "Search...")
        {
            $server->SelectDB('webdb');
            $result = $sql->query("SELECT * FROM shoplog WHERE shop='".$shop."' ORDER BY id DESC LIMIT 10");
            while($row = mysqli_fetch_assoc($result))
            { ?>
                <tr class="center">
                <td><?php echo $account->getAccName($row['account']); ?></td>
                <td><?php echo $account->getCharName($row['char_id'],$row['realm_id']); ?></td>
                <td><?php echo $server->getRealmName($row['realm_id']); ?></td>
                <td><a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                <?php echo $server->getItemName($row['entry']); ?></a></td>
                <td><?php echo $row['date']; ?></td>
                <td>x<?php echo $row['amount']; ?></td>
                </tr>
            <?php
            }
        }
        echo "</table>";
}