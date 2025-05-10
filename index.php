<?php
/*
    Plugin Name: Ahmeti Wp Timeline
    Text Domain: ahmeti-wp-timeline
    Plugin URI: http://ahmeti.net/
    Description: A nice plugin that allows you to make a timeline about anything. Herhangi bir konu hakkında bir timeline oluşarabilirsiniz.
    Author: Ahmet Imamoglu
    Version: 5.1
    Author URI: http://ahmeti.net/
    License: GPLv2 or later (license.txt)
*/

/*
 *  Copyright 2014 Ahmet İmamoğlu ( ahmet@ahmeti.net )
 *
 *  LICENSE:
 *
 *  Everyone is permitted to copy and distribute verbatim copies
 *  of this license document, but changing it is not allowed.
 *
 */

define('AHMETI_WP_TIMELINE_KONTROL', true);
define('AHMETI_WP_TIMELINE_ADMIN_URL', admin_url().'admin.php?page=ahmeti-wp-timeline/index.php');

global $wpdb;
define('AHMETI_WP_TIMELINE_DB_PREFIX', $wpdb->prefix);

require_once 'AhmetiWpTimelineFunction.php';

//  OPTION LIST
// $ahmetiWpTimelineOpt->DefaultSort = ASC
// $ahmetiWpTimelineOpt->StartState = open
// $ahmetiWpTimelineOpt->PageLimit = 20

if (is_admin()) {
    include __DIR__.DIRECTORY_SEPARATOR.'AhmetiWpTimelineAdmin.php';
    new AhmetiWpTimelineAdmin;
} else {
    include __DIR__.DIRECTORY_SEPARATOR.'AhmetiWpTimelineFront.php';
    new AhmetiWpTimelineFront;
}
