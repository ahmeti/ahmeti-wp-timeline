<?php

class AhmetiWpTimelineAdmin
{
    public function __construct()
    {
        if (isset($_GET['activate']) && $_GET['activate'] === 'true') {
            add_action('init', [$this, 'install']);
        }
    }

    public function install()
    {
        global $wpdb;

        $table = $wpdb->prefix.'ahmeti_wp_timeline';

        $tables = $wpdb->get_results('SHOW TABLES FROM '.DB_NAME, ARRAY_N);
        $tables = array_map(function ($table) {
            return $table[0];
        }, $tables);

        if (! in_array($table, $tables, true)) {
            $sql = "CREATE TABLE IF NOT EXISTS `$table` (
                `event_id` bigint(20) NOT NULL AUTO_INCREMENT,
                `group_id` smallint(6) NOT NULL DEFAULT '0',
                `timeline_bc` bigint(20) DEFAULT '0',
                `timeline_date` datetime DEFAULT '0000-00-00 00:00:00',
                `title` varchar(255) DEFAULT NULL,
                `event_content` mediumtext,
                `type` enum('event','group_name') NOT NULL DEFAULT 'event',
                PRIMARY KEY (`event_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=$wpdb->charset AUTO_INCREMENT=1;";
            $wpdb->query($sql);
        }

        // Create options
        if (get_option('ahmeti_wp_timeline_options') === false) {
            $options = [
                'DefaultSort' => 'ASC',
                'StartState' => 'close',
                'PageLimit' => '20',
                'DateFormatYear' => 'Y',
                'DateFormatYearMonth' => 'm.Y',
                'DateFormatYearMonthDay' => 'd.m.Y',
                'DateFormatHourMinutesSecond' => 'H:i:s',
            ];
            add_option('ahmeti_wp_timeline_options', json_encode($options));
        }
    }
}
