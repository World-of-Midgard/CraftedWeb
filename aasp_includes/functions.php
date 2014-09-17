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

    if(!isset($_SESSION))
        session_start();

    if(isset($_SESSION['cw_staff']) && !isset($_SESSION['cw_staff_id']))
    {
        session_destroy();
        exit('Seems like you\'re missing 1 or more sessions. You\'ve been logged out due to security reasons.');
    }

    if(isset($_SESSION['cw_admin']) && !isset($_SESSION['cw_admin_id']))
    {
        session_destroy();
        exit('Seems like you\'re missing 1 or more sessions. You\'ve been logged out due to security reasons.');
    }

    $sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);

    class server
    {
        public static function sqli()
        {
            global $sql;
            return $sql;
        }

        public function getConnections()
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT COUNT(id) AS count FROM account WHERE online='1'");
            $data = mysqli_fetch_assoc($result);
            return $data['count'];
        }

        public function getPlayersOnline($rid)
        {
            global $sql;
            $this->connectToRealmDB($rid);
            $result = $sql->query("SELECT COUNT(*) AS count FROM characters WHERE online='1'");
            $data = mysqli_fetch_assoc($result);
            return $data['count'];
        }

        public function getUptime($rid)
        {
            global $sql;
            global $string;
            $this->SelectDB($GLOBALS['connection']['logondb']);
            $getUp = $sql->query("SELECT starttime FROM uptime WHERE realmid='".(int)$rid."' ORDER BY starttime DESC LIMIT 1");
            $row = mysqli_fetch_assoc($getUp);
            $time = time();
            $uptime = $time - $row['starttime'];

            if($uptime<60)
                $string = 'Seconds';
            elseif ($uptime > 60)
            {
                $uptime = $uptime / 60;
                $string = 'Minutes';
                if ($uptime > 60)
                {
                    $string = 'Hours';
                    $uptime = $uptime / 60;
                    if ($uptime > 24)
                    {
                        $string = 'Days';
                        $uptime = $uptime / 24;
                    }
                }
                $uptime = ceil($uptime);
            }
            return $uptime.' '.$string;
        }

        public function getServerStatus($rid)
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['webdb']);
            $result = $sql->query("SELECT host,port FROM realms WHERE id='".(int)$rid."'");
            $row = mysqli_fetch_assoc($result);
            $fp = @fsockopen($row['host'], $row['port']);
            if (!$fp)
               return '<font color="#990000">Offline</font>';
            else
                return 'Online';
        }

        public function getGMSOnline()
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT COUNT(id) AS count FROM account WHERE username IN ( select username FROM account WHERE online IN ('1'))
            AND id IN (SELECT id FROM account_access WHERE gmlevel>'1');");
            $data = mysqli_fetch_assoc($result);
            return $data['count'];
        }

        public function getAccountsCreatedToday()
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT COUNT(id) AS count FROM account WHERE joindate LIKE '%".date("Y-m-d")."%'");
            $data = mysqli_fetch_assoc($result);
            return $data['count'];
        }

        public function getActiveAccounts()
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT COUNT(id) AS count FROM account WHERE last_login LIKE '%".date("Y-m")."%'");
            $data = mysqli_fetch_assoc($result);
            return $data['count'];
        }

        public function getActiveConnections()
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT COUNT(id) AS count FROM account WHERE online=1");
            $data = mysqli_fetch_assoc($result);
            return $data['count'];
        }

        public function getFactionRatio($rid)
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['webdb']);
            $result = $sql->query("SELECT id FROM realms");
            if(mysqli_num_rows($result) <= 0)
                $this->faction_ratio = "Unknown";
            else
            {
                $t = 0;
                $a = 0;
                $h = 0;
                while($row = mysqli_fetch_assoc($result))
                {
                    $this->connectToRealmDB($row['id']);
                    $result = $sql->query("SELECT COUNT(*) FROM characters");
                    $data = $result->fetch_array();
                    $t = $t + $data[0];

                    $result = $sql->query("SELECT COUNT(*) FROM characters WHERE race IN('3','4','7','11','1','22')");
                    $data2 = $result->fetch_array();
                    $a = $a + $data2[0];

                    $result = $sql->query("SELECT COUNT(*) FROM characters WHERE race IN('2','5','6','8','10','9')");
                    $data3 = $result->fetch_array();
                    $h = $h + $data3[0];
                }
                $a = ($a / $t)*100;
                $h = ($h / $t)*100;
                return '<font color="#0066FF">'.round($a).'%</font> &nbsp; <font color="#CC0000">'.round($h).'%</font>';
            }
            return $rid;
        }

        public function getAccountsLoggedToday()
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT COUNT(*) AS count FROM account WHERE last_login LIKE '%".date('Y-m-d')."%'");
            $data = mysqli_fetch_assoc($result);
            return $data['count'];
        }

        public function connect()
        {
            mysqli_connect($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
        }

        public function connectToRealmDB($realmid)
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['webdb']);
            $getRealmData = $sql->query("SELECT mysql_host,mysql_user,mysql_pass,char_db FROM realms WHERE id='".(int)$realmid."'");
            if(mysqli_num_rows($getRealmData) > 0)
            {
                $row = mysqli_fetch_assoc($getRealmData);
                if($row['mysql_host'] != $GLOBALS['connection']['host'] || $row['mysql_user'] != $GLOBALS['connection']['user'] || $row['mysql_pass'] != $GLOBALS['connection']['password'])
                    mysqli_connect($row['mysql_host'],$row['mysql_user'],$row['mysql_pass']);
                //or //buildError("<b>Database Connection error:</b> A connection could not be established to Realm. Error: " . mysqli_connect_error(), NULL);
                else
                    $this->connect();
                $this->SelectDB($row['char_db']);
                //or //buildError("<b>Database Selection error:</b> The realm database could not be selected. Error: " . mysqli_error(),NULL);
            }
        }

        public function SelectDB($db)
        {
            global $sql;
            $this->connect();
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
        }

        public function getItemName($id)
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['webdb']);
            $result = $sql->query("SELECT name FROM item_template WHERE entry='".$id."'");
            $row = mysqli_fetch_assoc($result);
            return $row['name'];
        }

        public function getAddress()
        {
            return $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }

        public function logThis($action,$extended = NULL)
        {
            global $sql;
            global $aid;
            $this->SelectDB($GLOBALS['connection']['webdb']);
            $url = $this->getAddress();
            if(isset($_SESSION['cw_admin']))
                $aid = (int)$_SESSION['cw_admin_id'];
            elseif(isset($_SESSION['cw_staff']))
                $aid = (int)$_SESSION['cw_staff_id'];

            $sql->query("INSERT INTO admin_log VALUES ('','".addslashes($url)."','".$_SERVER['REMOTE_ADDR']."','".time()."',
            '".addslashes($action)."','".$aid."','".addslashes($extended)."')");
        }

        public function addRealm($id,$name,$desc,$host,$port,$chardb,$sendtype,$rank_user,$rank_pass,$ra_port,$soap_port,$m_host,$m_user,$m_pass)
        {
            global $sql;
            $id = (int)$id;
            $name = addslashes($name);
            $desc = addslashes($desc);
            $host = addslashes($host);
            $port = addslashes($port);
            $chardb = addslashes($chardb);
            $sendtype = addslashes($sendtype);
            $rank_user = addslashes($rank_user);
            $rank_pass = addslashes($rank_pass);
            $ra_port = addslashes($ra_port);
            $soap_port = addslashes($soap_port);
            $m_host = addslashes($m_host);
            $m_user = addslashes($m_user);
            $m_pass = addslashes($m_pass);

            if (empty($name) || empty($host) || empty($port) || empty($chardb) || empty($rank_user) || empty($rank_pass))
                echo "<pre><b class='red_text'>Please enter all required fields!</b></pre><br/>";
            else
            {
                if(empty($m_host))
                    $m_host = $GLOBALS['connection']['host'];
                if(empty($m_user))
                    $m_host = $GLOBALS['connection']['user'];
                if(empty($m_pass))
                    $m_pass = $GLOBALS['connection']['password'];

                if (empty($ra_port))
                    $ra_port = 3443;
                if (empty($soap_port))
                    $soap_port = 7878;

                $this->SelectDB($GLOBALS['connection']['webdb']);
                $sql->query("INSERT INTO realms VALUES ('".$id."','".$name."','".$desc."','".$chardb."','".$port."',
                '".$rank_user."','".$rank_pass."','".$ra_port."','".$soap_port."','".$host."','".$sendtype."','".$m_host."',
                '".$m_user."','".$m_pass."')");

                $this->logThis("Added the realm ".$name."<br/>");
                echo '<pre><h3>&raquo; Successfully added the realm '.$name.'!</h3></pre><br/>';
            }
        }

        public function getRealmName($realm_id)
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['webdb']);
            $result = $sql->query("SELECT name FROM realms WHERE id='".(int)$realm_id."'");
            $row = mysqli_fetch_assoc($result);
            if(empty($row['name']))
               return '<i>Unknown</i>';
            else
               return $row['name'];
        }

        public function checkForNotifications()
        {
            global $sql;
            $this->SelectDB($GLOBALS['connection']['webdb']);
            $old = time() - 2592000;
            $result = $sql->query("SELECT COUNT(*) AS count FROM votelog WHERE `timestamp` <= ".$old."");
            $data = mysqli_fetch_assoc($result);
            if($data['count'] > 1)
            {
                echo '<div class="box_right"><div class="box_right_title">Notifications</div>';
                echo 'You have '.$data['count'].' votelog records that are 30 days or older. Since these are not really needed in general. We suggest you clear these. ';
                echo '</div>';
            }
        }

        public function serverStatus()
        {
            global $sql;
            if(!isset($_COOKIE['presetRealmStatus']))
            {
                $this->SelectDB($GLOBALS['connection']['webdb']);
                $getRealm = $sql->query('SELECT id FROM realms ORDER BY id ASC LIMIT 1');
                $row = mysqli_fetch_assoc($getRealm);
                $rid = $row['id'];
            }
            else
                $rid = $_COOKIE['presetRealmStatus'];
            echo 'Selected realm: <b>'.$this->getRealmName($rid).'</b> <a href="#" onclick="changePresetRealmStatus()">(Change Realm)</a><hr/>';
    ?>
            <table>
                   <tr valign="top">
                       <td width="70%">
                            Server Status: <br/>
                            Uptime: <br/>
                            Players online: <br/>
                       </td>
                       <td>
                       <b>
                           <?php echo $this->getServerStatus($rid);?><br/>
                           <?php echo $this->getUptime($rid);?><br/>
                           <?php echo $this->getPlayersOnline($rid);?><br/>
                       </b>
                       </td>
                   </tr>
                </table>
                <hr/>
                <b>General Status:</b><br/>
                <table>
                   <tr valign="top">
                       <td width="70%">
                            Active connections: <br/>
                            Accounts created today: <br/>
                            Active accounts (This month)
                       </td>
                       <td>
                       <b>
                           <?php echo $this->getActiveConnections();?><br/>
                           <?php echo $this->getAccountsCreatedToday();?><br/>
                           <?php echo $this->getActiveAccounts();?><br/>
                       </b>
                       </td>
                   </tr>
                </table>
    <?php
         }
    }

    class account
    {
        public function getAccID($user)
        {
            global $sql;
            $server = new server;
            $server->SelectDB($GLOBALS['connection']['logondb']);
            $user = addslashes($user);
            $result = $sql->query("SELECT id FROM account WHERE username='".addslashes($user)."'");
            $row = mysqli_fetch_assoc($result);
            return $row['id'];
        }

        public function getAccName($id)
        {
            global $sql;
            $server = new server;
            $server->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT username FROM account WHERE id='".(int)$id."'");
            $row = mysqli_fetch_assoc($result);
            if(empty($row['username']))
               return '<i>Unknown</i>';
            else
               return $row['username'];
        }

        public function getCharName($id,$realm_id)
        {
            global $sql;
            $server = new server;
            $server->connectToRealmDB($realm_id);
            $result = $sql->query("SELECT name FROM characters WHERE guid='".(int)$id."'");
            if(mysqli_num_rows($result) <= 0)
               return '<i>Unknown</i>';
            else
            {
                $row = mysqli_fetch_assoc($result);
                if(empty($row['name']))
                  return '<i>Unknown</i>';
                else
                  return $row['name'];
            }
        }

        public function getEmail($id)
        {
            global $sql;
            $server = new server;
            $server->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT email FROM account WHERE id='".(int)$id."'");
            $row = mysqli_fetch_assoc($result);
            return $row['email'];
        }

        public function getVP($id)
        {
            global $sql;
            $server = new server;
            $server->SelectDB($GLOBALS['connection']['webdb']);
            $result = $sql->query("SELECT vp FROM account_data WHERE id='".(int)$id."'");
            if(mysqli_num_rows($result) <= 0)
                return 0;
            $row = mysqli_fetch_assoc($result);
            return $row['vp'];
        }

        public function getDP($id)
        {
            global $sql;
            $server = new server;
            $server->SelectDB($GLOBALS['connection']['webdb']);
            $result = $sql->query("SELECT dp FROM account_data WHERE id='".(int)$id."'");
            if(mysqli_num_rows($result) <= 0)
                return 0;
            $row = mysqli_fetch_assoc($result);
            return $row['dp'];
        }

        public function getBan($id)
        {
            global $sql;
            $server = new server;
            $server->SelectDB($GLOBALS['connection']['logondb']);
            $result = $sql->query("SELECT * FROM account_banned WHERE id='".(int)$id."' AND active = 1 ORDER by bandate DESC LIMIT 1");
            if(mysqli_num_rows($result) <= 0)
                return "<span class='green_text'>Active</span>";
            $row = mysqli_fetch_assoc($result);
            if($row['unbandate'] < $row['bandate'])
                $time = "Never";
            else
                $time = date("Y-m-d H:i", $row['unbandate']);

            return
            "<font size='-4'><b class='red_text'>Banned</b><br/>
            Unban date: <b>".$time."</b><br/>
            Banned by: <b>".$row['bannedby']."</b><br/>
            Reason: <b>".$row['banreason']."</b></font>
            ";
        }
    }

    class page {

       public function validateSubPage()
       {
           if(isset($_GET['s']) && !empty($_GET['s']))
              return true;
           else
              return false;
       }

       public function validatePageAccess($page) {
           if(isset($_SESSION['cw_staff']) && !isset($_SESSION['cw_admin']))
           {
               if($GLOBALS['staffPanel_permissions'][$page]!=true)
               {
                 header("Location: ?p=notice&e=<h2>Not authorized!</h2>
                        You are not allowed to view this page!");
                   exit;
               }
           }
       }

        public function outputSubPage()
        {
             $page = $_GET['p'];
             $subpage = $_GET['s'];
             $pages = scandir('../aasp_includes/pages/subpages');
             unset($pages[0],$pages[1]);

             if (!file_exists('../aasp_includes/pages/subpages/'.$page.'-'.$subpage.'.php'))
                 include('../aasp_includes/pages/404.php');
             elseif(in_array($page.'-'.$subpage.'.php',$pages))
                 include('../aasp_includes/pages/subpages/'.$page.'-'.$subpage.'.php');
             else
                  include('../aasp_includes/pages/404.php');
        }

        public function titleLink()
        {
            return '<a href="?p='.$_GET['p'].'" title="Back to '.ucfirst($_GET['p']).'">'.ucfirst($_GET['p']).'</a>';
        }

        public function addSlideImage($upload,$path,$url)
        {
            global $sql;
            $path = addslashes($path);
            $url = addslashes($url);

            if(empty($path))
            {
                if($upload['error']>0)
                {
                    echo "<span class='red_text'><b>Error:</b> File uploading was not successfull!</span>";
                    $abort = true;
                }
                else
                {
                    if ((($upload["type"] == "image/gif") || ($upload["type"] == "image/jpeg") || ($upload["type"] == "image/pjpeg") || ($upload["type"] == "image/png")))
                    {
                    if (file_exists("../styles/global/slideshow/images/" . $upload["name"]))
                    {
                    unlink("../styles/global/slideshow/images/" . $upload["name"]);
                    move_uploaded_file($upload["tmp_name"],"../styles/global/slideshow/images/" . $upload["name"]);
                    $path = "styles/global/slideshow/images/" . $upload["name"];
                    }
                    else
                    {
                    move_uploaded_file($upload["tmp_name"],"../styles/global/slideshow/images/" . $upload["name"]);
                    $path = "styles/global/slideshow/images/" . $upload["name"];
                    }
                    }
                    else
                        $abort = true;
                }
            }

            if(!isset($abort))
            {
                $server = new server;
                $server->SelectDB($GLOBALS['connection']['webdb']);
                $sql->query("INSERT INTO slider_images VALUES('','".$path."','".$url."')");
            }
        }

    }

    class character
    {
        public static function getRace($value)
      {
          switch($value)
          {
             default:
                 return "Unknown";
                break;
             case 1:
                return "Human";
                break;
             case 2:
                return "Orc";
                break;
             case 3:
                return "Dwarf";
                break;
             case 4:
                 return "Night Elf";
                break;
             case 5:
                return "Undead";
                break;
             case 6:
                return "Tauren";
                break;
             case 7:
                 return "Gnome";
                break;
             case 8:
                return "Troll";
                break;
             case 9:
                 return "Goblin";
                break;
             case 10:
                return "Blood Elf";
                break;
             case 11:
                return "Dranei";
                break;
             case 22:
                return "Worgen";
                break;
          }
      }

      public static function getGender($value)
      {
         if($value==1)
             return "Female";
         elseif($value==0)
             return "Male";
         else
             return "Unknown";
      }

        public static function getClass($value)
        {
            switch($value)
            {
             default:
                return "Unknown";
                break;
             case 1:
                 return "Warrior";
                break;
             case 2:
                return "Paladin";
                break;
             case 3:
                return "Hunter";
                break;
             case 4:
                return "Rogue";
                break;
             case 5:
                return "Priest";
                break;
             case 6:
                return "Death Knight";
                break;
             case 7:
                 return "Shaman";
                break;
             case 8:
                 return "Mage";
                break;
             case 9:
                return "Warlock";
                break;
             case 11:
                 return "Druid";
                break;
             case 12:
                return "Monk";
                break;
            }
        }
    }

    function activeMenu($p)
    {
        if(isset($_GET['p']) && $_GET['p']==$p)
            echo "style='display:block;'";
    }

    function limit_characters ($str,$n)
    {
        $str = preg_replace("/<img[^>]+\>/i", "(image) ", $str);
        if ( strlen ( $str ) <= $n )
            return $str;
        else
            return substr ($str, 0, $n).'';
    }

    function stripBBCode($text_to_search)
    {
         $pattern = '|[[\/\!]*?[^\[\]]*?]|si';
         $replace = '';
         return preg_replace($pattern, $replace, $text_to_search);
    }