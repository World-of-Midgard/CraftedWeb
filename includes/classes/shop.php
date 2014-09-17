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
class shop
{
	public function search($value,$shop,$quality,$type,$ilevelfrom,$ilevelto,$results,$faction,$class)
	{
        global $sql;
		connect::selectDB('webdb');
		if ($shop == 'vote')
			$shopGlobalVar = $GLOBALS['voteShop']; 
		elseif($shop == 'donate')
			$shopGlobalVar = $GLOBALS['donateShop']; 
		
		$value = addslashes($value);
		$shop = addslashes($shop);
		$quality = (int)$quality;
		$ilevelfrom = (int)$ilevelfrom;
		$ilevelto = (int)$ilevelto;
		$results = (int)$results;
		$faction = (int)$faction;
		$class = (int)$class;
		$type = addslashes($type);
		
		if($value == "Search for an item...")
			$value = "";
		$advanced = NULL;
		if($GLOBALS[$shop.'Shop']['enableAdvancedSearch'] == true)
		{
			if($quality != "--Quality--")
				$advanced .= " AND quality='".$quality."'";
			
			if($type != "--Type--")
            {
				if($type == "15-5" || $type=="15-5")
                {
					$type = explode('-',$type);
					$advanced.=" AND type='".$type[0]."' AND subtype='".$type[1]."'";
				}
                else
					$advanced.=" AND type='".$type."'";
			} 
			
			if($faction != "--Faction--")
				$advanced .= " AND faction='".$faction."'";
			
			if($class != "--Class--")
				$advanced .= " AND class='".$class."'";
			
			if($ilevelfrom != "--Item level from--")
				$advanced .= " AND itemlevel>='".$ilevelfrom."'";
			
			if($ilevelto != "--Item level to--")
				$advanced .= " AND itemlevel<='".$ilevelto."'";

			$count = $sql->query("SELECT COUNT(id) AS count FROM shopitems WHERE name LIKE '%".$value."%' AND in_shop = '".$shop."' ".$advanced);
		    $result_count =  mysqli_fetch_assoc($count);
			if($result_count['count'] == 0)
				$count = 0;
			 else 
				$count = $result_count['count'];
				
			
			if($results != "--Results--")
				$advanced .= " ORDER BY name ASC LIMIT ".$results;
			 else 
				$advanced .= " ORDER BY name ASC LIMIT 250";
		}
		$result = $sql->query("SELECT entry,displayid,name,quality,price,faction,class
		FROM shopitems WHERE name LIKE '%".$value."%' 
		AND in_shop = '".addslashes($shop)."' ".$advanced);
		
		if($results != "--Results--")
			$limited = $results;
		 else 
			$limited = mysqli_num_rows($result);
		
	    echo "<div class='shopBox'><b>".$count."</b> results found. (".$limited." displayed)</div>";
		
		if (mysqli_num_rows($result) == 0)
			echo '<b class="red_text">No results found!</b><br/>';
		 else 
		 {
			while($row = mysqli_fetch_assoc($result))
			{
				$entry = $row['entry'];
				switch($row['quality'])
                {
                    default:
                        $class="white";
                        break;
                    case(0):
                        $class="gray";
                        break;
                    case(2):
                        $class="green";
                        break;
                    case(3):
                        $class="blue";
                        break;
                    case(4):
                        $class="purple";
                        break;
                    case(5):
                        $class="orange";
                        break;

                    case(6):
                        $class="gold";
                        break;
                    case(7):
                        $class="gold";
                        break;
				}
				
				 $getIcon = $sql->query("SELECT icon FROM item_icons WHERE displayid='".$row['displayid']."'");
				 if(!$getIcon)
				 {

					 $sxml = new SimpleXmlElement(file_get_contents('http://www.wowhead.com/item='.$entry.'&xml'));
					 $icon = strtolower(addslashes($sxml->item->icon));
                     $sql->query("INSERT INTO item_icons VALUES('".$row['displayid']."','".$icon."')");
				 }
				 else 
				 {
				   $iconrow = mysqli_fetch_assoc($getIcon);
				   $icon = strtolower($iconrow['icon']);
				 }
				?>
                <div class="shopBox" id="item-<?php echo $entry; ?>"> 
                    <table>
                           <tr> 
                               <td>
                                   <div class="iconmedium icon" rel="50818">
                                     <ins style="background-image: url('http://static.wowhead.com/images/wow/icons/medium/<?php echo $icon; ?>.jpg');">
                                     </ins>
                                     <del></del>
                                     </div>
                               </td>
                               <td width="380">
                                    <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $entry; ?>" 
                                       class="<?php echo $class; ?>_tooltip" target="_blank">
                                       <?php echo $row['name']; ?></a>
                               </td>
                              <td align="right" width="350">
								   <?php 
								   if($row['faction']==2) 
								   {
                                     echo "<span class='blue_text'>Alliance only </span>";  
                                     if($row['class']!="-1")
                                     	echo "<br/>";
                                   } 
								   elseif($row['faction']==1) 
								   {
                                     echo "<span class='red_text'>Horde only </span>"; 
                                     if($row['class']!="-1")
                                     	echo "<br/>";
                                   }
                                       
								   if($row['class']!="-1") 
									 echo shop::getClassMask($row['class']);
								   
								   
								   if(isset($_SESSION['cw_gmlevel']) && $_SESSION['cw_gmlevel']>=$GLOBALS['adminPanel_minlvl'] || 
								   isset($_SESSION['cw_gmlevel']) && $_SESSION['cw_gmlevel']>=$GLOBALS['staffPanel_minlvl'] && $GLOBALS['editShopItems']==true)
								   {
									   ?>
								 <font size="-2">
								 ( <a onclick="editShopItem('<?php echo $entry; ?>','<?php echo $shop; ?>','<?php echo $row['price']; ?>')">Edit</a> | 
								   <a onclick="removeShopItem('<?php echo $entry; ?>','<?php echo $shop; ?>')">Remove</a> )
								 </font>
								 &nbsp; &nbsp; &nbsp; &nbsp;   
								 <?php
								  }
								   
								  ?>
								  <font class="shopItemPrice"><?php echo $row["price"]; ?> 
								  <?php 
								  if ($shop=="donate") 
								   	  echo $GLOBALS['donation']['coins_name'];
								  else 
									  echo 'Vote Points';   
								  ?>
                                  </font>
							 
						   <div style="display:none;" id="status-<?php echo $entry; ?>" class="green_text">
						   The item was added to your cart
						   </div>
                           </td>
                           <td><input type="button" value="Add to cart" onclick="addCartItem(<?php echo $entry; ?>,'<?php echo $shop; ?>Cart',
                               '<?php echo $shop; ?>',this)"> 
                               
                           </td> 
                        </tr> 
                    </table> 
                </div>
                <?php
			}
		}
	}
	
