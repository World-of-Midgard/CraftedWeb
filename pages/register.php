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

    echo "<div class='box_two_title'>Register</div> It's free, join us today! <hr/>";
    account::isLoggedIn();
    if (isset($_POST['register']))
        $account->register($_POST['username'],$_POST['email'],$_POST['password'],$_POST['password_repeat'],$_POST['referer'],$_POST['captcha']);
    ?>
    <input type="hidden" value="<?php echo $_GET['id']; ?>" id="referer" />
    <table width="80%">
    <tr>
    <td align="right">Username:</td>
    <td><input type="text" id="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" onkeyup="checkUsername()"/>
    <br/><span id="username_check" style="display:none;"></span></td>
    </tr>
    <tr>
    <td align="right">Email:</td>
    <td><input type="text"  id="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"/></td>
    </tr>
    <tr>
    <td align="right">Password:</td>
    <td><input type="password"  id="password" /></td>
    </tr>
    <tr>
    <td align="right">Repeat password:</td>
    <td><input type="password"  id="password_repeat" /></td>
    </tr>
    <?php
    if($GLOBALS['registration']['captcha'] == true)
    {
        $_SESSION['captcha_numero']= rand(0000,9999);
        ?>
        <tr>
        <td align="right"></td>
        <td><img src="includes/misc/captcha.php" /></td>
        </tr>
        <tr>
        <td align="right">Captcha:</td>
        <td><input type="text" id="captcha" /></td>
        </tr>
    <?php }
    ?>
    <tr>
    <td></td>
    <td><hr/></td>
    </tr>

    <tr>
    <td></td>
    <td>
    <input type="submit" value="Register" onclick="register(<?php if($GLOBALS['registration']['captcha']==true)  echo 1;  else  echo 0; ?>)"
    id="register"/>
    <?php
        include("documents/termsofservice.php");
        if($tos_enable == true)
            echo '<br/>By registering, you accept our <a href="#" onclick="viewTos()">Terms of Service</a>';
    ?>
    </tr>
    </table>
    <script type="text/javascript">
        document.onkeydown = function(event)
        {
            var key_press = String.fromCharCode(event.keyCode);
            var key_code = event.keyCode;
            if(key_code == 13)
                register(<?php if($GLOBALS['registration']['captcha']==true)  echo 1;  else  echo 0; ?>)
        }
    </script>