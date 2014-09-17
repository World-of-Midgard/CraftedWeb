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
<div class="box_right_title">Updates</div>
<script type="text/javascript">
function getLatestVersions() {
	$(".hidden_version").fadeIn("fast");
}
</script>
<table width="100%">
       <tr>
            <td>Current version: r_01</td><td class="hidden_version">Available version: r_02</td>
       </tr>
       <tr>
            <td>Current database version: r_01</td><td class="hidden_version">Available database version: r_02</td>
       </tr>
       <tr>
           <td><input type="submit" value="Check for available versions" onclick="getLatestVersions()"/></td>
           <td class="hidden_version"><input type="submit" value="Update" onclick="alert('You can update from https://github.com/EmuDevs/CraftedWeb')"/></td>
       </tr>
</table>