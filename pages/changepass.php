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

echo "<div class='box_two_title'>Change Password</div>";
account::isNotLoggedIn();
if (isset($_POST['change_pass']))
	account::changePass($_POST['cur_pass'],$_POST['new_pass'],$_POST['new_pass_repeat']);
?>
<form action="?p=changepass" method="post">
<table width="70%">
       <tr>
           <td>New password:</td> 
           <td><input type="password" name="new_pass" class="input_text"/></td>
       </tr> 
       <tr>
           <td>Repeat new password:</td> 
           <td><input type="password" name="new_pass_repeat" class="input_text"/></td>
       </tr>
        <tr>
           <td></td> 
           <td><hr/></td>
       </tr> 
       <tr>
           <td>Enter your current password:</td> 
           <td><input type="password" name="cur_pass" class="input_text"/></td>
       </tr>  
       <tr>
           <td></td> 
           <td><input type="submit" value="Change Password" name="change_pass" /></td>
       </tr>                
</table>                 
</form>