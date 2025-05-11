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
        add_menu_page('Ahmeti Wp Timeline', 'Timeline', 'edit_pages', 'ahmeti-wp-timeline/index.php', [$this, 'routes'], plugins_url('ahmeti-wp-timeline/images/ahmeti-wp-timeline-icon.png'), '6.9');
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
        $JsData = ['pluginUrl' => plugins_url().'/ahmeti-wp-timeline/', 'pluginAdminUrl' => self::url()];
        wp_localize_script('AhmetiWpTimelineAdminJs', 'AhmetiWpTimelineJsData', $JsData);

        wp_register_style('AhmetiWpTimelineAdminCss', plugins_url().'/ahmeti-wp-timeline/Admin/Css/AhmetiWpTimelineAdmin.css', [], '', 'screen');
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
            <h1 style="font:oblique 30px/30px Georgia,serif; color:grey;background-image: url('<?php echo plugins_url(); ?>/ahmeti-wp-timeline/images/ahmeti-wp-timeline-logo.png');background-repeat: no-repeat;padding: 0 10px 10px 47px;background-position: 0 0;">Ahmeti WP Timeline <sup style="font-size: 14px">5.1</sup></h1>

            <a style="margin-right:5px;" class="button" href="<?php echo self::url(); ?>"><?php echo _e('Timeline List', 'ahmeti-wp-timeline'); ?></a>
            <a style="margin-right:30px;" class="button" href="<?php echo self::url(); ?>&islem=TimelineCreate"><?php echo _e('New Timeline', 'ahmeti-wp-timeline'); ?></a>

            <a style="margin-right:5px;" class="button" href="<?php echo self::url(); ?>&islem=EventList"><?php echo _e('Event List', 'ahmeti-wp-timeline'); ?></a>
            <a style="margin-right:30px;" class="button" href="<?php echo self::url(); ?>&islem=NewEventForm"><?php echo _e('Add New Event', 'ahmeti-wp-timeline'); ?></a>

            <a style="margin-right:5px;" class="button" href="<?php echo self::url(); ?>&islem=EditSettingsForm"><?php echo _e('Settings', 'ahmeti-wp-timeline'); ?></a>
        <?php
    }

    public function footer()
    {
        ?>
            <br/><br/>
            <div class="ahmetiWpTimelineFooter">
                <div class="footeradmindesc">
                    <span><?php echo _e('Developer', 'ahmeti-wp-timeline'); ?> : </span><a target="_blank" href="https://ahmeti.com.tr/"> Ahmet İmamoğlu</a> |
                    <span><?php echo _e('Plug-in Wp Page', 'ahmeti-wp-timeline'); ?> : </span><a target="_blank" href="https://wordpress.org/plugins/ahmeti-wp-timeline/">https://wordpress.org/plugins/ahmeti-wp-timeline/</a>
                </div>
            </div>
        </div>
        <?php
    }

    public function routes()
    {
        $this->header();

        $page = isset($_GET['islem']) ? $_GET['islem'] : '';

        $pages = [
            'GroupList' => 'Admin/Group/GroupList.php',
            'NewGroupForm' => 'Admin/Group/NewGroupForm.php',
            'NewGroupPost' => 'Admin/Group/NewGroupPost.php',
            'EditGroupForm' => 'Admin/Group/EditGroupForm.php',
            'EditGroupPost' => 'Admin/Group/EditGroupPost.php',
            'DeleteGroupPost' => 'Admin/Group/DeleteGroupPost.php',

            'EventList' => 'Admin/Event/EventList.php',
            'NewEventForm' => 'Admin/Event/NewEventForm.php',
            'NewEventPost' => 'Admin/Event/NewEventPost.php',
            'EditEventForm' => 'Admin/Event/EditEventForm.php',
            'EditEventPost' => 'Admin/Event/EditEventPost.php',
            'DeleteEventPost' => 'Admin/Event/DeleteEventPost.php',

            'EditSettingsForm' => 'Admin/Settings/EditSettingsForm.php',
            'EditSettingsPost' => 'Admin/Settings/EditSettingsPost.php',
        ];

        if (array_key_exists($page, $pages)) {
            require_once $pages[$page];
        } else {
            require_once $pages['GroupList'];
        }

        $this->footer();
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

    public static function pagination($site_url, $top_sayfa, $page, $limit, $page_url)
    {
        if ($top_sayfa > $limit) {

            echo '<div id="sayfala"><span class="say_sabit">'.__('Pages', 'ahmeti-wp-timeline').'</span>';

            $x = 5; // Aktif sayfadan önceki/sonraki sayfa gösterim sayisi
            $lastP = ceil($top_sayfa / $limit);

            // sayfa 1'i yazdir
            if ($page == 1) {
                echo '<span class="say_aktif">1</span>';
            } else {
                echo '<a class="say_a" href="'.$site_url.''.$page_url.'">1</a>';
            }

            // "..." veya direkt 2
            if ($page - $x > 2) {
                echo '<span class="say_b">...</span>';
                $i = $page - $x;
            } else {
                $i = 2;
            }
            // +/- $x sayfalari yazdir
            for ($i; $i <= $page + $x; $i++) {
                if ($i == $page) {
                    echo '<span class="say_aktif">'.$i.'</span>';
                } else {
                    echo '<a class="say_a" href="'.$site_url.''.$page_url.'&is_page='.$i.'">'.$i.'</a>';
                }
                if ($i == $lastP) {
                    break;
                }
            }

            // "..." veya son sayfa
            if ($page + $x < $lastP - 1) {
                echo '<span class="say_b">...</span>';
                echo '<a class="say_a" href="'.$site_url.''.$page_url.'&is_page='.$lastP.'">'.$lastP.'</a>';
            } elseif ($page + $x == $lastP - 1) {
                echo '<a class="say_a" href="'.$site_url.''.$page_url.'&is_page='.$lastP.'">'.$lastP.'</a>';
            }
            echo '</div>'; // #sayfala
        }
    }
}
