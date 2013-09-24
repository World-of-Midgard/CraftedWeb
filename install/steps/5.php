<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
?>
<p id="steps"><b>Introduction</b> &raquo; MySQL Info &raquo; Configure &raquo; Database &raquo; <b>Realm Info</b> &raquo; Finished<p>
<hr/>
    <fieldset style="height: 445px;">
        <legend>Realm Settings</legend>
        <label>Realm Host</label><BR />
        <input type="text" placeholder="Default: 127.0.0.1" id="addrealm_host"><BR />
        <label>Realm Port</label><BR />
        <input type="text" placeholder="Default: 8085" id="addrealm_port"><BR />
        <label>Realm ID</label><BR /></label>
        <input type="text" placeholder="Default: 1" id="addrealm_id"><BR />
        <label>Realm Name</label><BR />
        <input type="text" placeholder="Default: Sample Realm" id="addrealm_name"><BR />
        <label>Realm Description (optional)</label><BR />
        <input type="text" placeholder="Default: Blizzlike 1x" id="addrealm_desc"><BR />
        <label>MySQL Host</label><BR />
        <input type="text" placeholder="Default: 127.0.0.1" id="addrealm_m_host"><BR />
        <label>MySQL Username</label><BR />
        <input type="text" placeholder="Default: root" id="addrealm_m_user"><BR />
        <label>MySQL Password</label><BR />
        <input type="text" placeholder="Default: ascent" id="addrealm_m_pass"><BR />
        <label>Character Database</label><BR />
        <input type="text" placeholder="Default: characters" id="addrealm_chardb"><BR />
    </fieldset>
    <BR />
    <fieldset>
        <legend>Remote Settings</legend>
        <label>Authorized Account username</label><BR />
        <input type="text" placeholder="Default: admin" id="addrealm_a_user"><BR />
        <label>Authorized Account password</label><BR />
        <input type="text" placeholder="Default: adminpass" id="addrealm_a_pass"><BR />

        <label>Remote Console</label><BR />
        <select id="addrealm_sendtype">
        <option value="ra">RA</option>
        <option value="soap">SOAP</option>
        </select><BR />

        <label>RA Port (Ignore if you've chosen SOAP)</label><BR />
        <input type="text" placeholder="Default: 3443" id="addrealm_raport"><BR />
        <label>SOAP Port (Ignore if you've chosen RA)</label><BR />
        <input type="text" placeholder="Default: 7878" id="addrealm_soapport"><BR />
    </fieldset>
    <BR />
    <input type="submit" value="Finished" onclick="step5()">