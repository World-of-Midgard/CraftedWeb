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
class character
{
	public static function unstuck($guid,$char_db) 
	{
        global $sql;
		$guid = (int)$guid;
		$rid = server::getRealmId($char_db);
		connect::connectToRealmDB($rid);
        if(character::isOnline($guid) == true)
			echo '<b class="red_text">Please log out your character before proceeding.';
		else 
		{
			if($GLOBALS['service']['unstuck']['currency']=='vp')
			{
				if(account::hasVP($_SESSION['cw_user'],$GLOBALS['service']['unstuck']['price'])==false)
					die('<b class="red_text">Not enough Vote Points!</b>' );
				else
					account::deductVP(account::getAccountID($_SESSION['cw_user']),$GLOBALS['service']['unstuck']['price']);	
		    }
			if($GLOBALS['service']['unstuck']['currency']=='dp')
			{
				if(account::hasDP($_SESSION['cw_user'],$GLOBALS['service']['unstuck']['price'])==false)
					die('<b class="red_text">Not enough '.$GLOBALS['donation']['coins_name'].'</b>');
				else
					account::deductDP(account::getAccountID($_SESSION['cw_user']),$GLOBALS['service']['unstuck']['price']);
		    }
            $getXYZ = $sql->query("SELECT * FROM character_homebind WHERE guid='".$guid."'");
            $row = mysqli_fetch_assoc($getXYZ);
            $new_x = $row['posX'];
            $new_y = $row['posY'];
            $new_z = $row['posZ'];
            $new_zone = $row['zoneId'];
            $new_map = $row['mapId'];
            $sql->query("UPDATE characters SET position_x='".$new_x."', position_y='".$new_y."',
            position_z='".$new_z."', zone='".$new_zone."',map='".$new_map."' WHERE guid='".$guid."'");
            account::logThis("Performed unstuck on ".character::getCharName($guid,$rid),'Unstuck',$rid);
            return true;
	    }
        return false;
	}
	
	public static function revive($guid,$char_db) 
	{
        global $sql;
		$guid = (int)$guid;
		$rid = server::getRealmId($char_db);
		connect::connectToRealmDB($rid);
		if(character::isOnline($guid) == true)
			echo '<b class="red_text">Please log out your character before proceeding.';
	    else 
		{
			if($GLOBALS['service']['revive']['currency']=='vp')
			{
				if(account::hasVP($_SESSION['cw_user'],$GLOBALS['service']['unstuck']['price'])==false)
					die('<b class="red_text">Not enough Vote Points!</b>');
				else
					account::deductVP(account::getAccountID($_SESSION['cw_user']),$GLOBALS['service']['revive']['price']);	
			}
            if($GLOBALS['service']['revive']['currency']=='dp')
            {
                if(account::hasDP($_SESSION['cw_user'],$GLOBALS['service']['unstuck']['price'])==false)
                    die( '<b class="red_text">Not enough '.$GLOBALS['donation']['coins_name'].'</b>' );
                else
                    account::deductDP(account::getAccountID($_SESSION['cw_user']),$GLOBALS['service']['revive']['price']);
            }
            $sql->query("DELETE FROM character_aura WHERE guid = '".$guid."' AND spell = '20584' OR guid = '".$guid."' AND spell = '8326'");
            account::logThis("Performed a revive on ".character::getCharName($guid,$rid),'Revive',$rid);
            return true;
	    }
        return false;
	}
	
	public static function instant80($values) 
	{
        global $sql, $error;
		//die("This feature is disabled. <br/><i>Also, you shouldn't be here...</i>");
        $values = addslashes($values);
        $values = explode("*",$values);
		connect::connectToRealmDB($values[1]);
		if(character::isOnline($values[0])==true)
			echo '<b class="red_text">Please log out your character before proceeding.';
		else 
		{
            $service_values = explode("*",$GLOBALS['service']['instant80']);
            if ($service_values[1]=="dp")
            {
                if(account::hasDP($_SESSION['cw_user'],$GLOBALS['service']['instant80']['price'])==false)
                {
                    echo '<b class="red_text">Not enough '.$GLOBALS['donation']['coins_name'].'</b>';
                    $error = true;
                }
            }
            elseif($service_values[1]=="vp")
            {
                if(account::hasVP($_SESSION['cw_user'],$GLOBALS['service']['instant80']['price'])==false)
                {
                    echo '<b class="red_text">Not enough Vote Points.</b>';
                    $error = true;
                }
            }
            if ($error!=true)
            {
                connect::connectToRealmDB($values[1]);
                $sql->query("UPDATE characters SET level='80' WHERE guid = '".$values[0]."'");
                account::logThis("Performed an instant max level on ".character::getCharName($values[0],NULL),'Instant',NULL);
                echo '<h3 class="green_text">The character level was set to 80!</h3>';
            }
	    }
        return $values;
    }
 
 public static function isOnline($char_guid) 
 {
     global $sql;
	 $char_guid = (int)$char_guid;
	 $result = $sql->query("SELECT COUNT('guid') FROM characters WHERE guid='".$char_guid."' AND online=1");
	 if (!$result)
		 return false;
	 else 
		 return true;
  }
  
  public static function getRace($value) 
  {
	  switch($value) 
	  {
		 default:
			 return "Unknown";
		    break;
		 case(1):
		 	return "Human";
		    break;
		 case(2):
		 	return "Orc";
		    break;
		 case(3):
			 return "Dwarf";
		    break;
		 case(4):
		 	return "Night Elf";
		    break;
		 case(5):
		 	return "Undead";
		    break;
		 case(6):
			 return "Tauren";
		    break;
		 case(7):
		 	return "Gnome";
		    break;
		 case(8):
		 	return "Troll";
		    break;
		 case(9):
			 return "Goblin";
		    break;
		 case(10):
			return "Blood Elf";
		    break;
		 case(11):
		 	return "Dranei";
		    break;
		 case(22):
			 return "Worgen";
		    break;
	  }
  }
  
  public static function getGender($value) 
  {
	 if($value==1) 
		 return 'Female';
	 elseif($value==0)
		 return 'Male';
	 else 
		 return 'Unknown';
  }
  
      public static function getClass($value)
      {
          switch($value)
          {
             default:
                return "Unknown";
                break;
             case(1):
                return "Warrior";
                break;
             case(2):
                return "Paladin";
                break;
             case(3):
                 return "Hunter";
                break;
             case(4):
                 return "Rogue";
                break;
             case(5):
                 return "Priest";
                break;
             case(6):
                return "Death Knight";
                break;
             case(7):
                 return "Shaman";
                break;
             case(8):
                return "Mage";
                break;
             case(9):
                return "Warlock";
                break;
             case(11):
                return "Druid";
                break;
             case(12):
                return "Monk";
                break;
          }
    }
  
    public static function getClassIcon($value)
    {
      return '<img src="styles/global/images/icons/class/'.$value.'.gif" />';
    }

    public static function getFactionIcon($value)
    {
        global $icon;
       $a = array(1,3,4,7,11,22);
       $h = array(2,5,6,8,9,10);
       if(in_array($value,$a))
           $icon = '<img src="styles/global/images/icons/faction/0.gif" />';
       elseif(in_array($value,$h))
           $icon = '<img src="styles/global/images/icons/faction/1.gif" />';
       return $icon;
    }

    public static function getCharName($id,$realm_id)
    {
        global $sql;
        $id = (int)$id;
        connect::connectToRealmDB($realm_id);
        $result = $sql->query("SELECT name FROM characters WHERE guid='".$id."'");
        $row = mysqli_fetch_assoc($result);
        return $row['name'];
    }

    public static function isAccountCharacter($char_guid, $acct_id)
    {
        global $sql;
        $char_guid = (int)$char_guid;
        $acct_id = (int)$acct_id;
        $result = $sql->query("SELECT COUNT('guid') FROM characters WHERE guid='{$char_guid}' AND account = '{$acct_id}'");
        if (!$result)
            return false;
        else
            return true;
    }
}