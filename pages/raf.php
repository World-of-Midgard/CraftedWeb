<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
 
?>
<?php account::isNotLoggedIn(); ?>
<div class='box_two_title'>Refer-A-Friend</div>
<b class='yellow_text'>Your referral link: </b> <div id="raf_box">
                  <?php echo $GLOBALS['website_domain']."?p=register&id=".account::getAccountID($_SESSION['cw_user']); ?>
</div><br/>
<h4 class='blue_text'>How does it work?</h4>

It's simple! Just copy the link above and send it to your friends. If they create an account using your referral link, you two can venture into Azeroth with faster leveling speeds, reputation gain, and more!