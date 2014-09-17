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

    session_start();
    define('INIT_SITE', true);
    require('../configuration.php');
    require('../misc/connect.php');
    require('../classes/account.php');
    require('../classes/character.php');
    require('../classes/shop.php');
    connect::connectToDB();
    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
    if($_POST['action'] == 'removeFromCart')
    {
        unset($_SESSION[$_POST['cart']][$_POST['entry']]);
        return;
    }

    if($_POST['action'] == 'addShopitem')
    {
        $entry = (int)preg_replace("/[^0-9]/", "", $_POST['entry']);
        $shop =  addslashes($_POST['shop']);

        if(isset($_SESSION[$_POST['cart']][$entry]))
            $_SESSION[$_POST['cart']][$entry]['quantity']++;
        else
        {
            connect::selectDB('webdb');
            $result = $sql->query('SELECT entry, price FROM shopitems WHERE entry="'.$entry.'" AND in_shop="'.$shop.'"');
            if(mysqli_num_rows($result) != 0)
            {
                $row = mysqli_fetch_array($result);
                $_SESSION[$_POST['cart']][$row['entry']] = array('quantity' => 1, 'price' => $row['price']);
            }
        }
        return;
    }

    if($_POST['action'] == 'clear')
    {
        unset($_SESSION['donateCart']);
        unset($_SESSION['voteCart']);
        return;
    }

    if($_POST['action'] == 'getMinicart')
    {
        $curr = ($_POST['cart'] == 'donateCart' ? $GLOBALS['donation']['coins_name'] : 'Vote Points');

        if(!isset($_SESSION[$_POST['cart']]))
        {
            echo "<b>Show Cart:</b> 0 Items (0 ".$curr.")";
            exit;
        }

        $entrys = array_keys($_SESSION[$_POST['cart']]);
        if (count($entrys) <= 0)
        {
            echo "<b>Show Cart:</b> 0 Items (0 ".$curr.")";
            exit;
        }
        $num        = 0;
        $totalPrice = 0;
        connect::selectDB('webdb');
        $shop_filt = addslashes(substr($_POST['cart'], 0, -4));
        $query = "SELECT entry, price FROM shopitems WHERE in_shop = '{$shop_filt}' AND entry IN (";
        $query .= implode(', ', $entrys);
        $query .= ")";
        if ($result = $sql->query($query))
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $item = $_SESSION[$_POST['cart']][$row['entry']];
                if ($item)
                {
                    $num = $num + $item['quantity'];
                    $totalPrice = $totalPrice + ($item['quantity'] * $row['price']);
                    unset($item);
                }
            }
        }
        echo "<b>Show Cart:</b> {$num} Items ({$totalPrice} {$curr})";
        return;
    }

    if($_POST['action'] == 'saveQuantity')
    {
        $qty = (int)preg_replace("/[^0-9]/", "", $_POST['quantity']);
        if($qty <= 0)
            unset($_SESSION[$_POST['cart']][$_POST['entry']]);
        else
            $_SESSION[$_POST['cart']][$_POST['entry']]['quantity'] = $qty;
        return;
    }

    function process_cart($cart, $charaID, $character, $accountID, $realm)
    {
        global $sql;
        if (!isset($_SESSION[$cart.'Cart']))
            return;
        $host      = $GLOBALS['realms'][$realm]['host'];
        $rank_user = $GLOBALS['realms'][$realm]['rank_user'];
        $rank_pass = $GLOBALS['realms'][$realm]['rank_pass'];
        $ra_port   = $GLOBALS['realms'][$realm]['ra_port'];
        $totalPrice = 0;
        $entries = array_keys($_SESSION[$cart.'Cart']);
        if (count($entries) > 0)
        {
            $items = array();
            $query = "SELECT entry, price FROM shopitems WHERE in_shop = '{$cart}' AND entry IN (";
            $query .= implode(', ', $entries);
            $query .= ")";
            if ($result = $sql->query($query))
            {
                while($row = mysqli_fetch_assoc($result))
                {
                    $item = $_SESSION[$cart.'Cart'][$row['entry']];
                    if ($item)
                    {
                        $item['price'] = $row['price'];
                        $item['totalPrice'] = $row['price'] * $item['quantity'];
                        $totalPrice = $totalPrice + $item['totalPrice'];
                        $items[$row['entry']] = $item;
                        unset($item);
                    }
                }
            }
            if($cart == 'donate' And account::hasDP($_SESSION['cw_user'], $totalPrice) == false)
                die("You do not have enough {$GLOBALS['donation']['coins_name']}!");
            else if($cart == 'vote' And account::hasVP($_SESSION['cw_user'], $totalPrice) == false)
                die("You do not have enough Vote Points!");

            foreach ($items as $entry => $info)
            {
                $num = $info['quantity'];
                while ($num > 0)
                {
                    $qty = $num > 12 ? 12 : $num;
                    $command = "send items ".$character." \"Your requested item\" \"Thanks for supporting us!\" ".$entry.":".$qty." ";

                    if ($error = sendRA($command, $rank_user, $rank_pass, $host, $ra_port))
                    {
                        echo 'Connection problems...Aborting | Error: '.$error;
                        exit;
                    }
                    else
                    {
                        $shop = new shop;
                        $shop->logItem($cart, $entry, $charaID, $accountID, $realm, $qty);
                        if ($cart == 'donate')
                            account::deductDP($accountID, $info['price'] * $qty);
                        else
                            account::deductVP($accountID, $info['price'] * $qty);
                        $_SESSION[$cart.'Cart'][$entry]['quantity'] -= $qty;
                    }
                    $num = $num - $qty;
                }
                unset($_SESSION[$cart.'Cart'][$entry]);
            }
        }
        unset($_SESSION[$cart.'Cart']);
    }

    if($_POST['action'] == 'checkout')
    {
        $values = explode('*', $_POST['values']);
        $realm = $values[1];
        $character = character::getCharname($values[0], $realm);
        $accountID = account::getAccountID($_SESSION['cw_user']);
        connect::selectDB('webdb');
        require('../misc/ra.php');
        process_cart('donate', $values[0], $character, $accountID, $realm);
        process_cart('vote', $values[0], $character, $accountID, $realm);
        echo true;
    }

    if($_POST['action'] == 'removeItem')
    {
        if(account::isGM($_SESSION['cw_user']) == false)
            exit;
        $entry = (int)preg_replace("/[^0-9]/", "", $_POST['entry']);
        $shop = addslashes($_POST['shop']);
        connect::selectDB('webdb');
        $sql->query("DELETE FROM shopitems WHERE entry='".$entry."' AND in_shop='".$shop."'");
        return;
    }

    if($_POST['action'] == 'editItem')
    {
        if(account::isGM($_SESSION['cw_user'])==false)
            exit();
        $entry = (int)preg_replace("/[^0-9]/", "", $_POST['entry']);
        $shop  = addslashes($_POST['shop']);
        $price = (int)preg_replace("/[^0-9]/", "", $_POST['price']);
        connect::selectDB('webdb');
        if($price >= 0)
            $sql->query("UPDATE shopitems SET price='".$price."' WHERE entry='".$entry."' AND in_shop='".$shop."'");
        return;
    }