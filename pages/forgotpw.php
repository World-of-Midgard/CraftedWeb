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
    echo "<div class='box_two_title'>Forgot Password</div>";
    account::isLoggedIn();
    if (isset($_POST['forgotpw']))
        account::forgotPW($_POST['forgot_username'],$_POST['forgot_email']);
    if(isset($_GET['code']) || isset($_GET['account']))
    {
        if (!isset($_GET['code']) || !isset($_GET['account']))
            echo "<b class='red_text'>Link error, one or more required values are missing.</b>";
        else
        {
            connect::selectDB('webdb');
            $code = addslashes($_GET['code']); $account = addslashes($_GET['account']);
            $result = $sql->query("SELECT COUNT('id') FROM password_reset WHERE code='".$code."' AND account_id='".$account."'");
            if ($result != 0)
                echo "<b class='red_text'>The values specified does not match the ones in the database.</b>";
            else
            {
                $newPass = RandomString();
                echo "<b class='yellow_text'>Your new password is: ".$newPass." <br/><br/>Please sign in and change your password.</b>";
                $sql->query("DELETE FROM password_reset WHERE account_id = '".$account."'");
                $account_name = account::getAccountName($account);
                account::changeForgottenPassword($account_name,$newPass);
                $ignoreForgotForm = true;
            }
        }
    }
    if (!isset($ignoreForgotForm))
    {
        echo 'To reset your password, please type your username & the Email address you registered with.
        An email will be sent to you, containing a link to reset your password. <br/><br/>
        <form action="?p=forgotpw" method="post">
        <table width="80%">
        <tr>
        <td align="right">Username:</td>
        <td><input type="text" name="forgot_username" /></td>
        </tr>
        <tr>
        <td align="right">Email:</td>
        <td><input type="text" name="forgot_email" /></td>
        </tr>
        <tr>
        <td></td>
        <td><hr/></td>
        </tr>
        <tr>
        <td></td>
        <td><input type="submit" value="OK!" name="forgotpw" /></td>
        </tr>
        </table>
        </form>';
    }