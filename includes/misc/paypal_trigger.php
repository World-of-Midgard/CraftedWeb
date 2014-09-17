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
require('../configuration.php');
require('connect.php');
$sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
$send = 'cmd=_notify-validate';
foreach ($_POST as $key => $value)
{
    if(get_magic_quotes_gpc() == 1)
        $value = urlencode(stripslashes($value));
    else
        $value = urlencode($value);
    $send .= "&$key=$value";
}

$head .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$head .= "Content-Type: application/x-www-form-urlencoded\r\n";
$head .= 'Content-Length: '.strlen($send)."\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
connect::selectDB('webdb');
if ($fp !== false)
{
    fwrite($fp, $head.$send);
    $resp = stream_get_contents($fp);
    $resp = end(explode("\n", $resp));
    $item_number = addslashes($_POST['item_number']);
    $item_name = addslashes($item_number['0']);
    $mc_gross = addslashes($_POST['mc_gross']);
    $txn_id = addslashes($_POST['txn_id']);
    $payment_date = addslashes($_POST['payment_date']);
    $first_name = addslashes($_POST['first_name']);
    $last_name = addslashes($_POST['last_name']);
    $payment_type = addslashes($_POST['payment_type']);
    $payer_email = addslashes($_POST['payer_email']);
    $address_city = addslashes($_POST['address_city']);
    $address_country = addslashes($_POST['address_country']);
    $custom = addslashes($_POST['custom']);
    $mc_fee = addslashes($_POST['mc_fee']);
    $fecha = date("Y-m-d");
    $payment_status = addslashes($_POST['payment_status']);
    $reciever = addslashes($_POST['receiver_email']);

    if ($resp == 'VERIFIED')
    {
        if ($reciever!=$GLOBALS['donation']['paypal_email'])
            exit();
        $sql->query("INSERT INTO payments_log(userid,paymentstatus,buyer_email,firstname,lastname,city,country,mc_gross,mc_fee,itemname,paymenttype,
        paymentdate,txnid,pendingreason,reasoncode,datecreation) values ('".$custom."','".$payment_status."','".$payer_email."',
        '".$first_name."','".$last_name."','".$address_city."','".$address_country."','".$mc_gross."',
        '".$mc_fee."','".$item_name."','".$payment_type."','".$payment_date."','".$txn_id."','".$pending_reason."',
        '".$reason_code."','".$fecha."')");

        $to = $payer_email;
        $subject = $GLOBALS['donation']['emailResponse'];
        $message = 'Hello '.$first_name.'
        We would like to inform you that the recent payment you did was successfull.

        If you require further assistance, please contact us via the forums.
        ------------------------------------------
        Payment email: '.$payer_email.'
        Payment amount: '.$mc_gross.'
        Buyer name: '.$first_name.' '.$last_name.'
        Payment date: '.$payment_date.'
        Account ID: '.$custom.'
        ------------------------------------------
        This payment is saved in our logs.

        Thank you, Management.
        ';
        $headers = 'From: '.$GLOBALS['default_email'].'' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        if ($GLOBALS['donation']['emailResponse']==true)
        {
            mail($to, $subject, $message, $headers);
            if ($GLOBALS['donation']['sendResponseCopy']==true)
            mail($GLOBALS['donation']['copyTo'], $subject, $message, $headers);
        }

        $res = fgets ($fp, 1024);
        if($payment_status=="Completed")
        {
            $sql->query("INSERT INTO payments_log(userid,paymentstatus,buyer_email,firstname,lastname,mc_gross,paymentdate,datecreation)
            ('".$custom."','".$mc_gross."','".$payer_email."','".$first_name."','".$last_name."','".$mc_gross."','".$payment_date."','".$fecha."')");
            if($GLOBALS['donation']['donationType'] == 2)
            {
                for ($row = 0; $row < count($GLOBALS['donationList']); $row++)
                {
                    $coins = $mc_gross;
                    if($coins == $GLOBALS['donationList'][$row][2])
                        $sql->query("UPDATE account_data SET dp=dp + ".$GLOBALS['donationList'][$row][1]." WHERE id='".$custom."'");
                }
            }
            elseif($GLOBALS['donation']['donationType'] == 1)
            {
                $coins = ceil($mc_gross);
                $sql->query("UPDATE account_data SET dp=dp + ".$coins." WHERE id='".$custom."'");
            }
        }
    }
    else if ($resp == 'INVALID')
    {
        mail($GLOBALS['donation']['copyTo'],"INVALID Donation","A payment was invalid. Information is shown below: <br/>
        User ID : ".$custom."
        Buyer Email: ".$payer_email."
        Amount: ".$mc_gross." USD
        Date: ".$payment_date."
        First name: ".$first_name."
        Last name: ".$last_name."
        ","From: ".$GLOBALS['donation']['responseFrom']."");
        mail($payer_email, "INVALID Donation", "Hello there. Unfortunately, the latest payment you made was invalid. Please contact us for more information. Best regards, Management");
        $sql->query("INSERT INTO payments_log(userid,paymentstatus,buyer_email,firstname,lastname,mc_gross,paymentdate,datecreation)
        VALUES ('".$custom."','".$payment_status." - INVALID','".$payer_email."','".$first_name."','".$last_name."','".$mc_gross."','".$payment_date."','".$fecha."')");
    }
}
fclose ($fp);