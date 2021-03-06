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
 
function sendSoap($command,$username,$password,$host,$soapport)
{
    $client = new SoapClient(NULL,
    array(
    "location" => "http://$host:$soapport/",
    "uri" => "urn:TC",
    "style" => SOAP_RPC,
    'login' => $username,
    'password' => $password
    ));
    try
    {
        $result = $this->$client->executeCommand(new SoapParam($command, "command"));
        echo "Command succeeded! Output:<br />\n";
        echo $result;
    }
    catch (Exception $e)
    {
        echo "Command failed! Reason:<br />\n";
        echo $e->getMessage();
    }
}