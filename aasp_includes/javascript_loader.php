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
<script type="text/javascript" src="../aasp_includes/js/interface.js"></script>
<script type="text/javascript" src="../aasp_includes/js/account.js"></script>
<script type="text/javascript" src="../aasp_includes/js/server.js"></script>
<script type="text/javascript" src="../aasp_includes/js/news.js"></script>
<script type="text/javascript" src="../aasp_includes/js/logs.js"></script>
<script type="text/javascript" src="../aasp_includes/js/shop.js"></script>
<?php if($GLOBALS['core_expansion']>2) 
{
	//Core is over WOTLK. Use WoWHead.
	echo '<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>';
}
else
{
	echo '<script type="text/javascript" src="http://cdn.openwow.com/api/tooltip.js"></script>';
}
?>
<script type="text/javascript" src="../aasp_includes/js/wysiwyg.js"></script>
<script type="text/javascript" src="../aasp_includes/js/wysiwyg/wysiwyg.image.js"></script>
<script type="text/javascript" src="../aasp_includes/js/wysiwyg/wysiwyg.link.js"></script>
<script type="text/javascript" src="../aasp_includes/js/wysiwyg/wysiwyg.table.js"></script>

<script type="text/javascript">
$(function() {
        $('#wysiwyg').wysiwyg();
    });
</script>