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

    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
    require('includes/classes/template_parse.php');
    connect::selectDB('webdb');
    $getTemplate = $sql->query("SELECT path FROM template WHERE applied='1' ORDER BY id ASC LIMIT 1");
    $row = mysqli_fetch_assoc($getTemplate);
    $template['path'] = $row['path'];

    if(!file_exists("styles/".$template['path']."/style.css") || !file_exists("styles/".$template['path']."/template.html"))
    {
        //buildError("<b>Template Error: </b>The active template does not exist or missing files.",NULL);
        exit_page();
    }
    ?>
    <link rel="stylesheet" href="styles/<?php echo $template['path']; ?>/style.css" />
    <link rel="stylesheet" href="styles/global/style.css" />
    <?php
    plugins::load('styles');