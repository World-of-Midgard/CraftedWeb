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

class Page
{
    var $page;
    var $values = array();
    function Page($template)
    {
        if (file_exists($template))
        $this->page = join("", file($template));
    }

    function parse($file)
    {
        ob_start();
        include($file);
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    function replace_tags($tags = array())
    {
        if (sizeof($tags) > 0)
        {
            foreach ($tags as $tag => $data)
            {
                $data = (file_exists($data)) ? $this->parse($data) : $data;
                $this->page = preg_replace("({" . $tag . "})", $data,
                $this->page);
            }
        }
    }

    function setVar($key,$array)
    {
        $this->values[$key] = $array;
    }

    function output()
    {
        echo $this->page;
    }

    function loadCustoms()
    {
        if($GLOBALS['enablePlugins'] == true)
        {
            if(isset($_SESSION['loaded_plugins_modules']))
            {
                foreach($_SESSION['loaded_plugins_modules'] as $filename)
                {
                    $name = basename(substr($filename,0,-4));
                    $this->replace_tags(array($name => $filename));
                }
            }
        }
    }
}