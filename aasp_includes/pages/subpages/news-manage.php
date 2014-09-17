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
    $server = new server;
    $server->SelectDB('webdb');
    $sql = $server->sqli();
    $result = $sql->query("SELECT * FROM news ORDER BY id DESC");
    if(mysqli_num_rows($result) == 0)
        echo "<span class='blue_text'>No news has been posted yet!</span>";
    else
    {
        echo '<div class="box_right_title">News &raquo; Manage</div>
            <table style="width:100%">
            <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Comments</th>
            <th>Actions</th>
            </tr>';

        while($row = mysqli_fetch_assoc($result))
        {

            $comments = $sql->query("SELECT COUNT(id) AS count FROM news_comments WHERE newsid='".$row['id']."'");
            $res = mysqli_fetch_assoc($comments);
            echo '<tr class="center">
                <td>'.$row['id'].'</td>
                <td>'.$row['title'].'</td>
                <td>'.strip_tags(substr($row['body'],0,25)).'...</td>
                <td>'.$res['count'].'</td>
                <td> <a onclick="editNews('.$row['id'].')" href="#">Edit</a> &nbsp;
                <a onclick="deleteNews('.$row['id'].')" href="#">Delete</a></td>
                </tr>';
        }
        echo '</table>';
    }