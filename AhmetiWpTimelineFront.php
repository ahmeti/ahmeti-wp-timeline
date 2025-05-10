<?php

class AhmetiWpTimelineFront
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_shortcode('ahmetiwptimeline', [$this, 'shortCode']);
    }

    public function enqueueScripts()
    {
        /* Get Short Code Parameters */
        $dat = [];
        preg_match("/\[ahmetiwptimeline (.+?)]/", get_post_field('post_content', get_the_ID()), $dat);

        if (empty($dat)) {
            return false;
        }

        $dat = array_pop($dat);
        $dat = explode(' ', $dat);
        $params = [];
        foreach ($dat as $d) {
            [$opt, $val] = explode('=', $d);
            $params[$opt] = trim($val, '"');
        }
        /* End Short Code Parameters */

        /* Wp User Head */
        load_plugin_textdomain('ahmeti-wp-timeline', false, dirname(plugin_basename(__FILE__)).'/languages/');

        /* JS */
        wp_enqueue_script('jquery');

        wp_register_script('timelinerColorboxJs', plugins_url().'/ahmeti-wp-timeline/TimelinerJquery/inc/colorbox.js', ['timelinerTimelinerJs']);
        wp_enqueue_script('timelinerColorboxJs');

        wp_register_script('timelinerTimelinerJs', plugins_url().'/ahmeti-wp-timeline/TimelinerJquery/js/timeliner.min.js', ['jquery']);
        $translation_array = ['ExpandAll' => __('+ Expand All', 'ahmeti-wp-timeline'), 'CollapseAll' => __('- Collapse All', 'ahmeti-wp-timeline'), 'State' => $params['state'] ?? ''];
        wp_localize_script('timelinerTimelinerJs', 'timelinerTimelinerJsObject', $translation_array);
        wp_enqueue_script('timelinerTimelinerJs');

        /* CSS */
        wp_register_style('timelinerColorboxCss', plugins_url().'/ahmeti-wp-timeline/TimelinerJquery/inc/colorbox.css', [], '', 'screen');
        wp_enqueue_style('timelinerColorboxCss');

        wp_register_style('timelinerScreenCss', plugins_url().'/ahmeti-wp-timeline/TimelinerJquery/css/screen.css', [], '', 'screen');
        wp_enqueue_style('timelinerScreenCss');

    }

    public function shortCode($attrs)
    {
        global $wpdb;

        /*
         * Aynı yıl içinde varsa bir kaç tane olay varsa yılın içine ekle...
         *
         */

        // echo _e('Hepsini_Ac','ahmeti-wp-timeline');

        /* OPTIONS */
        $ahmetiWpTimelineOpt = json_decode(get_option('ahmeti_wp_timeline_options'));

        /* SHORTCODE */
        $group_id = $attrs['groupid'];
        $sort = @$attrs['sort'];
        $state = @$attrs['state'];

        if (empty($sort)) {
            $sort = $ahmetiWpTimelineOpt->DefaultSort;
            if (empty($sort)) {
                $sort = 'DESC';
            }
        }

        if (empty($state)) {
            $state = $ahmetiWpTimelineOpt->StartState;
            if (empty($state)) {
                $state = 'close';
            }
        }

        $AhmetiWpTimelineEndSqlYear = '';
        $AhmetiWpTimelineOut = '<div id="timelineContainer" unselectable="on">';

        if ($state == 'open') {
            $AhmetiWpTimelineOut .= '<a id="AhmetiExpandButton" class="expandAll expanded" style="float:right">'.__('- Collapse All', 'ahmeti-wp-timeline').'</a><br/>';
        } else {
            $AhmetiWpTimelineOut .= '<a id="AhmetiExpandButton" class="expandAll" style="float:right">'.__('+ Expand All', 'ahmeti-wp-timeline').'</a><br/>';
        }

        $AhmetiSay = true;

        $sql_group = $wpdb->get_results('SELECT * FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE group_id="'.$group_id.'" AND type="event" ORDER BY timeline_bc '.$sort.', timeline_date '.$sort, ARRAY_A);

        foreach ($sql_group as $row_group) {

            if ($row_group['timeline_bc'] < 0) {
                $AhmetiYear = $row_group['timeline_bc'];
            } elseif (AhmetiWpTimelineGetYear($row_group['timeline_date']) > 0) {
                // Sadece Yılı Al...
                $AhmetiYear = AhmetiWpTimelineGetYear($row_group['timeline_date']);
            }

            if ($AhmetiYear == $AhmetiWpTimelineEndSqlYear) {

                // Yılın İçine Ekle
                $AhmetiWpTimelineOut .= '
                <dl class="timelineMinor">
                    <dt id="event'.$row_group['event_id'].'"><a>'.$row_group['title'].'</a></dt>
                    <dd class="timelineEvent" id="event'.$row_group['event_id'].'EX" style="display: none; ">
                        <div class="event_content">';

                // Tarih Detay
                if ($AhmetiYear > 0) {
                    $AhmetiWpTimelineOut .= '<span class="AhmetiDate">'.AhmetiWpTimelineDateTitle($row_group['timeline_date'], $ahmetiWpTimelineOpt).'</span>';
                }

                $AhmetiWpTimelineOut .= $row_group['event_content'].'</div><!-- event_content -->
                    </dd><!-- /.timelineEvent -->
                </dl><!-- /.timelineMinor -->';
            } else {

                // Ilk döngüyü atlatmak için...
                if ($AhmetiSay != true) {
                    $AhmetiWpTimelineOut .= '</div><!-- /.timelineMajor -->';
                }

                // Yeni bir yıl ise major ekle...
                $AhmetiWpTimelineOut .= '
            <div class="timelineMajor">
                <h2 class="timelineMajorMarker"><span>';

                if ($AhmetiYear < 0) {
                    $AhmetiWpTimelineOut .= __('BC', 'ahmeti-wp-timeline').' '.ltrim($AhmetiYear, '-');
                } else {
                    $AhmetiWpTimelineOut .= (int) $AhmetiYear;
                }

                $AhmetiWpTimelineOut .= '</span></h2>
            
                <dl class="timelineMinor">
                    <dt id="event'.$row_group['event_id'].'"><a>'.$row_group['title'].'</a></dt>
                    <dd class="timelineEvent" id="event'.$row_group['event_id'].'EX" style="display: none; ">
                        <div class="event_content">';

                // Tarih Detay
                if ($AhmetiYear > 0) {
                    $AhmetiWpTimelineOut .= '<span class="AhmetiDate">'.AhmetiWpTimelineDateTitle($row_group['timeline_date'], $ahmetiWpTimelineOpt).'</span>';
                }

                $AhmetiWpTimelineOut .= $row_group['event_content'].'</div><!-- event_content -->
                    </dd><!-- /.timelineEvent -->
                </dl><!-- /.timelineMinor -->';

            }

            $AhmetiWpTimelineEndSqlYear = $AhmetiYear;

            $AhmetiSay = false;

        } // While
        $AhmetiWpTimelineOut .= '</div><!-- /.timelineMajor -->';
        $AhmetiWpTimelineOut .= '</div><!-- /#timelineContainer -->';

        return $AhmetiWpTimelineOut;
    }
}
