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

    connect::selectDB('webdb');
    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
    $pages = scandir('pages');
    unset($pages[0],$pages[1]);
    $page = addslashes($_GET['p']);
    if (!isset($page))
        include('pages/home.php');
    elseif(isset($_SESSION['loaded_plugins_pages']) && $GLOBALS['enablePlugins'] == true && !in_array($page.'.php',$pages))
        plugins::load('pages');

    elseif(in_array($page.'.php',$pages))
    {
        $result = $sql->query("SELECT COUNT(filename) FROM disabled_pages WHERE filename='".$page."'");
        if(!$result)
            include('pages/'.$page.'.php');
        else
            include('pages/404.php');
    }
    else
    {
        $result = $sql->query("SELECT * FROM custom_pages WHERE filename='".$page."'");
        if($result)
        {
            $check = $sql->query("SELECT COUNT(filename) FROM disabled_pages WHERE filename='".$page."'");
            if(!$check)
            {
                $row = mysqli_fetch_assoc($result);
                echo html_entity_decode($row['content']);
            }
        }
        else
            include('pages/404.php');
    }