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

    require('../ext_scripts_class_loader.php');
    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
    if(isset($_POST['element']) &&$_POST['element'] == 'vote')
        echo 'Vote Points: '.account::loadVP($_POST['account']);
    elseif(isset($_POST['element']) && $_POST['element']=='donate')
        echo $GLOBALS['donation']['coins_name'].': '.account::loadDP($_POST['account']);

    if(isset($_POST['action']) && $_POST['action'] == 'removeComment')
    {
        connect::selectDB('webdb');
        $sql->query("DELETE FROM news_comments WHERE id='".(int)$_POST['id']."'");
    }

    if(isset($_POST['action']) && $_POST['action'] == 'getComment')
    {
        connect::selectDB('webdb');
        $result = $sql->query("SELECT `text` FROM news_comments WHERE id='".(int)$_POST['id']."'");
        $row = mysqli_fetch_assoc($result);
        echo $row['text'];
    }

    if(isset($_POST['action']) && $_POST['action'] == 'editComment')
    {
        $content = addslashes(trim(htmlentities($_POST['content'])));
        connect::selectDB('webdb');
        $sql->query("UPDATE news_comments SET `text` = '".$content."' WHERE id='".(int)$_POST['id']."'");
        $sql->query("INSERT INTO admin_log (full_url, ip, timestamp, action, account, extended_inf)
        VALUES('/index.php?page=news','".$_SERVER['REMOTE_ADDR']."', '".time()."', 'Edited a news comment', '".$_SESSION['cw_user_id']."',
        'Edited news comment with comment ID: ".(int)$_POST['id']."')");
    }

    if(isset($_POST['getTos']))
    {
        include("../../documents/termsofservice.php");
        echo $tos_message;
    }

    if(isset($_POST['getRefundPolicy']))
    {
        include("../../documents/refundpolicy.php");
        echo $rp_message;
    }

    if(isset($_POST['serverStatus']))
    {
        echo '<div class="box_one_title">Server status</div>';
        $num = 0;
        foreach ($GLOBALS['realms'] as $k => $v)
        {
            if ($num != 0) { echo "<hr/>"; }
            server::serverStatus($k);
            $num++;
        }
        if ($num == 0)
        {
            ////buildError("<b>No realms found: </b> Please setup your database and add your realm(s)!",NULL);
            echo "No realms found.";
        }
        unset($num);
        ?>
        <hr/>
        <span id="realmlist">set realmlist <?php echo $GLOBALS['connection']['realmlist']; ?></span>
        </div>
        <?php
    }

    if(isset($_POST['convertDonationList']))
    {
        for ($row = 0; $row < count($GLOBALS['donationList']); $row++)
        {
            $value = (int)$_POST['convertDonationList'];
            if($value == $GLOBALS['donationList'][$row][1])
            {
                echo $GLOBALS['donationList'][$row][2];
                exit();
            }
        }
    }