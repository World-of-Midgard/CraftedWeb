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

    account::isNotLoggedIn(); ?>
    <div class='box_two_title'>Refer-A-Friend</div>
    <b class='yellow_text'>Your referral link: </b> <div id="raf_box">
    <?php echo $GLOBALS['website_domain']."?p=register&id=".account::getAccountID($_SESSION['cw_user']); ?>
    </div><br/>
    <h4 class='blue_text'>How does it work?</h4>

    It's simple! Just copy the link above and send it to your friends. If they create an account using your referral link,
    you two can venture into Azeroth with faster leveling speeds, reputation gain, and more!