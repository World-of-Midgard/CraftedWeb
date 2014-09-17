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
class connect
{
    public static $connectedTo = NULL;
    public static function connectToDB()
    {
        global $sql;
        if(static::$connectedTo != 'global')
        {
            if (!$sql)
                echo "Connect Error";
                ////buildError("<b>Database Connection error:</b> A connection could not be established. Error: ".mysqli_error($sql), null);
            static::$connectedTo = 'global';
        }
    }

    public static function connectToRealmDB($realmid)
    {
        global $sql;
        static::selectDB('webdb');
        if($GLOBALS['realms'][$realmid]['mysql_host'] != $GLOBALS['connection']['host']
        || $GLOBALS['realms'][$realmid]['mysql_user'] != $GLOBALS['connection']['user']
        || $GLOBALS['realms'][$realmid]['mysql_pass'] != $GLOBALS['connection']['password'])
        {
            $sql->connect($GLOBALS['realms'][$realmid]['mysql_host'],
            $GLOBALS['realms'][$realmid]['mysql_user'],
            $GLOBALS['realms'][$realmid]['mysql_pass']);
        }
        else
            static::connectToDB();
        $sql->select_db($GLOBALS['realms'][$realmid]['chardb']);
        static::$connectedTo = 'chardb';
    }

    public static function selectDB($db)
    {
        global $sql;
        static::connectToDB();
        switch($db)
        {
            default:
                $sql->select_db($db);
                break;
            case('logondb'):
                $sql->select_db($GLOBALS['connection']['logondb']);
                break;
            case('webdb'):
                $sql->select_db($GLOBALS['connection']['webdb']);
                break;
            case('worlddb'):
                $sql->select_db($GLOBALS['connection']['worlddb']);
                break;
        }
        return true;
    }
}

    $realms = array();
    $service = array();
    connect::selectDB('webdb');
    $getRealms = $sql->query("SELECT * FROM realms ORDER BY id ASC");
    while($row = mysqli_fetch_assoc($getRealms))
    {
        $realms[$row['id']] = $row;
        $realms[$row['id']]['chardb'] = $row['char_db'];
    }

    $getServices = $sql->query("SELECT enabled,price,currency,service FROM service_prices");
    while($row = mysqli_fetch_assoc($getServices))
    {
        $service[$row['service']]['status'] = $row['enabled'];
        $service[$row['service']]['price'] = $row['price'];
        $service[$row['service']]['currency'] = $row['currency'];
    }

    if (get_magic_quotes_gpc())
    {
        $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
        while (list($key, $val) = each($process))
        {
            foreach ($val as $k => $v)
            {
                unset($process[$key][$k]);
                if (is_array($v))
                {
                    $process[$key][stripslashes($k)] = $v;
                    $process[] = &$process[$key][stripslashes($k)];
                }
                else
                    $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    unset($process);
    }