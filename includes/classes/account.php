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
class account
{
	public static function logIn($username,$password,$last_page,$remember)
	{
        global $sql;
		if (!isset($username) || !isset($password) || $username=="Username..." || $password=="Password...")
			echo '<span class="red_text">Please enter both fields.</span>';
		else
		{
			$username = addslashes(trim(strtoupper($username)));
			$password = addslashes(trim(strtoupper($password)));

			connect::selectDB('logondb');
			$checkForAccount = $sql->query("SELECT COUNT(id) FROM account WHERE username='".$username."'");
			if (!$checkForAccount)
				echo '<span class="red_text">Invalid username.</span>';
			else
			{
				if($remember != 835727313)
					$password = sha1("".$username.":".$password."");

				$result = $sql->query("SELECT id FROM account WHERE username='".$username."' AND sha_pass_hash='".$password."'");
				if (mysqli_num_rows($result) == 0)
					echo '<span class="red_text">Wrong password.</span>';
				else
				{
					if($remember=='on')
						setcookie("cw_rememberMe", $username.' * '.$password, time()+30758400);
					$id = mysqli_fetch_assoc($result);
					$id = $id['id'];

					self::GMLogin($username);
					$_SESSION['cw_user'] = ucfirst(strtolower($username));
					$_SESSION['cw_user_id'] = $id;

                    connect::selectDB('webdb');
					$count = $sql->query("SELECT COUNT(*) AS count FROM account_data WHERE id='".$id."'");
                    $result_count = mysqli_fetch_assoc($count);
					if($result_count['count'] == 0)
                        $sql->query("INSERT INTO account_data VALUES('".$id."','0','0')");

					if(!empty($last_page))
					   header("Location: ".$last_page);
					else
					   header("Location: index.php");
                    exit;
				}
			}

		}

	}

