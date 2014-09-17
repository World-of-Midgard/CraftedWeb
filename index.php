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

    require('includes/loader.php');
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <?php require('includes/template_loader.php'); ?>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>
    <?php
    echo $website_title .' - ';
    while ($page_title = current($GLOBALS['core_pages']))
    {
        if ($page_title == $_GET['p'].'.php')
        {
            echo key($GLOBALS['core_pages']);
            $foundPT = true;
        }
        next($GLOBALS['core_pages']);
    }
    if(!isset($foundPT))
        echo ucfirst($_GET['p']);
    ?>
    </title>
    <?php
        $content = new Page('styles/'.$template['path'].'/template.html');
        $content->loadCustoms();
        $content->replace_tags(array('content' => 'modules/content.php'));
        $content->replace_tags(array('menu' => 'modules/menu.php'));
        $content->replace_tags(array('login' => 'modules/login.php'));
        $content->replace_tags(array('account' => 'modules/account.php'));
        $content->replace_tags(array('serverstatus' => 'modules/server_status.php'));
        $content->replace_tags(array('slideshow' => 'modules/slideshow.php'));
        $content->replace_tags(array('footer' => 'modules/footer.php'));
        $content->replace_tags(array('loadjava' => 'includes/javascript_loader.php'));
        $content->replace_tags(array('social' => 'modules/social.php'));
        $content->replace_tags(array('alert' => 'modules/alert.php'));
    ?>
    </head>
    <body>
    <?php $content->output(); ?>
    </body>
    </html>