	public function listAll($shop)
	{
        global $sql;
		connect::selectDB('webdb');
		$shop = addslashes($shop);
		$result = $sql->query("SELECT entry,displayid,name,quality,price,faction,class
		FROM shopitems WHERE in_shop = '".$shop."'");
		
		if(!$result)
			echo 'No items was found in the shop.';
		else
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$entry = $row['entry'];
				$getIcon = $sql->query("SELECT icon FROM item_icons WHERE displayid='".$row['displayid']."'");
				 if(!$getIcon)
				 {
					 $sxml = new SimpleXmlElement(file_get_contents('http://www.wowhead.com/item='.$entry.'&xml'));
					 $icon = strtolower(addslashes($sxml->item->icon));
                     $sql->query("INSERT INTO item_icons VALUES('".$row['displayid']."','".$icon."')");
				 }
				 else 
				 {
				   $iconrow = mysqli_fetch_assoc($getIcon);
				   $icon = strtolower($iconrow['icon']);
				 }
				?>
                <div class="shopBox" id="item-<?php echo $entry; ?>"> 
                   <table>
                          <tr> 
                           <td>
                            <div class="iconmedium icon" rel="50818">
                                 <ins style="background-image: url('http://static.wowhead.com/images/wow/icons/medium/<?php echo $icon; ?>.jpg');">
                                 </ins>
                                 <del></del>
                                 </div>
                           </td>
                               <td width="380">
                               		<a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $entry; ?>" 
									class="<?php echo $class; ?>_tooltip" target="_blank">
                               			<?php echo $row['name']; ?>
                                    </a>
                               </td>
                               <td align="right" width="350">
                               <?php if($row['faction'] == 2)
							   {
                                 echo "<span class='blue_text'>Alliance only </span>";  
                                 if($row['class'] != "-1")
                                	 echo "<br/>";
                               } 
							   elseif($row['faction'] == 1)
							   {
                                 echo "<span class='red_text'>Horde only </span>"; 
                                 if($row['class'] != "-1")
                                	 echo "<br/>";
                               }
                               
                               if($row['class'] != "-1") {
                                 echo shop::getClassMask($row['class']);
                               }
                               
                               if(isset($_SESSION['cw_gmlevel']) && $_SESSION['cw_gmlevel']>=5)
                               {
                             ?>
                             <font size="-2">
                                 ( <a onclick="editShopItem('<?php echo $entry; ?>','<?php echo $shop; ?>','<?php echo $row['price']; ?>')">Edit</a> | 
                                 <a onclick="removeShopItem('<?php echo $entry; ?>','<?php echo $shop; ?>')">Remove</a> )
                             </font>
                             &nbsp; &nbsp; &nbsp; &nbsp;   
                             <?php
                               }
                               
                               ?>
                               <font class="shopItemPrice"><?php echo $row["price"]; ?> 
                               <?php 
							   if ($shop=="donate") 
                               		echo $GLOBALS['donation']['coins_name'];
							   else 
                               		echo 'Vote Points';   
							   ?>
                               </font>
                         
                       <div style="display:none;" id="status-<?php echo $entry; ?>" class="green_text">
                       		The item was added to your cart
                       </div>
                       </td>
                       <td>
                       		<input type="button" value="Add to cart" 
                       	    onclick="addCartItem(<?php echo $entry; ?>,'<?php echo $shop; ?>Cart',
                       		'<?php echo $shop; ?>',this)"> 
                       </td> 
                   </tr> 
                </table> 
            </div>
            <?php
			}
		}
	}

	public function logItem($shop,$entry,$char_id,$account,$realm_id,$amount) 
	{
        global $sql;
		connect::selectDB('webdb');
		date_default_timezone_set($GLOBALS['timezone']);
        $sql->query("INSERT INTO shoplog VALUES (NULL,'".(int)$entry."','".(int)$char_id."','".date("Y-m-d H:i:s")."',
		'".$_SERVER['REMOTE_ADDR']."','".addslashes($shop)."','".(int)$account."','".(int)$realm_id."','".(int)$amount."')");
	}
	
	public static function getClassMask($classID)
    {
        global $class;
        switch((int)$classID)
        {
            case(1):
                $class = "<span class='warrior_color'>Warrior only</span> <br/>";
                break;
            case(2):
                $class = "<span class='paladin_color'>Paladin only</span> <br/>";
                break;
            case(4):
                $class = "<span class='hunter_color'>Hunter only</span> <br/>";
                break;
            case(8):
                $class = "<span class='rogue_color'>Rogue only</span> <br/>";
                break;
            case(16):
                $class = "<span class='priest_color'>Priest only</span> <br/>";
                break;
            case(32):
                $class = "<span class='dk_color'>Death Knight only</span> <br/>";
                break;
            case(64):
                $class = "<span class='shaman_color'>Shaman only</span> <br/>";
                break;
            case(128):
                $class = "<span class='mage_color'>Mage only</span> <br/>";
                break;
            case(256):
                $class = "<span class='warlock_color'>Warlock only</span> <br/>";
                break;
            case(1024):
                $class = "<span class='druid_color'>Druid only</span> <br/>";
                break;
        }
        return $class;
	}
}