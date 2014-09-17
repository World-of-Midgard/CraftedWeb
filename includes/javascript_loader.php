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

    echo '<script type="text/javascript" src="javascript/jquery.min.js"></script>';
    echo '<script type="text/javascript" src="javascript/main.js"></script>';
    if($_GET['p'] == 'donateshop')
    {
        echo '<script type="text/javascript">
        $(document).ready(function() {
            loadMiniCart("donateCart");
        });
        </script>';
    }

    if($_GET['p'] == 'voteshop')
    {
        echo '<script type="text/javascript">
        $(document).ready(function() {
            loadMiniCart("voteCart");
        });
        </script>';
    }

    if($GLOBALS['enableSlideShow'] == true && $_GET['p'] == 'home')
    {
        echo '<script type="text/javascript" src="javascript/jquery.nivo.slider.js"></script>
        <script type="text/javascript">
        $(window).load(function() {
            $("#slider").nivoSlider({
                effect: "fade"
            });
        });
        </script>';
    }
    if($GLOBALS['core_expansion']>2)
        echo '<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>';
    else
        echo '<script type="text/javascript" src="http://cdn.openwow.com/api/tooltip.js"></script>';

    if($_GET['p'] == 'donateshop' || $_GET['p'] == 'voteshop')
    {
        echo '<script type="text/javascript">
        $(document).ready(function() {
            $(document).mousemove(function(e){
                mouseY = e.pageY;
            });
        });
        </script>';
    }

    if($GLOBALS['social']['enableFacebookModule'] == true)
    {
        echo '<script type="text/javascript">
            $(document).ready(function() {
                var box_width_one = $(".box_one").width();
                $("#fb").attr("width", box_width_one);
            });
            </script>';
        }

    if($GLOBALS['serverStatus']['enable'] == true)
    {
        echo '<script type="text/javascript">
                $(document).ready(function() {
                    $.post("includes/scripts/misc.php", { serverStatus: true },
                    function(data) {
                        $("#server_status").html(data);
                        $(".srv_status_po").hover(function() {
                            $(".srv_status_text").fadeIn("fast");
                        }, function() {
                            $(".srv_status_text").fadeOut("fast");
                        });
                    });
                });
                </script>';
    }
    plugins::load('javascript');

