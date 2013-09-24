<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
 
     website::getNews();
	 
	 if ($GLOBALS['enableSlideShow']==false && $GLOBALS['news']['enable']==false)  
	 {
		 buildError("<b>Configuration file error.</b>Neither the slideshow or the news are displayed, the homepage will be empty.");
		 echo "Seems like the homepage was empty!";
	 }
