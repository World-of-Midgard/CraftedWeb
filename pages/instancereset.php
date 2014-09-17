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
    echo "<div class='box_two_title'>Instance Reset</div>Let's you reset the instance on your characters.<hr/>";
    account::isNotLoggedIn();
    $service = "reset";
    if($GLOBALS['service'][$service]['price'] == 0)
        echo '<span class="attention">Instance Reset is free of charge.</span>';
    else
    {
        echo '<span class="attention">Instance Reset costs';
        echo $GLOBALS['service'][$service]['price'].' '.website::convertCurrency($GLOBALS['service'][$service]['currency']);
        echo '</span>';
        if($GLOBALS['service'][$service]['currency'] == "vp")
            echo "<span class='currency'>Vote Points: ".account::loadVP($_SESSION['cw_user'])."</span>";
        elseif($GLOBALS['service'][$service]['currency'] == "dp")
            echo "<span class='currency'>".$GLOBALS['donation']['coins_name'].": ".account::loadDP($_SESSION['cw_user'])."</span>";
    }

    if (isset($_POST['ir_step1']) || isset($_POST['ir_step2']))
        echo 'Selected realm: <b>'.server::getRealmName($_POST['ir_realm']).'</b><br/><br/>';
    else
    {
        ?>
        Select realm:
        &nbsp;
        <form action="?p=instancereset" method="post">
        <table>
            <tr>
            <td>
            <select name="ir_realm">
            <?php
            $result = $sql->query("SELECT name,char_db FROM realms");
            while($row = mysqli_fetch_assoc($result))
            {
                if(isset($_POST['ir_realm']) && $_POST['ir_realm'] == $row['char_db'])
                    echo '<option value="'.$row['char_db'].'" selected>';
                else
                    echo '<option value="'.$row['char_db'].'">';
                echo $row['name'].'</option>';
            }
            ?>
            </select>
            </td>
            <td>
            <?php
            if(!isset($_POST['ir_step1']) && !isset($_POST['ir_step2']) && !isset($_POST['ir_step3']))
                echo '<input type="submit" value="Continue" name="ir_step1">';
            ?>
            </td>
            </tr>
        </table>
        </form>
        <?php
    }
    if(isset($_POST['ir_step1']) || isset($_POST['ir_step2']) || isset($_POST['ir_step3']))
    {
        if (isset($_POST['ir_step2']))
            echo 'Selected character: <b>'.character::getCharName($_POST['ir_char'],server::getRealmId($_POST['ir_realm'])) .'</b><br/><br/>';
        else
        {
            ?>
            Select character:
            &nbsp;
            <form action="?p=instancereset" method="post">
            <table>
                <tr>
                <td>
                <input type="hidden" name="ir_realm" value="<?php echo $_POST['ir_realm']; ?>">
                <select name="ir_char">
                <?php
                $acc_id = account::getAccountID($_SESSION['username']);
                connect::selectDB($_POST['ir_realm']);
                $result = $sql->query("SELECT name,guid FROM characters WHERE account='".$acc_id."'");

                while($row = mysqli_fetch_assoc($result))
                {
                    if(isset($_POST['ir_char']) && $_POST['ir_char'] == $row['guid'])
                    echo '<option value="'.$row['guid'].'" selected>';
                    else
                    echo '<option value="'.$row['guid'].'">';
                    echo $row['name'].'</option>';
                }
                ?>
                </select>
                </td>
                <td>
                <?php
                if(!isset($_POST['ir_step2']) && !isset($_POST['ir_step3']))
                    echo '<input type="submit" value="Continue" name="ir_step2">';
                ?>
                </td>
                </tr>
            </table>
            </form>
            <?php
        }
    }
    if(isset($_POST['ir_step2']) || isset($_POST['ir_step3']))
    {
        ?>
        Select instance:
        &nbsp;
        <form action="?p=instancereset" method="post">
        <table>
        <tr>
        <td>
        <input type="hidden" name="ir_realm" value="<?php echo $_POST['ir_realm']; ?>">
        <input type="hidden" name="ir_char" value="<?php echo $_POST['ir_char']; ?>">
        <select name="ir_instance">
        <?php
        $guid = (int)$_POST['ir_char'];
        connect::selectDB($_POST['ir_realm']);

        $result = $sql->query("SELECT instance FROM character_instance WHERE guid='".$guid."' AND permanent=1");
        if (!$result)
        {
            echo "<option value='#'>No instance locks were found</option>";
            $nope = true;
        }
        else
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $getI = $sql->query("SELECT id, map, difficulty FROM instance WHERE id='".$row['instance']."'");
                $instance = mysqli_fetch_assoc($getI);
                connect::selectDB('webdb');
                $getName = $sql->query("SELECT name FROM instance_data WHERE map='".$instance['map']."'");
                $name = mysqli_fetch_assoc($getName);

                if(empty($name['name']))
                    $name = "Unknown Instance";
                else
                    $name = $name['name'];

                if ($instance['difficulty']==0)
                    $difficulty = "10-man Normal";
                elseif($instance['difficulty']==1)
                    $difficulty = "25-man Normal";
                elseif($instance['difficulty']==2)
                    $difficulty = "10-man Heroic";
                elseif($instance['difficulty']==3)
                    $difficulty = "25-man Heroic";

                echo '<option value="'.$instance['id'].'">'.$name.' <i>('.$difficulty.')</i></option>';
            }
        }
        ?>
        </select>
        </td>
        <td>
        <?php
        if(!isset($_POST['ir_step1']) && !isset($nope))
            echo '<input type="submit" value="Reset Instance" name="ir_step3">';
        ?>
        </td>
        </tr>
        </table>
        </form>
        <?php
    }

    if(isset($_POST['ir_step3']))
    {
        $guid = (int)$_POST['ir_char'];
        $instance = (int)$_POST['ir_instance'];

        if($GLOBALS['service'][$service]['currency'] == "vp")
        if(account::hasVP($_SESSION['cw_user'],$GLOBALS['service'][$service]['price']) == true)
            echo '<span class="alert">You do not have enough Vote Points!';
        else
        {
            connect::selectDB($_POST['ir_realm']);
            $sql->query("DELETE FROM instance WHERE id='".$instance."'");
            account::deductVP(account::getAccountID($_SESSION['cw_user']),$GLOBALS['service'][$service]['price']);
            echo '<span class="approved">The instance lock was removed!</span>';
        }
        elseif($GLOBALS['service'][$service]['currency'] == "dp")
        {
            if(account::hasDP($_SESSION['cw_user'],$GLOBALS['service'][$service]['price']) == true)
                echo '<span class="alert">You do not have enough '.$GLOBALS['donation']['coins_name'];
            else
            {
                connect::selectDB($_POST['ir_realm']);
                $sql->query("DELETE FROM instance WHERE id='".$instance."'");
                account::deductDP(account::getAccountID($_SESSION['cw_user']),$GLOBALS['service'][$service]['price']);
                echo '<span class="approved">The instance lock was removed!</span>';
                account::logThis("Performed an Instance reset on ".character::getCharName($guid,server::getRealmId($_POST['ir_realm'])),"instancereset",
                server::getRealmId($_POST['ir_realm']));
            }
        }
    }
    ?>
    <br/>
    <a href="?p=instancereset">Start over</a>