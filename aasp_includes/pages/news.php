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
	 $page->validatePageAccess('News');
     if($page->validateSubPage())
		 $page->outputSubPage();
     else
     {
?>
<div class="box_right_title">News &raquo; Post news</div>                  
<div id="news_status"></div>
<input type="text" value="Title..." id="news_title"/> <br/>
<input type="text" value="Author..." id="news_author"/> <br/>
<input type="text" value="Image URL..." id="news_image"/> <br/>
<textarea cols="72" rows="7" id="wysiwyg">Content...</textarea>
<input type="submit" value="Post" onclick="postNews()"/>  <input type="submit" value="Preview" onclick="previewNews()" disabled="disabled"/>                                    
<?php }
                                    