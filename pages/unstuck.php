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

    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
    echo "<div class='box_two_title'>Character Unstuck</div>
    Choose the character you wish to unstuck. The character will be teleported to your character's home location.<hr/>";
    $service = "unstuck";
    if($GLOBALS['service'][$service]['price'] == 0)
        echo '<span class="attention">Unstuck is free of charge.</span>';
    else
    {
        echo '<span class="attention">Unstuck costs';
        echo $GLOBALS['service'][$service]['price'].' '.website::convertCurrency($GLOBALS['service'][$service]['currency']);
        echo '</span>';
        if($GLOBALS['service'][$service]['currency'] == "vp")
            echo "<span class='currency'>Vote Points: ".account::loadVP($_SESSION['cw_user'])."</span>";
        elseif($GLOBALS['service'][$service]['currency'] == "dp")
            echo "<span class='currency'>".$GLOBALS['donation']['coins_name'].": ".account::loadDP($_SESSION['cw_user'])."</span>";
    }

    account::isNotLoggedIn();
    $sql->select_db($GLOBALS['connection']['webdb']);
    $num = 0;
    $result = $sql->query('SELECT * FROM realms ORDER BY id ASC');
    if (!$result)
        echo "query didnt work";

    while($row = mysqli_fetch_array($result))
    {
        $realm = $row['name'];
        $char_db = $row['char_db'];
        $acct_id = account::getAccountID($_SESSION['cw_user']);
        $sql->select_db($char_db);
        $results = $sql->query('SELECT * FROM characters WHERE account='.$acct_id);
        while($row = mysqli_fetch_assoc($results))
        {
            ?><div class='charBox'>
            <table width="100%">
            <tr>
            <td width="73">
            <?php
            if(!file_exists('styles/global/images/portraits/'.$row['gender'].'-'.$row['race'].'-'.$row['class'].'.gif'))
                echo '<img src="styles/'.$GLOBALS['template']['path'].'/images/unknown.png" />';
            else
                echo "<img src='styles/global/images/portraits/".$row['gender']."-".$row['race']."-".$row['class'].".gif' border='none'>";
                ?>
            </td>

            <td width="160"><h3><?php echo $row['name']; ?></h3>
            <?php echo $row['level']." ".character::getRace($row['race'])." ".character::getGender($row['gender']).
            " ".character::getClass($row['class']); ?>
            </td>

            <td>Realm: <?php echo $realm; ?>
            <?php if($row['online'] == 1)
            echo "<br/><span class='red_text'>Please log out before trying to unstuck.</span>";?>
            </td>

            <td align="right"> &nbsp; <input type="submit" value="Unstuck"
            <?php if($row['online'] == 0) { ?>
            onclick='unstuck(<?php echo $row['guid']; ?>,"<?php echo $char_db; ?>")' <?php }
            else { echo 'disabled="disabled"'; } ?>>
            </td>
            </tr>
            </table>
            </div> <?php
            $num++;
        }
    }