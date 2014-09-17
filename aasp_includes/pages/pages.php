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

    $server->SelectDB('webdb');
    $page = new page;
    $page->validatePageAccess('Pages');
    if($page->validateSubPage() == true)
        $page->outputSubPage();
    else
    {
        echo '<div class="box_right_title">Pages</div>';
        if(!isset($_GET['action']))
        {
            echo '<table class="center"><tr><th>Name</th><th>File name</th><th>Actions</th></tr>';

            $result = $sql->query("SELECT * FROM custom_pages ORDER BY id ASC");
            while($row = mysqli_fetch_assoc($result))
            {
                $check = $sql->query("SELECT COUNT(filename) AS count FROM disabled_pages WHERE filename='".$row['filename']."'");
                $result_check = mysqli_fetch_assoc($check);
                if($result_check['count'] == 0)
                    $disabled = false;
                else
                    $disabled = true;
        ?>
        <tr <?php if($disabled) { echo "style='color: #999;'"; }?>>
             <td width="50"><?php echo $row['name']; ?></td>
             <td width="100"><?php echo $row['filename']; ?>(Database)</td>
             <td><select id="action-<?php echo $row['filename']; ?>"><?php if($disabled==true) {  ?>
                 <option value="1">Enable</option>
             <?php } else { ?>
                 <option value="2">Disable</option>
             <?php } ?>
             <option value="3">Edit</option>
             <option value="4">Remove</option>
             </select> &nbsp;<input type="submit" value="Save" onclick="savePage('<?php echo $row['filename']; ?>')"></td>
        </tr>
    <?php }

            foreach ($GLOBALS['core_pages'] as $k => $v)
            {
                $filename = substr($v, 0, -4);
                unset ($check);
                $check = $sql->query("SELECT COUNT(filename) AS count FROM disabled_pages WHERE filename='".$filename."'");
                $result = mysqli_fetch_assoc($check);
                if($result['count'] <= 0)
                    $disabled = false;
                else
                    $disabled = true;
    ?>

        <tr <?php if($disabled==true) { echo "style='color: #999;'"; }?>>
            <td><?php echo $k; ?></td>
            <td><?php echo $v; ?></td>
            <td><select id="action-<?php echo $filename; ?>">
                 <?php if($disabled==true) { ?>
                 <option value="1">Enable</option>
             <?php } else { ?>
                 <option value="2">Disable</option>
             <?php } ?>
            </select> &nbsp;<input type="submit" value="Save" onclick="savePage('<?php echo $filename; ?>')"></td>
        </tr>
    <?php }

        echo "</table>";
        }
        elseif($_GET['action']=='new')
        {}
        elseif($_GET['action']=='edit')
        {

        if(isset($_POST['editpage']))
        {
            $name = addslashes($_POST['editpage_name']);
            $filename = trim(strtolower(mysql_real_escape_string($_POST['editpage_filename'])));
            $content = addslashes(htmlentities($_POST['editpage_content']));
            if(empty($name) || empty($filename) || empty($content))
                echo "<h3>Please enter <u>all</u> fields.</h3>";
            else
            {
                $sql->query("UPDATE custom_pages SET name='".$name."',filename='".$filename."',
                content='".$content."' WHERE filename='".addslashes($_GET['filename'])."'");
                echo "<h3>The page was successfully updated.</h3>
                <a href='".$GLOBALS['website_domain']."?p=".$filename."' target='_blank'>View Page</a>";
            }
        }

    $result = $sql->query("SELECT * FROM custom_pages WHERE filename='".addslashes($_GET['filename'])."'");
    $row = mysqli_fetch_assoc($result);
    ?>

         <h4>Editing <?php echo $_GET['filename']; ?>.php</h4>
        <form action="?p=pages&action=edit&filename=<?php echo $_GET['filename']; ?>" method="post">
        Name<br/>
        <input type="text" name="editpage_name" value="<?php echo $row['name']; ?>"><br/>
        Filename<br/>
        <input type="text" name="editpage_filename" value="<?php echo $row['filename']; ?>"><br/>
        Content<br/>
        <textarea cols="77" rows="14" id="wysiwyg" name="editpage_content"><?php echo $row['content']; ?></textarea>
        <br/>
        <input type="submit" value="Save" name="editpage">

    <?php
        }
    }