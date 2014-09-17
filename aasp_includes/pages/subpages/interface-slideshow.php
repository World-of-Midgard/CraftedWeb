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
    ?>
    <div class="box_right_title"><?php echo $page->titleLink(); ?> &raquo; Slideshow</div>
    <?php
    if($GLOBALS['enableSlideShow']==true)
        $status = 'Enabled';
    else
        $status = 'Disabled';

    $server->selectDB('webdb');
    $count = $sql->query("SELECT COUNT(*) AS count FROM slider_images");
    $result_count = mysqli_fetch_assoc($count);
    ?>
    The slideshow is <b><?php echo $status; ?></b>. You have <b><?php echo round($result_count['count']); ?></b> images in the slideshow.
    <hr/>
    <?php
    if(isset($_POST['addSlideImage']))
    {
        $page = new page;
        $page->addSlideImage($_FILES['slideImage_upload'],$_POST['slideImage_path'],$_POST['slideImage_url']);
    }
    ?>
    <a href="#addimage" onclick="addSlideImage()" class="content_hider">Add image</a>
    <div class="hidden_content" id="addSlideImage">
    <form action="" method="post" enctype="multipart/form-data">
    Upload an image:<br/>
    <input type="file" name="slideImage_upload"><br/>
    or enter image URL: (This will override your uploaded image)<br/>
    <input type="text" name="slideImage_path"><br/>
    Where should the image redirect? (Leave empty if no redirect)<br/>
    <input type="text" name="slideImage_url"><br/>
    <input type="submit" value="Add" name="addSlideImage">
    </form>
    </div>
    <br/>&nbsp;<br/>
    <?php
    $server->selectDB('webdb');
    $result = $sql->query("SELECT * FROM slider_images ORDER BY position ASC");
    if(!$result)
        echo "You don't have any images in the slideshow!";
    else
    {
        echo '<table>';
        $c = 1;
        while($row = mysqli_fetch_assoc($result))
        {
            echo '<tr class="center">';
            echo '<td><h2>&nbsp; '.$c.' &nbsp;</h2><br/>
            <a href="#remove" onclick="removeSlideImage('.$row['position'].')">Remove</a></td>';
            echo '<td><img src="../'.$row['path'].'" alt="'.$c.'" class="slide_image" maxheight="200"/></td>';
            echo '</tr>';
            $c++;
        }
          echo '</table>';
    }

