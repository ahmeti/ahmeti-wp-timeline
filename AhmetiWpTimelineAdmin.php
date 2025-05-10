<?php

class AhmetiWpTimelineAdmin
{
    private $url;

    public function __construct()
    {
        $this->url = admin_url().'admin.php?page=ahmeti-wp-timeline/index.php';

        if (isset($_GET['activate']) && $_GET['activate'] === 'true') {
            add_action('init', [$this, 'install']);
        }

        add_action('admin_menu', [$this, 'menu']);

        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
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
                `timeline_date` datetime DEFAULT NULL,
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

    public function menu()
    {
        add_menu_page('Ahmeti Wp Timeline', 'Timeline', 'edit_pages', 'ahmeti-wp-timeline/index.php', 'Ahmeti_Wp_Timeline_Index', plugins_url('ahmeti-wp-timeline/images/ahmeti-wp-timeline-icon.png'), '6.9');
    }

    public function enqueueScripts()
    {
        load_plugin_textdomain('ahmeti-wp-timeline', false, dirname(plugin_basename(__FILE__)).'/languages/');

        wp_enqueue_script('jquery');

        /* Datepicker */
        wp_enqueue_script('jquery-ui-datepicker');
        wp_register_style('AhmetiWpTimelineAdminJqueryUi', plugins_url().'/ahmeti-wp-timeline/Admin/Css/smoothness/jquery-ui-1.10.3.custom.min.css', [], '', 'screen');
        wp_enqueue_style('AhmetiWpTimelineAdminJqueryUi');

        wp_register_script('AhmetiWpTimelineAdminJs', plugins_url().'/ahmeti-wp-timeline/Admin/Js/AhmetiWpTimelineAdmin.js', ['jquery']);
        wp_enqueue_script('AhmetiWpTimelineAdminJs');

        /* Start to Add Js Variables */
        $JsData = ['pluginUrl' => plugins_url().'/ahmeti-wp-timeline/', 'pluginAdminUrl' => $this->url];
        wp_localize_script('AhmetiWpTimelineAdminJs', 'AhmetiWpTimelineJsData', $JsData);

        wp_register_style('AhmetiWpTimelineAdminCss', plugins_url().'/ahmeti-wp-timeline/Admin/Css/AhmetiWpTimelineAdmin.css', [], '', 'screen');
        wp_enqueue_style('AhmetiWpTimelineAdminCss');

        // load_plugin_textdomain('ahmeti-wp-timeline', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
    }
}
