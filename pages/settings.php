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

    account::isNotLoggedIn();
    if (isset($_POST['save']))
        account::changeEmail($_POST['email'],$_POST['current_pass']);
    ?>
    <div class='box_two_title'>Change Email</div>
    <form action="?p=settings" method="post">
        <table width="70%">
            <tr>
            <td>Email adress:</td>
            <td><input type="text" name="email" value="<?php echo account::getEmail($_SESSION['cw_user']); ?>"></td>
            </tr>
            <tr>
            <td></td>
            <td><hr/></td>
            </tr>
            <tr>
            <td>Enter your current password:</td>
            <td><input type="password" name="current_pass"></td>
            </tr>

            <tr>
            <td></td>
            <td><input type="submit" value="Save" name="save"></td>
            </tr>
        </table>
    </form>