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

	if(!defined('INIT_SITE'))
		exit();

    /*
        Config Settings
        Enable = true
        Disable = false
    */

    // Enable/Disable maintenance mode
    $maintainance = false;
    $maintainance_allowIPs = array('127.0.0.1', '127.0.0.2'); // Allowed IP's able to view the site while in maintenance

    // Site settings
    $website_title = 'CraftedWeb';
    $default_email = 'noreply@emudevs.com';
    $website_domain = 'http://emudevs.com/_cw'; // Path of your website, ie: http://craftedweb.com/

    // Show page loading time
    $showLoadTime = false;

    // Footer message
    $footer_text = '&copy;2014 - <a href="http://emudevs.com">EmuDevs.com</a> - CraftedWeb<BR />coded by <a href="http://emudevs.com">Faded</a>';

    // Set timezone, for a full list see: php.net/manual/en/timezones.php
    $timezone = 'Europe/Belgrade';

    //The expansion of your server.
    $core_expansion = 2;// 0 = Vanilla
                        // 1 = The Burning Crusade
                        // 2 = Wrath of The Lich King
                        // 3 = Cataclysm

    // Enable/Disable staff panels
    $adminPanel_enable = true;
    $staffPanel_enable = true;

    // Admin access level
    $adminPanel_minlvl = 5; // Min level for full admin privileges to the panel. Default: 5
    // Staff settings, limit access to staff below the $adminPanel minimum
    $staffPanel_minlvl = 3; // Min level for staff, to access the staff panel features below. Default: 3
    $staffPanel_permissions['Pages'] = false;
    $staffPanel_permissions['News'] = false;
    $staffPanel_permissions['Shop'] = false;
    $staffPanel_permissions['Donations'] = false;
    $staffPanel_permissions['Logs'] = true;
    $staffPanel_permissions['Interface'] = false;
    $staffPanel_permissions['Users'] = true;
    $staffPanel_permissions['Realms'] = false;
    $staffPanel_permissions['Services'] = false;
    $staffPanel_permissions['Tools->Tickets'] = true;
    $staffPanel_permissions['Tools->Account Access'] = false;
    $staffPanel_permissions['editNewsComments'] = true;
    $staffPanel_permissions['editShopItems'] = false;

    // Enable/Disable Plugins
	$enablePlugins = true;

    // Enable/Disable Slide Show
	$enableSlideShow = true;

    // News settings
	$news['enable'] = true;
	$news['maxShown'] = 2; // Max amount of new posts to show
	$news['enableComments'] = true;
	$news['limitHomeCharacters'] = false; // Ignore
	
	// Server status settings
	$serverStatus['enable'] = true; // This will enable/disable the server status box.
	$serverStatus['nextArenaFlush'] = false; //This will display the next arena flush for your realm.
	$serverStatus['uptime'] = true; // This will display the uptime of your realm.
	$serverStatus['playersOnline'] = true; // This will show current players online
	$serverStatus['factionBar'] = true; // This will show the players online faction bar.
	
    // MySQL settings
    $connection['host'] = '127.0.0.1';
    $connection['user'] = 'user';
    $connection['password'] = 'pass';
    $connection['logondb'] = 'auth';
    $connection['webdb'] = 'testing';
    $connection['worlddb'] = 'world';
    $connection['realmlist'] = 'logon.myrealm.com';

    // Registration settings
	$registration['userMaxLength'] = 16; // Max length of username
	$registration['userMinLength'] = 3; // Minimum length of username
	$registration['passMaxLength'] = 22; // Max length of password
	$registration['passMinLength'] = 5; // Minimum length of password
	$registration['validateEmail'] = false; // Enable/Disable email validation
	$registration['captcha'] = false; // Enable/Disable captcha

    // Vote settings
	$vote['timer'] = 43200; // Time between votes (ms)
	$vote['type'] = 'instant';
	$vote['multiplier'] = 1; // Amount of points given per vote

    // Paypal donation settings
    // coins_name = The name of the donation coins that the user will buy.
    // currency = The name of the currency that you want the user to pay with. Default: USD
    // sendResponseCopy = Set this to "true" if you wish to recieve a copy of the email response mentioned above.
    // copyTo = Enable the sendResponseCopy to activate this function. Enter the email address of wich the payment copy will be sent to.
    // donationType = How the user will donate. 1 = They can enter how many coins they wish to buy, and the value can be increased with the multiplier.
	$donation['paypal_email'] = 'paypal@emudevs.com';
	$donation['coins_name'] = 'Donations Coins';
	$donation['currency'] = 'USD';
	$donation['emailResponse'] = true;
	$donation['sendResponseCopy'] = false;
	$donation['copyTo'] = 'noreply@emudevs.com';
	$donation['responseSubject'] = 'Thanks for your support!';
	$donation['donationType'] = 2;
    // Edit the donation amounts here
    // NOTE: You must have donation type set to '2' above, to use custom values
	$donationList = array(
			array('10 Donation Coins - 5$', 10, 5),
			array('20 Donation Coins - 8$', 20, 8),
			array('50 Donation Coins - 20$', 50, 20),
			array('100 Donation Coins - 35$', 100, 35 ),
			array('200 Donation Coins - 70$', 200, 70 )
	);
	

    // Enable/Disable vote & donation shop
    // enableShop = Enables/disables the use of the Vote Shop. "true" = enable, "false" = disable.
    // enableAdvancedSearch = Enabled/disables the use of the advanced search feature. "true" = enable, "false" = disable.
    // shopType = The type of shop you wish to use. 1 = "Search". 2 = List all items available.
	$voteShop['enableShop'] = true;
	$voteShop['enableAdvancedSearch'] = true;
	$voteShop['shopType'] = 1;
    $donateShop['enableShop'] = true;
    $donateShop['enableAdvancedSearch'] = true;
    $donateShop['shopType'] = 1;

    //Enable/Disable Facebook integration
	$social['enableFacebookModule'] = false;
	$social['facebookGroupURL'] = 'http://www.facebook.com/YourServer';

    //This is being removed, disregard
	$forum['type'] = 'phpbb';
	$forum['autoAccountCreate'] = false;
	$forum['forum_path'] = '/forum/';
	$forum['forum_db'] = 'phpbb';
    //--------------------------------

    //Don't touch
	$core_pages = array('Account Panel' => 'account.php','Shopping Cart' => 'cart.php',
	'Change Password' => 'changepass.php','Donate' => 'donate.php','Donation Shop' => 'donateshop.php',
	'Forgot Password' => 'forgotpw.php','Home' => 'home.php','Logout' => 'logout.php',
	'News' => 'news.php','Refer-A-Friend' => 'raf.php','Register' => 'register.php',
	'Character Revive' => 'revive.php','Change Email' => 'settings.php','Support' => 'support.php',
	'Character Teleport' => 'teleport.php','Character Unstucker' => 'unstuck.php','Vote' => 'vote.php',
	'Vote Shop' => 'voteshop.php','Confirm Service' => 'confirmservice.php');
	
	// Max item level per expansion
	switch($GLOBALS['core_expansion']) 
	{
		case(0):
		$maxItemLevel = 100;
		break;
		case(1):
		$maxItemLevel = 175;
		break;
		default:
		case(2):
		$maxItemLevel = 284;
		break;
		case(3):
		$maxItemLevel = 416;
		break;
	}

    // Leave this alone
    $compression['gzip'] = true;
    $compression['sanitize_output'] = true;
    $useDebug = false;
    $useCache = false;
	if($GLOBALS['core_expansion']>2) 
		$tooltip_href = 'www.wowhead.com/';
	else
		$tooltip_href = 'www.openwow.com/?';
	date_default_timezone_set($GLOBALS['timezone']);
	if(file_exists('includes/classes/error.php'))
		require('includes/classes/error.php');
	elseif(file_exists('../classes/error.php'))
		require('../classes/error.php');
	elseif(file_exists('../includes/classes/error.php'))
		require('../includes/classes/error.php');
	elseif(file_exists('../../includes/classes/error.php'))
		require('../../includes/classes/error.php');
	elseif(file_exists('../../../includes/classes/error.php'))
		require('../../../includes/classes/error.php');