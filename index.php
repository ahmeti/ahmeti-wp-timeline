<?php

/*
    Plugin Name: Ahmeti Wp Timeline
    Text Domain: ahmeti-wp-timeline
    Plugin URI: https://ahmeti.com.tr/ahmeti-wp-timeline-zaman-cizelgesi-eklentisi
    Description: A nice plugin that allows you to make a timeline about anything. Herhangi bir konu hakkında bir timeline oluşarabilirsiniz.
    Author: Ahmet Imamoglu
    Version: 6.0
    Author URI: https://ahmeti.com.tr/
    License: GPLv2 or later (license.txt)
*/

if (is_admin()) {
    include __DIR__.DIRECTORY_SEPARATOR.'AhmetiWpTimelineAdmin.php';
    new AhmetiWpTimelineAdmin;
} else {
    include __DIR__.DIRECTORY_SEPARATOR.'AhmetiWpTimelineFront.php';
    new AhmetiWpTimelineFront;
}
