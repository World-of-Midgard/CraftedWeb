<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
?>
<?php $page = new page; ?>
<div class="box_right_title"><?php echo $page->titleLink(); ?> &raquo; Tools</div>
<input type="submit" value="Clear Vote shop" onclick="clearShop('vote')"/>  &nbsp; 
This will clear all items from the vote shop<br/><br/>
<input type="submit" value="Clear Donation shop" onclick="clearShop('donate')"/> &nbsp;  
This will clear all items from the donation shop