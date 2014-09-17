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

    if (!isset($_SESSION['cw_user']))
    {
        if (isset($_POST['login']))
            account::logIn($_POST['login_username'],$_POST['login_password'],$_SERVER['REQUEST_URI'],$_POST['login_remember']);
        ?>
        <div class="box_one">
            <div class="box_one_title">Account Management</div>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                <fieldset style="border: none; margin: 0; padding: 0;">
                    <input type="text" placeholder="Username..." name="login_username" class="login_input" /><br/>
                    <input type="password" placeholder="Password..." name="login_password" class="login_input" style="margin-top: -1px;" /><br/>
                    <input type="submit" value="Log In" name="login" style="margin-top: 4px;" />
                    <input type="checkbox" name="login_remember" checked="checked"/> Remember me
                </fieldset>
            </form>
            <br/>
            <table width="100%">
                <tr>
                    <td><a href="?p=register">Create an account</a></td>
                    <td align="right"><a href="?p=forgotpw">Forgot your Password?</a></td>
                </tr>
            </table>
        </div>
<?php }
