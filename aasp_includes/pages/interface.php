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

	$server->SelectDB('webdb');
 	$page = new page;
	$page->validatePageAccess('Interface');
    if($page->validateSubPage())
		$page->outputSubPage();
	else
    {
?>
<div class="box_right_title">Template</div>
 Here you can choose which template that should be active on your website. This is also where you install new themes for your website.<br/><br/>
 <h3>Choose Template</h3>
        <select id="choose_template">
                <?php
                $result = $sql->query("SELECT * FROM template ORDER BY id ASC");
                while($row = mysqli_fetch_assoc($result)) {
                    if($row['applied']==1) 
                        echo "<option selected='selected' value='".$row['id']."'>[Active] ";
                    else 
                        echo "<option value='".$row['id']."'>";
                        
                    echo $row['name']."</option>";
                }
                ?>
        </select>
        <input type="submit" value="Save" onclick="setTemplate()"/><hr/><p/>
        
        <h3>Install a new template</h3>
        <a href="#" onclick="templateInstallGuide()">How to install new templates on your website</a><br/><br/><br/>
        Path to the template<br/>
        <input type="text" id="installtemplate_path"/><br/>
        Choose a name<br/>
        <input type="text" id="installtemplate_name"/><br/>
        <input type="submit" value="Install" onclick="installTemplate()"/>
        <hr/>
        <p/>
        
        <h3>Uninstall a template</h3>
        <select id="uninstall_template_id">
                <?php
                $result = $sql->query("SELECT * FROM template ORDER BY id ASC");
                while($row = mysqli_fetch_assoc($result)) {
                    if($row['applied']==1) 
                        echo "<option selected='selected' value='".$row['id']."'>[Active] ";
                    else 
                        echo "<option value='".$row['id']."'>";
                        
                    echo $row['name']."</option>";
                }
                ?>
        </select>
        <input type="submit" value="Uninstall" onclick="uninstallTemplate()"/> 
 <?php }