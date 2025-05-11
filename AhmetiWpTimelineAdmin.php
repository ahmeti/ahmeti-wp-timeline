<?php

class AhmetiWpTimelineAdmin
{
    public function __construct()
    {
        if (isset($_GET['activate']) && $_GET['activate'] === 'true') {
            add_action('init', [$this, 'install']);
        }

        add_action('admin_menu', [$this, 'menu']);

        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);

        add_action('admin_init', [$this, 'editorButton']);
    }

    public function install()
    {
        global $wpdb;

        $table = self::table();

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
        add_menu_page('Ahmeti Wp Timeline', 'Timeline', 'edit_pages', 'ahmeti-wp-timeline/index.php', [$this, 'routes'], plugins_url('ahmeti-wp-timeline/images/icon.png'), '6.9');
    }

    public function enqueueScripts()
    {
        load_plugin_textdomain('ahmeti-wp-timeline', false, dirname(plugin_basename(__FILE__)).'/languages/');

        wp_enqueue_script('jquery');

        // wp_register_script('AhmetiWpTimelineAdminJs', plugins_url().'/ahmeti-wp-timeline/Admin/Js/AhmetiWpTimelineAdmin.js', ['jquery']);
        // wp_enqueue_script('AhmetiWpTimelineAdminJs');

        /* Start to Add Js Variables */
        $JsData = ['pluginUrl' => plugins_url().'/ahmeti-wp-timeline/', 'pluginAdminUrl' => self::url()];
        wp_localize_script('AhmetiWpTimelineAdminJs', 'AhmetiWpTimelineJsData', $JsData);

        wp_register_style('AhmetiWpTimelineAdminCss', plugins_url().'/ahmeti-wp-timeline/Admin/Css/AhmetiWpTimelineAdmin.css');
        wp_enqueue_style('AhmetiWpTimelineAdminCss');

        // load_plugin_textdomain('ahmeti-wp-timeline', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
    }

    public function editorButton()
    {
        if (! current_user_can('edit_posts')) {
            return;
        }

        if (! current_user_can('edit_pages')) {
            return;
        }

        global $wp_version;

        if (version_compare($wp_version, '3.9', '<')) {
            // Old TinyMce 3.0
            add_filter('mce_buttons', [$this, 'filterEditorButtons']);
            add_filter('mce_external_plugins', [$this, 'filterEditorPlugins']);
        } else {
            // New TinyMce 4.0
            add_filter('mce_external_plugins', [$this, 'addEditorPlugin']);
            add_filter('mce_buttons', [$this, 'registerEditorButton']);
        }
    }

    public function filterEditorButtons($buttons)
    {
        array_push($buttons, '|', 'mygallery_button');

        return $buttons;
    }

    public function filterEditorPlugins($plugins)
    {
        $plugins['mygallery'] = plugins_url().'/ahmeti-wp-timeline/Admin/Js/EditorButtonV3.js';

        return $plugins;
    }

    public function addEditorPlugin($plugin_array)
    {
        $plugin_array['ahmeti_wp_timeline_button'] = plugins_url().'/ahmeti-wp-timeline/Admin/Js/EditorButtonV4.js';

        return $plugin_array;
    }

    public function registerEditorButton($buttons)
    {
        $buttons[] = 'ahmeti_wp_timeline_button';

        return $buttons;
    }

    public function header()
    {
        ?>
        <div id="ahmeti-wp-timeline">
            <h1 class="slogan">Ahmeti WP Timeline <sup>6.0</sup></h1>

            <a style="margin-right:5px;" class="button" href="<?php echo self::url(); ?>&islem=TimelineIndex"><?php echo _e('Timeline List', 'ahmeti-wp-timeline'); ?></a>
            <a style="margin-right:30px;" class="button" href="<?php echo self::url(); ?>&islem=TimelineCreate"><?php echo _e('New Timeline', 'ahmeti-wp-timeline'); ?></a>

            <a style="margin-right:5px;" class="button" href="<?php echo self::url(); ?>&islem=EventIndex"><?php echo _e('Event List', 'ahmeti-wp-timeline'); ?></a>
            <a style="margin-right:30px;" class="button" href="<?php echo self::url(); ?>&islem=EventCreate"><?php echo _e('Add New Event', 'ahmeti-wp-timeline'); ?></a>

            <a style="margin-right:5px;" class="button" href="<?php echo self::url(); ?>&islem=SettingEdit"><?php echo _e('Settings', 'ahmeti-wp-timeline'); ?></a>
        <?php
    }

    public function footer()
    {
        ?>
        </div>
        <?php
    }

    public function routes()
    {
        $this->header();

        $page = isset($_GET['islem']) ? $_GET['islem'] : '';

        $pages = [
            'TimelineIndex' => ['Admin/AhmetiWptTimeline', 'index'],
            'TimelineCreate' => ['Admin/AhmetiWptTimeline', 'create'],
            'TimelineStore' => ['Admin/AhmetiWptTimeline', 'store'],
            'TimelineEdit' => ['Admin/AhmetiWptTimeline', 'edit'],
            'TimelineUpdate' => ['Admin/AhmetiWptTimeline', 'update'],
            'TimelineDelete' => ['Admin/AhmetiWptTimeline', 'delete'],

            'EventIndex' => ['Admin/AhmetiWptEvent', 'index'],
            'EventCreate' => ['Admin/AhmetiWptEvent', 'create'],
            'EventStore' => ['Admin/AhmetiWptEvent', 'store'],
            'EventEdit' => ['Admin/AhmetiWptEvent', 'edit'],
            'EventUpdate' => ['Admin/AhmetiWptEvent', 'update'],
            'EventDelete' => ['Admin/AhmetiWptEvent', 'delete'],

            'SettingEdit' => ['Admin/AhmetiWptSetting', 'edit'],
            'SettingUpdate' => ['Admin/AhmetiWptSetting', 'update'],
        ];

        if (! array_key_exists($page, $pages)) {
            $page = 'TimelineIndex';
        }

        $route = $pages[$page];
        include $route[0].'.php';
        call_user_func([basename($route[0]), $route[1]], []);

        $this->footer();
    }

    public static function options($key = null)
    {
        $options = json_decode(get_option('ahmeti_wp_timeline_options'), true);

        $options = (object) array_merge([
            'DefaultSort' => 'ASC',
            'StartState' => 'close',
            'PageLimit' => '20',
            'DateFormatYear' => 'Y',
            'DateFormatYearMonth' => 'm.Y',
            'DateFormatYearMonthDay' => 'd.m.Y',
            'DateFormatHourMinutesSecond' => 'H:i:s',
        ], $options);

        if ($key && isset($options->$key)) {
            return $options->$key;
        }

        return $options;
    }

    public static function url()
    {
        return admin_url().'admin.php?page=ahmeti-wp-timeline/index.php';
    }

    public static function table()
    {
        global $wpdb;

        return $wpdb->prefix.'ahmeti_wp_timeline';
    }

    public static function pagination($siteUrl, $total, $page, $limit, $page_url)
    {
        if ($total > $limit) {

            echo '<div id="pagination"><span class="page-title">'.__('Pages', 'ahmeti-wp-timeline').'</span>';

            $x = 5;
            $lastP = ceil($total / $limit);

            if ($page == 1) {
                echo '<span class="page-active">1</span>';
            } else {
                echo '<a class="page-a" href="'.$siteUrl.$page_url.'">1</a>';
            }

            if ($page - $x > 2) {
                echo '<span class="page-b">...</span>';
                $ii = $page - $x;
            } else {
                $ii = 2;
            }

            for ($i = $ii; $i <= $page + $x; $i++) {
                if ($i == $page) {
                    echo '<span class="page-active">'.$i.'</span>';
                } else {
                    echo '<a class="page-a" href="'.$siteUrl.$page_url.'&is_page='.$i.'">'.$i.'</a>';
                }
                if ($i == $lastP) {
                    break;
                }
            }

            if ($page + $x < $lastP - 1) {
                echo '<span class="page-b">...</span>';
                echo '<a class="page-a" href="'.$siteUrl.$page_url.'&is_page='.$lastP.'">'.$lastP.'</a>';
            } elseif ($page + $x == $lastP - 1) {
                echo '<a class="page-a" href="'.$siteUrl.$page_url.'&is_page='.$lastP.'">'.$lastP.'</a>';
            }
            echo '</div>';
        }
    }

    public static function getTimelines()
    {
        global $wpdb;

        $prepare = $wpdb->prepare('SELECT `group_id`, `title` FROM '.AhmetiWpTimelineAdmin::table().' WHERE type = %s ORDER BY title ASC', 'group_name');

        return $wpdb->get_results($prepare);
    }
}
