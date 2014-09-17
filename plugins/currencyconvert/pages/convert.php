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

    $divide = 40;
    account::isNotLoggedIn();
    ?>
    <h2>Currency converter</h2>
    <?php echo $GLOBALS['website_title']; ?> now lets you convert your Vote Points into <?php echo $GLOBALS['donation']['coins_name']; ?>!<br/>
    Every <?php echo $divide; ?> Vote Point will give you 1 donation coin, simple! <br/>
    You currently have <b><?php echo account::loadVP($_SESSION['cw_user']); ?></b> Vote Points which would give you <b><?php
    echo floor(account::loadVP($_SESSION['cw_user'])/$divide); ?></b> <?php echo $GLOBALS['donation']['coins_name']; ?>.
    <hr/>
    <form action="?p=convert" method="post">
    <table>
        <tr>
            <td>
            Vote Points:
            </td>
            <td>
            <select name="conv_vp" onchange="calcConvert(<?php echo $divide; ?>)" id="conv_vp">
                <option value="40">40</option>
                <option value="80">80</option>
                <option value="120">120</option>
                <option value="160">160</option>
                <option value="200">200</option>
            </select>
            </td>
        </tr>
        <tr>
            <td>
            <?php echo $GLOBALS['donation']['coins_name']; ?>:
            </td>
            <td>
            <input type="text" id="conv_dp" style="width: 70px;" value="1" readonly/>
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
            <hr/>
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
            <input type="submit" value="Convert" name="convert" />
            </td>
        </tr>
    </table>
    </form>
    <?php
    if(isset($_POST['convert']))
    {
        $vp = round((int)$_POST['conv_vp']);
        if(account::hasVP($_SESSION['cw_user'],$vp) == false)
        echo "<span class='alert'>You do not have enough Vote Points!</span>";
        else
        {
            $dp = floor($vp / $divide);
            account::deductVP(account::getAccountID($_SESSION['cw_user']),$vp);
            account::addDP(account::getAccountID($_SESSION['cw_user']),$dp);
            account::logThis("Converted ".$vp." Vote Points into ".$dp." ".$GLOBALS['donation']['coins_name'],"currencyconvert",NULL);
            header("Location: ?p=convert");
            exit;
        }
    }