	public static function loadUserData()
	{
		global $sql;
		$user_info = array();
        connect::selectDB('logondb');
		$account_info = $sql->query("SELECT id, username, email, joindate, locked, last_ip, expansion FROM account
		WHERE username='".$_SESSION['cw_user']."'");
		while($row = mysqli_fetch_array($account_info))
		{
			$user_info[] = $row;
		}
	    return $user_info;
	}

	public static function logOut()
	{
		session_destroy();
		setcookie('cw_rememberMe', '', time()-30758400);
		if (empty($last_page))
		{
			header('Location: index.php?p=home');
			exit();
		}
		header('Location: '.$last_page);
		exit();
	}

	public function register($username,$email,$password,$repeat_password,$captcha,$raf)
	{
        global $sql;
		$errors = array();
		if (empty($username))
			$errors[] = 'Enter a username.';
		if (empty($email))
			$errors[] = 'Enter an email address.';
		if (empty($password))
			$errors[] = 'Enter a password.';
		if (empty($repeat_password))
			$errors[] = 'Enter the password repeat.';
		if($username==$password)
			$errors[] = 'Your password cannot be your username!';

		else
		{
			session_start();
			if($GLOBALS['registration']['captcha']==true)
			{
				if($captcha!=$_SESSION['captcha_numero'])
					$errors[] = 'The captcha is incorrect!';
			}

			if (strlen($username)>$GLOBALS['registration']['userMaxLength'] || strlen($username)<$GLOBALS['registration']['userMinLength'])
				$errors[] = 'The username must be between '.$GLOBALS['registration']['userMinLength'].' and '.$GLOBALS['registration']['userMaxLength'].' letters.';

			if (strlen($password)>$GLOBALS['registration']['passMaxLength'] || strlen($password)<$GLOBALS['registration']['passMinLength'])
				$errors[] = 'The password must be between '.$GLOBALS['registration']['passMinLength'].' and '.$GLOBALS['registration']['passMaxLength'].' letters.';

			if ($GLOBALS['registration']['validateEmail']==true)
			{
			    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
				       $errors[] = 'Enter a valid email address.';
			}

		}
		$username_clean = addslashes(trim($username));
		$password_clean = addslashes(trim($password));
		$username = addslashes(trim(strtoupper(strip_tags($username))));
		$email = addslashes(trim(strip_tags($email)));
		$password = addslashes(trim(strtoupper(strip_tags($password))));
		$repeat_password = trim(strtoupper($repeat_password));
		$raf = (int)$raf;

        connect::selectDB('logondb');

		$result = $sql->query("SELECT COUNT(id) FROM account WHERE username='".$username."'");
		if ($result)
			$errors[] = 'The username already exists!';
		if ($password != $repeat_password)
			$errors[] = 'The passwords does not match!';

		if (!empty($errors))
		{
			echo "<p><h4>The following errors occured:</h4>";
            foreach($errors as $error)
            {
                echo  "<strong>*", $error ,"</strong><br/>";
            }
			echo "</p>";
			exit();
		}
		else
		{
			$password = sha1("".$username.":".$password."");
            $sql->query("INSERT INTO account (username,email,sha_pass_hash,joindate,expansion,recruiter)
			VALUES('".$username."','".$email."','".$password."','".date("Y-m-d H:i:s")."','".$GLOBALS['core_expansion']."','".$raf."') ");
			$getID = $sql->query("SELECT id FROM account WHERE username='".$username."'");
			$row = mysqli_fetch_assoc($getID);
            connect::selectDB('webdb');
            $sql->query("INSERT INTO account_data VALUES('".$row['id']."','','')");
			$result = $sql->query("SELECT id FROM account WHERE username='".$username_clean."'");
			$id = mysqli_fetch_assoc($result);
			$id = $id['id'];
			self::GMLogin($username_clean);
			$_SESSION['cw_user']=ucfirst(strtolower($username_clean));
			$_SESSION['cw_user_id']=$id;
			account::forumRegister($username_clean,$password_clean,$email);
		}
	}

	public static function forumRegister($username,$password,$email)
	{
        date_default_timezone_set($GLOBALS['timezone']);
        global $phpbb_root_path, $phpEx, $user_id, $db, $config, $cache, $template;
        if($GLOBALS['forum']['type']=='phpbb' && $GLOBALS['forum']['autoAccountCreate']==true)
        {
            define('IN_PHPBB', true);
            define('ROOT_PATH', '../..'.$GLOBALS['forum']['forum_path']);
            $phpEx = "php";
            $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : ROOT_PATH;
            if(file_exists($phpbb_root_path . 'common.' . $phpEx) && file_exists($phpbb_root_path . 'includes/functions_user.' . $phpEx))
            {
                include($phpbb_root_path.'common.'.$phpEx);
                include($phpbb_root_path.'includes/functions_user.'.$phpEx);
                $arrTime = getdate();
                $unixTime = strtotime($arrTime['year']."-".$arrTime['mon'].'-'.$arrTime['mday']." ".$arrTime['hours'].":".
                                      $arrTime['minutes'].":".$arrTime['seconds']);

                $user_row = array(
                    'username'              => $username,
                    'user_password'         => phpbb_hash($password),
                    'user_email'            => $email,
                    'group_id'              => (int) 2,
                    'user_timezone'         => (float) 0,
                    'user_dst'              => "0",
                    'user_lang'             => "en",
                    'user_type'             => 0,
                    'user_actkey'           => "",
                    'user_ip'               => $_SERVER['REMOTE_HOST'],
                    'user_regdate'          => $unixTime,
                    'user_inactive_reason'  => 0,
                    'user_inactive_time'    => 0
                );
                $user_id = user_add($user_row);
            }
        }
    }

	public static function isLoggedIn()
	{
		if (isset($_SESSION['cw_user']))
        {
			header("Location: ?p=account");
            exit;
        }
	}

	public static function isNotLoggedIn()
	{
		if (!isset($_SESSION['cw_user']))
        {
	        header("Location: ?p=login&r=".$_SERVER['REQUEST_URI']);
            exit;
        }
	}

	public static function isNotGmLoggedIn()
	{
		if (!isset($_SESSION['cw_gmlevel']))
        {
			header("Location: ?p=home");
            exit;
        }
	}

	public static function checkBanStatus($user)
	{
        global $sql;
        connect::selectDB('logondb');
		$acct_id = self::getAccountID($user);
		$result = $sql->query("SELECT bandate,unbandate,banreason FROM account_banned WHERE id='".$acct_id."' AND active=1");
		if (mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_assoc($result);
            if($row['bandate'] >= $row['unbandate'] && $row['unbandate'] < time())
            {
                return '<span class="red_text">Banned<br />
                Reason: '.$row['banreason'].'<br />
                Time left: Infinite</span>';
            }
			else
			{
                $duration = $row['unbandate'] - time();
                if ($duration > 0)
                {
                    $duration = round(($duration / 60)/60, 2);
                    $duration = $duration.' hours';
                    return '<span class="yellow_text">Banned<br/>
                    Reason: '.$row['banreason'].'<br/>
                    Time left: '.$duration.'</span>';
                }
            }
		}
        return '<b class="green_text">Active</b>';
	}

	public static function getAccountID($user)
	{
        global $sql;
		$user = addslashes($user);
        connect::selectDB('logondb');
		$result = $sql->query("SELECT id FROM account WHERE username='".$user."'");
		$row = mysqli_fetch_assoc($result);
		return $row['id'];
	}

	public static function getAccountName($id)
	{
        global $sql;
		$id = (int)$id;
        connect::selectDB('logondb');
		$result = $sql->query("SELECT username FROM account WHERE id='".$id."'");
		$row = mysqli_fetch_assoc($result);
		return $row['username'];
	}

	public function getRemember()
	{
		if (isset($_COOKIE['cw_rememberMe']) && !isset($_SESSION['cw_user'])) {
			$account_data = explode("*", $_COOKIE['cw_rememberMe']);
			$this->logIn($account_data[0],$account_data[1],$_SERVER['REQUEST_URI'],835727313);
		}
	}

	public static function loadVP($account_name)
	{
        global $sql;
		$acct_id = self::getAccountID($account_name);
        connect::selectDB('webdb');
		$result = $sql->query("SELECT vp FROM account_data WHERE id=".$acct_id);
		if (mysqli_num_rows($result) == 0)
			return 0;
		else
		{
			$row = mysqli_fetch_assoc($result);
			return $row['vp'];
		}
	}

	public static function loadDP($account_name)
	{
        global $sql;
	    $acct_id = self::getAccountID($account_name);
        connect::selectDB('webdb');
		$result = $sql->query("SELECT dp FROM account_data WHERE id=".$acct_id);
		if (mysqli_num_rows($result) == 0)
			return 0;
		else
		{
			$row = mysqli_fetch_assoc($result);
			return $row['dp'];
		}
	}

	public static function getEmail($account_name)
	{
        global $sql;
		$account_name = addslashes($account_name);
        connect::selectDB('logondb');
		$result = $sql->query("SELECT email FROM account WHERE username='".$account_name."'");
		$row = mysqli_fetch_assoc($result);
		return $row['email'];
	}

	public static function getOnlineStatus($account_name)
	{
        global $sql;
		$account_name = addslashes($account_name);
        connect::selectDB('logondb');
		$result = $sql->query("SELECT COUNT(online) FROM account WHERE username='".$account_name."' AND online=1");
		if (!$result)
			return '<b class="red_text">Offline</b>';
		else
			return '<b class="green_text">Online</b>';
	}

	public static function getJoindate($account_name)
	{
        global $sql;
		$account_name = addslashes($account_name);
        connect::selectDB('logondb');
		$result = $sql->query("SELECT joindate FROM account WHERE username='".$account_name."'");
		$row = mysqli_fetch_assoc($result);
		return $row['joindate'];
	}

	public static function GMLogin($account_name)
	{
        global $sql;
        connect::selectDB('logondb');
		$acct_id = self::getAccountID($account_name);
		$result = $sql->query("SELECT gmlevel FROM account_access WHERE gmlevel > 2 AND id=".$acct_id);
		if(mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_assoc($result);
			$_SESSION['cw_gmlevel']=$row['gmlevel'];
		}

	}

	public static function getCharactersForShop($account_name)
	{
        global $sql;
		$acct_id = self::getAccountID($account_name);
        connect::selectDB('webdb');
		$getRealms = $sql->query("SELECT id,name FROM realms");
		while($row = mysqli_fetch_assoc($getRealms))
		{
			connect::connectToRealmDB($row['id']);
			$result = $sql->query("SELECT name,guid FROM characters WHERE account='".$acct_id."'");
			if(mysqli_num_rows($result) == 0 && !isset($x))
			{
				$x = true;
			     echo '<option value="">No characters found!</option>';
			}
			while($char = mysqli_fetch_assoc($result))
			{
				echo '<option value="'.$char['guid'].'*'.$row['id'].'">'.$char['name'].' - '.$row['name'].'</option>';
			}
		}
	}

	public static function changeEmail($email,$current_pass)
	{
        global $sql;
		$errors = array();
		if (empty($current_pass))
			$errors[] = 'Please enter your current password';
		else
		{
			if (empty($email))
				$errors[] = 'Please enter an email address.';

            connect::selectDB('logondb');
			$id = $_SESSION['cw_user_id'];
			$username = addslashes(trim(strtoupper($_SESSION['cw_user'])));
			$password = addslashes(trim(strtoupper($current_pass)));
			$password = sha1("".$username.":".$password."");
			$result = $sql->query("SELECT COUNT(id) FROM account WHERE id='".$id."' AND sha_pass_hash='".$password."'");
			if (!$result)
				$errors[] = 'The current password is incorrect.';
			if ($GLOBALS['registration']['validateEmail']==true)
			{
			    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
				    $errors[] = 'Enter a valid email address.';
			}

		}
        echo '<div class="news" style="padding: 5px;">';
		if(empty($errors))
        {
            $sql->query("UPDATE account SET email='".$email."' WHERE id='".$_SESSION['cw_user_id']."'");
			echo '<h4 class="green_text">Successfully updated your account</h4>';
        }
		else
		{
			echo '
			<h4 class="red_text">The following errors occured:</h4>';
				   foreach($errors as $error)
				   {
					 echo  '<strong class="yellow_text">*', $error ,'</strong><br/>';
				   }
		}
        echo '</div>';
	}

	public static function changePass($old,$new,$new_repeat)
	{
		global $sql;
		if (!isset($_POST['cur_pass']) || !isset($_POST['new_pass']) || !isset($_POST['new_pass_repeat']))
			echo '<b class="red_text">Please type in all fields!</b>';
	    else
		{
            $_POST['cur_pass'] = addslashes(trim($old));
            $_POST['new_pass'] = addslashes(trim($new));
            $_POST['new_pass_repeat'] = addslashes(trim($new_repeat));

			if ($_POST['new_pass'] != $_POST['new_pass_repeat'])
				echo '<b class="red_text">The new passwords doesnt match!</b>';
			else
			{
			  if (strlen($_POST['new_pass']) < $GLOBALS['registration']['passMinLength'] ||
			      strlen($_POST['new_pass']) > $GLOBALS['registration']['passMaxLength'])
				  echo '<b class="red_text">Your password must be between 6 and 32 letters</b>';
			  else
			  {
                    $username = strtoupper(addslashes($_SESSION['cw_user']));
                    connect::selectDB('logondb');
                    $getPass = $sql->query("SELECT `sha_pass_hash` FROM `account` WHERE `id`='".$_SESSION['cw_user_id']."'");
                    $row = mysqli_fetch_assoc($getPass);
                    $thePass = $row['sha_pass_hash'];
                    $pass = addslashes(strtoupper($_POST['cur_pass']));
                    $pass_hash = sha1($username.':'.$pass);
                    $new_pass = addslashes(strtoupper($_POST['new_pass']));
                    $new_pass_hash = sha1($username.':'.$new_pass);
                    if ($thePass != $pass_hash)
                        echo '<b class="red_text">The old password is not correct!</b>';
                    else
                    {
                        echo 'Your Password was changed!';
                        if (isset($_COOKIE['cw_rememberMe']))
                            setcookie("cw_rememberMe", $username.' * '.$new_pass, time()+30758400);
                        $sql->query("UPDATE account SET sha_pass_hash='".$new_pass_hash."', v='0', s='0' WHERE id='".$_SESSION['cw_user_id']."'");
                    }
			    }
		    }
		}
	}

	public static function changePassword($account_name,$password)
	{
        global $sql;
        $username = addslashes(strtoupper($account_name));
        $pass = addslashes(strtoupper($password));
        $pass_hash = sha1($username.':'.$pass);
        connect::selectDB('logondb');
        $sql->query("UPDATE `account` SET `sha_pass_hash`='{$pass_hash}', v='0', s='0' WHERE `id`='".$_SESSION['cw_user_id']."'");
        account::logThis("Changed password","passwordchange",NULL);
	}

	public static function changeForgottenPassword($account_name,$password)
	{
        global $sql;
        connect::selectDB('logondb');
        $username = addslashes(strtoupper($account_name));
        $result = $sql->query("SELECT * FROM account WHERE username='".$username."'");
        $row = mysqli_fetch_array($result);
        if($row)
        {
            $password = strtoupper($password);
            $password_hash = addslashes(sha1($username.':'.$password));
            connect::selectDB('logondb');
            $sql->query("UPDATE `account` SET `sha_pass_hash`='".$password_hash."', `v`='0', `s`='0' WHERE `id`='".$row['id']."'");
            account::logThis($account_name." Successfully recovered password","passwordrecoverd",NULL);
        }
    }

	public static function forgotPW($account_name, $account_email)
	{
        global $sql;
		$account_name = addslashes($account_name);
		$account_email = addslashes($account_email);
		if (empty($account_name) || empty($account_email))
			echo '<b class="red_text">Please enter both fields.</b>';
		else
		{
            connect::selectDB('logondb');
			$result = $sql->query("SELECT COUNT('id') FROM account WHERE username='".$account_name."' AND email='".$account_email."'");
			if (!$result)
				echo '<b class="red_text">The username or email is incorrect.</b>';
			else
			{
				$code = RandomString();
				$emailSent = website::sendEmail($account_email,$GLOBALS['default_email'],'Forgot Password',"
				Hello, <br/><br/>
				A password reset has been requested for the account ".$account_name." <br/>
				If you requested to reset your password, click the following link: <br/>
				<a href='".$GLOBALS['website_domain']."?p=forgotpw&code=".$code."&account=".account::getAccountID($account_name)."'>
				".$GLOBALS['website_domain']."?p=forgotpw&code=".$code."&account=".account::getAccountID($account_name)."</a>

				<br/><br/>

				If you did not request a password reset, then just ignore this message.<br/><br/>
				Sincerely,
				Management.");
                if ($emailSent)
                {
				    $account_id = self::getAccountID($account_name);
                    connect::selectDB('webdb');
                    $sql->query("DELETE FROM password_reset WHERE account_id='".$account_id."'");
                    $sql->query("INSERT INTO password_reset (code,account_id)
				    VALUES ('".$code."','".$account_id."')");
				    echo "An email containing a link to reset your password has been sent to the Email address you specified.
					      If you've tried to send other forgot password requests before this, they won't work. <br/>";
                }
                else
                {
                    echo '<h4 class="red_text">Failed to send email! (Check error logs for details)</h4>';
                }
			}
		}
	}

    public static function hasVP($account_name,$points)
    {
        global $sql;
        $points = (int)$points;
        $account_id = self::getAccountID($account_name);
        connect::selectDB('webdb');
        $result = $sql->query("SELECT COUNT('id') FROM account_data WHERE vp >= '".$points."' AND id='".$account_id."'");
        if (!$result)
            return false;
        else
            return true;
    }

    public static function hasDP($account_name,$points)
    {
        global $sql;
        $points = (int)$points;
        $account_id = self::getAccountID($account_name);
        connect::selectDB('webdb');
        $result = $sql->query("SELECT COUNT('id') FROM account_data WHERE dp >= '".$points."' AND id='".$account_id."'");
        if (!$result)
            return false;
        else
            return true;
    }

    public static function deductVP($account_id,$points)
    {
        global $sql;
        $points = (int)$points;
        $account_id = (int)$account_id;
        connect::selectDB('webdb');
        $sql->query("UPDATE account_data SET vp=vp - ".$points." WHERE id='".$account_id."'");
    }

    public static function deductDP($account_id,$points)
    {
        global $sql;
        $points = (int)$points;
        $account_id = (int)$account_id;
        connect::selectDB('webdb');
        $sql->query("UPDATE account_data SET dp=dp - ".$points." WHERE id='".$account_id."'");
    }

    public static function addDP($account_id,$points)
    {
        global $sql;
        $account_id = (int)$account_id;
        $points = (int)$points;
        connect::selectDB('webdb');
        $sql->query("UPDATE account_data SET dp=dp + ".$points." WHERE id='".$account_id."'");
    }

    public static function addVP($account_id,$points)
    {
        global $sql;
        $account_id = (int)$account_id;
        $points = (int)$points;
        connect::selectDB('webdb');
        $sql->query("UPDATE account_data SET dp=dp + ".$points." WHERE id='".$account_id."'");
    }

    public static function getAccountIDFromCharId($char_id,$realm_id)
    {
        global $sql;
        $char_id = (int)$char_id;
        $realm_id = (int)$realm_id;
        connect::selectDB('webdb');
        connect::connectToRealmDB($realm_id);
        $result = $sql->query("SELECT account FROM characters WHERE guid='".$char_id."'");
        $row = mysqli_fetch_assoc($result);
        return $row['account'];
    }

    public static function isGM($account_name)
    {
        global $sql;
         $account_id = self::getAccountID($account_name);
         $result = $sql->query("SELECT COUNT(id) FROM account_access WHERE id='".$account_id."' AND gmlevel >= 1");
         if ($result)
             return true;
         else
             return false;
    }

    public static function logThis($desc,$service,$realmid)
    {
        global $sql;
        $desc = addslashes($desc);
        $realmid = (int)$realmid;
        $service = addslashes($service);
        $account = (int)$_SESSION['cw_user_id'];
        connect::selectDB('webdb');
        $sql->query("INSERT INTO user_log VALUES(NULL,'".$account."','".$service."','".time()."','".$_SERVER['REMOTE_ADDR']."','".$realmid."','".$desc."')");
    }
}