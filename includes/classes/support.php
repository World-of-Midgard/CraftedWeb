<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
 
#################
# Unused class. #
#################
class support {
	
	public static function loadEmailForm() 
	{
		?><br/>
		<form action="?p=support&do=email" method="post">
        Issue: <bR/><select name="issue">
               <option>Technical Problems</option>
               <option>Violation</option>
               <option>Other...</option>       
        </select><br/>
        Describe your problem: <br/>
        <textarea name="description" cols="50" rows="7"></textarea>
        </form>
		<?php
	}
	
}

?>