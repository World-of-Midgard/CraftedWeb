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
    <div class="box_right_title"><?php echo $page->titleLink(); ?> &raquo; Character Inventory</div>
    Showing inventory of character
    <a href="?p=users&s=viewchar&guid=<?php echo $_GET['guid']; ?>&rid=<?php echo $_GET['rid']; ?>">
        <?php echo $account->getCharName($_GET['guid'],$_GET['rid']); ?>
    </a>
    <hr/>
    Filter:
           <a href="?p=users&s=inventory&guid=<?php echo $_GET['guid']; ?>&rid=<?php echo $_GET['rid']; ?>&f=equip">
            <?php if(isset($_GET['f']) && $_GET['f']=='equip') echo '<b>'; ?>Equipped Items</a><?php if(isset($_GET['f']) && $_GET['f']=='equip') echo '</b>'; ?>
        &nbsp; |
    &nbsp; <a href="?p=users&s=inventory&guid=<?php echo $_GET['guid']; ?>&rid=<?php echo $_GET['rid']; ?>&f=bank">
            <?php if(isset($_GET['f']) && $_GET['f']=='bank') echo '<b>'; ?>Items in bank<?php if(isset($_GET['f']) && $_GET['f']=='bank') echo '</b>'; ?></a>
        &nbsp; |
    &nbsp; <a href="?p=users&s=inventory&guid=<?php echo $_GET['guid']; ?>&rid=<?php echo $_GET['rid']; ?>&f=keyring">
            <?php if(isset($_GET['f']) && $_GET['f']=='keyring') echo '<b>'; ?>Items in keyring<?php if(isset($_GET['f']) && $_GET['f']=='keyring') echo '</b>'; ?>
            </a>
         &nbsp; |
    &nbsp; <a href="?p=users&s=inventory&guid=<?php echo $_GET['guid']; ?>&rid=<?php echo $_GET['rid']; ?>&f=currency">
            <?php if(isset($_GET['f']) && $_GET['f']=='currency') echo '<b>'; ?>Currencies<?php if(isset($_GET['f']) && $_GET['f']=='currency') echo '</b>'; ?></a>
         &nbsp; |
    &nbsp; <a href="?p=users&s=inventory&guid=<?php echo $_GET['guid']; ?>&rid=<?php echo $_GET['rid']; ?>">
            <?php if(!isset($_GET['f'])) echo '<b>'; ?>All Items<?php if(!isset($_GET['f'])) echo '</b>'; ?></a>
    <p/>
    <?php
    $server->connectToRealmDB($_GET['rid']);
    $equip_array = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18);

    $result = $sql->query("SELECT guid,itemEntry,`count` FROM item_instance WHERE owner_guid='".(int)$_GET['guid']."'");
    if(!$result)
        echo 'No items was found!';
    else
    {
     echo '<table cellspacing="3" cellpadding="5">';
     while($row = mysqli_fetch_assoc($result))
     {
        $entry = $row['itemEntry'];

        if(isset($_GET['f']))
        {
            if($_GET['f'] == 'equip')
                $getPos = $sql->query("SELECT slot,bag FROM character_inventory WHERE item='".$row['guid']."' AND bag='0' AND slot IN(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18) AND guid='".(int)$_GET['guid']."'");
            elseif($_GET['f'] == 'bank')
                $getPos = $sql->query("SELECT slot,bag FROM character_inventory WHERE item='".$row['guid']."' AND slot>=39 AND slot<=73");
            elseif($_GET['f'] == 'keyring')
                $getPos = $sql->query("SELECT slot,bag FROM character_inventory WHERE item='".$row['guid']."' AND slot>=86 AND slot<=117");
            elseif($_GET['f'] == 'currency')
                $getPos = $sql->query("SELECT slot,bag FROM character_inventory WHERE item='".$row['guid']."' AND slot>=118 AND slot<=135");
        }
        else
            $getPos = $sql->query("SELECT slot,bag FROM character_inventory WHERE item='".$row['guid']."'");

        if($getPos)
        {
            $pos = mysqli_fetch_assoc($getPos);
            $server->SelectDB('worlddb');
            $get = $sql->query("SELECT name,entry,quality,displayid FROM item_template WHERE entry='".$entry."'");
            $r = mysqli_fetch_assoc($get);
             $server->SelectDB('webdb');
             $getIcon = $sql->query("SELECT icon FROM item_icons WHERE displayid='".$r['displayid']."'");
             if(mysqli_num_rows($getIcon) == 0)
             {
                $sxml = new SimpleXmlElement(file_get_contents('http://www.wowhead.com/item='.$entry.'&xml'));
                $icon = strtolower(addslashes($sxml->item->icon));
                $sql->query("INSERT INTO item_icons VALUES('".$row['displayid']."','".$icon."')");
             }
             else
             {
               $iconrow = mysqli_fetch_assoc($getIcon);
               $icon = strtolower($iconrow['icon']);
             }
            $server->connectToRealmDB($_GET['rid']);
            ?>
                <tr bgcolor="#e9e9e9">
                    <td width="36"><img src="http://static.wowhead.com/images/wow/icons/medium/<?php echo $icon; ?>.jpg"></td>
                    <td>
                        <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $r['entry']; ?>" title="" target="_blank"><?php echo $r['name']; ?></a>
                    </td>
                    <td>x<?php echo $row['count']; ?>

                    <?php
                    if(!isset($_GET['f']))
                    {
                        if(in_array($pos['slot'], $equip_array) && $pos['bag']==0) echo '(Equipped)';
                        if($pos['slot']>= 39 && $pos['slot'] <= 73) echo '(Bank)';
                        if($pos['slot']>= 86 && $pos['slot'] <= 117) echo '(Keyring)';
                        if($pos['slot']>= 118 && $pos['slot'] <= 135) echo '(Currency)';
                    }
                    ?>
                </td>
            </tr>
        <?php
        }
     }
     echo '</table>';
    }