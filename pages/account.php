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

account::isNotLoggedIn();
?>
<div class='box_two_title'>My Account</div>
<table style="width: 100%; margin-top: -15px;">
<tr>
<td><span class='blue_text'>Account name</span></td><td> <?php echo ucfirst(strtolower($_SESSION['cw_user']));?></td>
<td><span class='blue_text'>Joined</span></td><td><?php echo account::getJoindate($_SESSION['cw_user']); ?></td>
</tr>
<tr>
    <td><span class='blue_text'>Email adress</span></td><td><?php echo account::getEmail($_SESSION['cw_user']);?></td>
    <td><span class='blue_text'>Vote Points</span></td><td><?php echo account::loadVP($_SESSION['cw_user']); ?></td>
</tr>
<tr>
    <td><span class='blue_text'>Account Status</span></td><td><?php echo account::checkBanStatus($_SESSION['cw_user']);?></td>
    <td><span class='blue_text'><?php echo $GLOBALS['donation']['coins_name']; ?></span></td><td><?php echo account::loadDP($_SESSION['cw_user']); ?></td>
</tr>
<br/>
</table>
 </div>
<div class='box_two'>
      <div class='box_two_title'>Services</div>
     <div id="account_func_placeholder">
			  <div class='account_func' onclick="acct_services('character')">Character Services</div>
			  <div class='account_func' onclick="acct_services('account')">Account Services</div>
			  <div class='account_func' onclick="acct_services('settings')">Settings</div>

			  <div class='hidden_content' id='character'>
                 <?php if($GLOBALS['service']['unstuck']['status'] == 'true') { ?>
                     <div class='service' onclick='redirect("?p=unstuck")'>
                     <div class='service_icon'><img src='styles/global/images/icons/character_migration.png'></div>
                     <h3>Unstuck</h3>
                     <div class='service_desc'>Unstuck your character.</div>
                     </div>
                 <?php } ?>

                 <?php if($GLOBALS['service']['revive']['status'] == 'true') { ?>
                     <div class='service' onclick='redirect("?p=revive")'>
                     <div class='service_icon'><img src='styles/global/images/icons/revive.png'></div>
                     <h3>Revive</h3>
                     <div class='service_desc'>Revive your character.</div>
                     </div>
                 <?php } ?>

                 <?php if($GLOBALS['service']['teleport']['status'] == 'true') { ?>
                     <div class='service' onclick='redirect("?p=teleport")'>
                     <div class='service_icon'><img src='styles/global/images/icons/transfer.png'></div>
                     <h3>Teleport</h3>
                     <div class='service_desc'>Teleport your character.</div>
                     </div>
                 <?php } ?>

                 <?php if($GLOBALS['service']['appearance']['status'] == 'true') { ?>
                     <div class='service' onclick='redirect("?p=service&s=appearance")'>
                     <div class='service_icon'><img src='styles/global/images/icons/appearance.png'></div>
                     <h3>Appearance change</h3>
                     <div class='service_desc'>Customize your character's appearance (optional name change included)</div>
                     </div>
                 <?php } ?>

                 <?php if($GLOBALS['service']['race']['status'] == 'true') { ?>
                     <div class='service' onclick='redirect("?p=service&s=race")'>
                     <div class='service_icon'><img src='styles/global/images/icons/race_change.png'></div>
                     <h3>Race change</h3>
                     <div class='service_desc'>Change your character's race (within your current faction)</div>
                     </div>
                 <?php } ?>

                 <?php if($GLOBALS['service']['name']['status'] == 'true') { ?>
                     <div class='service' onclick='redirect("?p=service&s=name")'>
                     <div class='service_icon'><img src='styles/global/images/icons/name_change.png'></div>
                     <h3>Name change</h3>
                     <div class='service_desc'>Change your character's name</div>
                     </div>
                 <?php } ?>

                 <?php if($GLOBALS['service']['faction']['status'] == 'true') { ?>
                     <div class='service' onclick='redirect("?p=service&s=faction")'>
                     <div class='service_icon'><img src='styles/global/images/icons/factions.png'></div>
                     <h3>Faction change</h3>
                     <div class='service_desc'>Change your character's faction (Horde to Alliance or Alliance to Horde)</div>
                     </div>
                 <?php } ?>
              </div>
              <div class='hidden_content' id='account'>

                     <div class='service' onclick='redirect("?p=vote")'>
                     <div class='service_icon'><img src='styles/global/images/icons/character_migration.png'></div>
                     <h3>Vote</h3>
                     <div class='service_desc'>Vote & recieve rewards.</div>
                     </div>

                     <div class='service' onclick='redirect("?p=donate")'>
                     <div class='service_icon'><img src='styles/global/images/icons/visa.png'></div>
                     <h3>Donate</h3>
                     <div class='service_desc'>Donate & recieve rewards.</div>
                     </div>

                     <div class='service' onclick='redirect("?p=voteshop")'>
                     <div class='service_icon'><img src='styles/global/images/icons/raf.png'></div>
                     <h3>Vote Shop</h3>
                     <div class='service_desc'>Claim your rewards!</div>
                     </div>

                     <div class='service' onclick='redirect("?p=donateshop")'>
                     <div class='service_icon'><img src='styles/global/images/icons/raf.png'></div>
                     <h3>Donation Shop</h3>
                     <div class='service_desc'>Claim your rewards!</div>
                     </div>

              </div>

              <div class='hidden_content' id='settings'>

                     <div class='service' onclick='redirect("?p=changepass")'>
                     <div class='service_icon'><img src='styles/global/images/icons/arena.png'></div>
                     <h3>Change Password</h3>
                     <div class='service_desc'>Change your account password.</div>
                     </div>

                     <div class='service' onclick='redirect("?p=settings")'>
                     <div class='service_icon'><img src='styles/global/images/icons/ptr.png'></div>
                     <h3>Change Email</h3>
                     <div class='service_desc'>Change the email adress associated with your account.</div>
                     </div>

              </div>
      </div>