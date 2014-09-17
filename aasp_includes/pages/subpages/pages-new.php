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
    $page = new page;
    $server = new server;
    $sql = $server->sqli();
    if(isset($_POST['newpage'])) {

        $name = addslashes($_POST['newpage_name']);
        $filename = trim(strtolower(addslashes($_POST['newpage_filename'])));
        $content = addslashes(htmlentities($_POST['newpage_content']));

        if(empty($name) || empty($filename) || empty($content))
            echo "<h3>Please enter <u>all</u> fields.</h3>";
        else
        {
            $sql->query("INSERT INTO custom_pages VALUES ('','".$name."','".$filename."','".$content."','".date("Y-m-d H:i:s")."')");
            echo "<h3>The page was successfully created.</h3><a href='".$GLOBALS['website_domain']."?p=".$filename."' target='_blank'>View Page</a><br/><br/>";
        }
    }
    ?>
    <div class="box_right_title"><?php echo $page->titleLink(); ?> &raquo; New page</div>
    <form action="?p=pages&s=new" method="post">
    Name <br/>
    <input type="text" name="newpage_name"><br/>
    Filename <i>(This is what the ?p=FILENAME will refer to. Eg. ?p=connect where Filename is 'connect')<br/>
    <input type="text" name="newpage_filename"><br/>
    Content<br/>
    <textarea cols="77" rows="14" id="wysiwyg" name="newpage_content">
    <?php if(isset($_POST['newpage_content'])) { echo $_POST['newpage_content']; } ?></textarea><br/>
    <input type="submit" value="Create" name="newpage">