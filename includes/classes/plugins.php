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
class plugins 
{
	public static function globalInit()
	{
		if($GLOBALS['enablePlugins'] == true)
		{
			if(!isset($_SESSION['loaded_plugins']))
			{
				$loaded_plugins = array();
				$bad = array('.','..','index.html');
				$count = 0;
				$folder = scandir('plugins/');
				foreach($folder as $folderName)
				{
					if(!in_array($folderName,$bad))
					{
						connect::selectDB('webdb');
						if(file_exists('plugins/'.$folderName.'/config.php'))
							include('plugins/'.$folderName.'/config.php');
						$loaded_plugins[] = $folderName;
						$count++;
					}
				}
				if($count == 0)
					$_SESSION['loaded_plugins'] = NULL;
				else
					$_SESSION['loaded_plugins'] = $loaded_plugins;
			}
		}
	}
	
	public static function init($type)
	{
        global $sql;
		if($GLOBALS['enablePlugins'] == true)
		{
			if($_SESSION['loaded_plugins'] != NULL)
			{
				$bad = array('.','..','index.html');
				$loaded = array();
				foreach($_SESSION['loaded_plugins'] as $folderName)
				{
                    connect::selectDB('webdb');
					$chk = $sql->query("SELECT COUNT(*) FROM disabled_plugins WHERE foldername='".addslashes($folderName)."'");
					if(!$chk && file_exists('plugins/' . $folderName . '/'. $type . '/'))
					{	
						$folder = scandir('plugins/' . $folderName . '/'. $type . '/');
						foreach($folder as $fileName)
						{
							if(!in_array($fileName,$bad))
								$loaded[] = 'plugins/' . $folderName . '/'. $type . '/'.$fileName;
						}
						$_SESSION['loaded_plugins_' . $type] = $loaded;
					}
				}
			}
		}
	}
	
	public static function load($type)
	{
        if($GLOBALS['enablePlugins'] == true && isset($_SESSION['loaded_plugins_' . $type]))
		{
		  if($type == 'pages')
		  {	
		  		$count = 0;
				foreach($_SESSION['loaded_plugins_' . $type] as $filename)
				{
					$name = basename(substr($filename,0,-4));
					if($name == $_GET['p'])
					{
						include($filename);
						$count = 1;
					}
				}
				if($count == 0)
					include('pages/404.php');	  
			}
			elseif($type == 'javascript')
			{
				foreach($_SESSION['loaded_plugins_' . $type] as $filename)
				{
					echo '<script type="text/javascript" src="'.$filename.'"></script>';
				}
			}
			elseif($type == 'styles')
			{
				foreach($_SESSION['loaded_plugins_' . $type] as $filename)
				{
					echo '<link rel="stylesheet" href="'.$filename.'" />';
				}
			}
			elseif($type == 'classes')
			{
				foreach($_SESSION['loaded_plugins_' . $type] as $filename)
				{
					include($filename);
				}
			}
		}
	}
}