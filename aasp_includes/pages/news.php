<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
?>
<?php 
	 $server->selectDB('webdb'); 
 	 $page = new page;
	 
	 $page->validatePageAccess('News');
	 
     if($page->validateSubPage() == TRUE) {
		 $page->outputSubPage();
	 } else {
?>
<div class="box_right_title">News &raquo; Post news</div>                  
<div id="news_status"></div>
<input type="text" value="Title..." id="news_title"/> <br/>
<input type="text" value="Author..." id="news_author"/> <br/>
<input type="text" value="Image URL..." id="news_image"/> <br/>
<textarea cols="72" rows="7" id="wysiwyg">Content...</textarea>
<input type="submit" value="Post" onclick="postNews()"/>  <input type="submit" value="Preview" onclick="previewNews()" disabled="disabled"/>                                    
<?php } ?>
                                    