<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
?>
    <p id="steps">Introduction &raquo; <b>MySQL Info</b> &raquo; Configure &raquo; Database &raquo; Realm Info &raquo; Finished<p>
    <hr/>
    <fieldset style="float: left;">
       <legend>MySQL Settings</legend>
       <label>MySQL Host</label><BR />
        <input type="text" placeholder="127.0.0.1" id="step1_host"><BR />
        <label>MySQL User</label><BR />
        <input type="text" placeholder="root" id="step1_user"><BR />
        <label>MySQL Password</label><BR />
        <input type="text" placeholder="ascent" id="step1_pass"><BR />
        <label>CraftedWeb DB</label><BR />
        <input type="text" placeholder="craftedweb" id="step1_webdb"><BR />
        <label>Logon Database</label><BR />
        <input type="text" placeholder="auth" id="step1_logondb"><BR />
        <label>World Database</label><BR />
        <input type="text" placeholder="world" id="step1_worlddb"><BR />
    </fieldset>

    <fieldset style="float: right;">
        <legend>Site & Server Settings</legend>
        <label>Core Expansion</label><BR />
        <select id="step1_exp">
            <option value="0">Vanilla (No expansion)</option>
            <option value="1">The Burning Crusade</option>
            <option value="2" selected>Wrath of the Lich King (TrinityCore)</option>
            <option value="3">Cataclysm (SkyfireEMU)</option>
            <option value="4">Mists of Pandaria</option>
        </select><BR />
        <label>Realmlist</label><BR />
        <input type="text" placeholder="logon.yourserver.com" id="step1_realmlist"><BR />
        <label>Website Domain</label><BR />
        <input type="text" placeholder="http://yourserver.com" id="step1_domain"><BR />
        <label>Website Title</label><BR />
        <input type="text" placeholder="YourServer" id="step1_title"><BR />
        <label>PayPal Email</label><BR />
        <input type="text" placeholder="youremail@gmail.com" id="step1_paypal"><BR />
        <label>Default Email</label><BR />
        <input type="text" placeholder="noreply@yourserver.com" id="step1_email">
    </fieldset>
    <BR><BR />
    <input type="submit" value="Procceed to Step 2" onclick="step1()">