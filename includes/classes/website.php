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

$sql = new mysqli($GLOBALS['connection']['host'],$GLOBALS['connection']['user'],$GLOBALS['connection']['password']);
class website
{
    public static function getNews()
    {
        global $sql;
        if ($GLOBALS['news']['enable'] == true)
        {
            echo '<div class="box_two_title">Latest News</div>';
            if (cache::exists('news') == true)
                cache::loadCache('news');
            else
            {
                connect::selectDB('webdb');
                $result = $sql->query("SELECT * FROM news ORDER BY id DESC LIMIT ".$GLOBALS['news']['maxShown']);
                if (!$result)
                    echo 'No news was found';
                else
                {
                    $output = null;
                    while($row = mysqli_fetch_assoc($result))
                    {
                        if(file_exists($row['image']))
                        {
                            echo $newsPT1 =  '
                            <table class="news" width="100%">
                            <tr>
                            <td><h3 class="yellow_text">'.$row['title'].'</h3></td>
                            </tr>
                            </table>
                            <table class="news_content" cellpadding="4">
                            <tr>
                            <td><img src="'.$row['image'].'" alt=""/></td>
                            <td>';
                        }
                        else
                        {
                            echo $newsPT1 =  '
                            <table class="news" width="100%">
                            <tr>
                            <td><h3 class="yellow_text">'.$row['title'].'</h3></td>
                            </tr>
                            </table>
                            <table class="news_content" cellpadding="4">
                            <tr>
                            <td>';
                        }
                        $output .= $newsPT1;
                        unset($newsPT1);
                        $text = html_entity_decode(preg_replace("
                        #((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie",
                        "'<a href=\"$1\" target=\"_blank\">http://$3</a>$4'",
                        $row['body']
                        ));

                        if ($GLOBALS['news']['limitHomeCharacters'] == true)
                        {
                            echo website::limit_characters($text,200);
                            $output.= website::limit_characters($row['body'],200);
                        }
                        else
                        {
                            echo nl2br($text);
                            $output .= nl2br($row['body']);
                        }
                        $commentsNum = $sql->query("SELECT COUNT(id) AS count FROM news_comments WHERE newsid='".$row['id']."'");
                        $result_comments = mysqli_fetch_assoc($commentsNum);
                        if($GLOBALS['news']['enableComments'] == true)
                            $comments = '| <a href="?p=news&amp;newsid='.$row['id'].'">Comments ('.$result_comments['count'].')</a>';
                        else
                            $comments = '';
                        echo $newsPT2 = '
                        <br/><br/><br/>
                        <i class="gray_text"> Written by '.$row['author'].' | '.$row['date'].' '.$comments.'</i>
                        </td>
                        </tr>
                        </table>';
                        $output .= $newsPT2;
                        unset($newsPT2);
                    }
                    echo '<hr/>
                    <a href="?p=news">View older news...</a>';
                    cache::buildCache('news',$output);
                }
            }
        }
    }

    public static function getSlideShowImages()
    {
        global $sql, $output;
        if (cache::exists('slideshow') == true)
            cache::loadCache('slideshow');
        else
        {
            connect::selectDB('webdb');
            $result = $sql->query("SELECT path,link FROM slider_images ORDER BY position ASC");
            while($row = mysqli_fetch_assoc($result))
            {
                echo $outPutPT = '<a href="'.$row['link'].'">
                <img border="none" src="'.$row['path'].'" alt="" />
                </a>';
                $output .= $outPutPT;
            }
            cache::buildCache('slideshow',$output);
        }
    }

	public static function limit_characters($str,$n)
	{
		$str = preg_replace("/<img[^>]+\>/i", "(image)", $str);
		if (strlen($str) <= $n)
			return $str;
		else
			return substr ($str, 0, $n).'...';
    }

    public static function loadVotingLinks()
    {
        global $sql;
        connect::selectDB('webdb');
        $result = $sql->query("SELECT * FROM votingsites ORDER BY id DESC");
        if (mysqli_num_rows($result) == 0)
            echo "No vote links added";
            ////buildError("Couldnt fetch any voting links from the database. ".mysqli_error($result));
        else
        {
            while($row = mysqli_fetch_assoc($result))
            {
                ?>
                <div class='votelink'>
                <table width="100%">
                <tr>
                <td width="20%"><img src="<?php echo $row['image']; ?>" /></td>
                <td width="50%"><strong><?php echo $row['title']; ?></strong> (<?php echo $row['points']; ?> Vote Points)<td>
                <td width="40%">
                <?php if(website::checkIfVoted($row['id']) == false)
                {?>
                    <input type='submit' value='Vote'  onclick="vote('<?php echo $row['id']; ?>',this)">
                <?php
                }
                else
                {
                    $getNext = $sql->query("SELECT next_vote FROM ".$GLOBALS['connection']['webdb'].".votelog
                    WHERE userid='".account::getAccountID($_SESSION['cw_user'])."'
                    AND siteid='".$row['id']."' ORDER BY id DESC LIMIT 1");
                    $row = mysqli_fetch_assoc($getNext);
                    $time = $row['next_vote'] - time();
                    echo 'Time until reset: '.convTime($time);
                }
                ?>
                </td>
                </tr>
                </table>
                </div>
                <?php
            }
        }
    }

	public static function checkIfVoted($siteid)
	{
        global $sql;
		$siteid = (int)$siteid;
		$acct_id = account::getAccountID($_SESSION['cw_user']);
		connect::selectDB('webdb');
		$result = $sql->query("SELECT COUNT(id) FROM votelog
		WHERE userid='".$acct_id."' AND siteid='".$siteid."' AND next_vote > ".time());
		if (!$result)
			return false;
		 else
			return true;
	}

	public static function sendEmail($to,$from,$subject,$body)
	{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$from . "\r\n";
		if (!mail($to,$subject,$body,$headers))
            return false;
        return true;
	}

	public static function convertCurrency($currency)
	{
        global $convert;
		if($currency == 'dp')
            $convert = $GLOBALS['donation']['coins_name'];
		elseif($currency == 'vp')
			$convert = "Vote Points";
        return $convert;
	}
}

