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
 
    ?>
    <div class='box_two_title'>Login</div>
    Please log in to view this page. <hr/>
    <?php
        if(isset($_POST['x_login']))
            account::logIn($_POST['x_username'],$_POST['x_password'],$_POST['x_redirect'],$_POST['x_remember']);
    ?>
    <form action="?p=login" method="post">
    <table>
        <tr>
        <td>Username:</td>
        <td><input type="text" name="x_username"></td>
        </tr>
        <tr>
        <td>Password:</td>
        <td><input type="password" name="x_password"></td>
        </tr>
        <tr>
        <td></td>
        <td><input type="checkbox" name="x_remember"> Remember Me</td>
        </tr>
        <tr>
        <td><input type="hidden" value="<?php echo $_GET['r']; ?>" name="x_redirect"></td>
        <td><input type="submit" value="Log In" name="x_login"></td>
        </tr>
    </table>
    </form>