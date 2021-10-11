<?php
/*
 *  LICENSE:
 * 
 *  Everyone is permitted to copy and distribute verbatim copies
 *  of this license document, but changing it is not allowed.
 * 
 *  Developer (Ahmet İmamoğlu, ahmeti.net)
 * 
 */

if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); }

function Ahmeti_Wp_Timeline_Kurulum()
{
    global $wpdb;
    
    // Veri Tabanı Oluştur...
    $create_table = AHMETI_WP_TIMELINE_DB_PREFIX . 'ahmeti_wp_timeline';
  

     // Tablolar Var Mı? Eğer varsa hiç bir şey yapma...
    $table_list_array=array();
    $table_list_array = $wpdb->get_results( 'SHOW TABLES FROM '.DB_NAME, ARRAY_A );

    
    // Tablo var mı?
    if (in_array('ahmeti_wp_timeline',$table_list_array)){
        // Tablo var
    }else{
        // SQL Çalıştır
        $db_sql="CREATE TABLE IF NOT EXISTS `$create_table` (
        `event_id` bigint(20) NOT NULL AUTO_INCREMENT,
        `group_id` smallint(6) NOT NULL DEFAULT '0',
        `timeline_bc` bigint(20) DEFAULT '0',
        `timeline_date` datetime DEFAULT '0000-00-00 00:00:00',
        `title` varchar(255) DEFAULT NULL,
        `event_content` mediumtext,
        `type` enum('event','group_name') NOT NULL DEFAULT 'event' COMMENT 'grup adları almak için group_name listele, diğerleri event olacaktır.',
        PRIMARY KEY (`event_id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ";
        $wpdb->query( $db_sql );
    }  
    

    // Ayar Meta Var mı?
    if (get_option('AhmetiWpTimelineOptions') == false ){
        
        $options=array(
            'DefaultSort' => 'ASC',
            'StartState' => 'close',
            'PageLimit' => '20',
            'DateFormatYear' => 'Y',
            'DateFormatYearMonth' => 'm.Y',
            'DateFormatYearMonthDay' => 'd.m.Y',
            'DateFormatHourMinutesSecond' => 'H:i:s'
        );
        
        add_option('AhmetiWpTimelineOptions', json_encode($options));
    }
    
    // Old Meta Delete
    if (get_option('AhmetiWpTimelinePageLimit') !== false ){
        delete_option( 'AhmetiWpTimelinePageLimit' );
    }
    
    

}

function Ahmeti_Wp_Timeline_Admin_Head()
{
    /* Wp Admin Head */
    
    load_plugin_textdomain('ahmeti-wp-timeline', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
     
    wp_enqueue_script('jquery');

    /* Datepicker */
    if (@$_GET['islem']=='NewEventForm' || @$_GET['islem']=='EditEventForm' || @$_GET['islem']=='EventList'){
        wp_enqueue_script('jquery-ui-datepicker');
        wp_register_style( 'AhmetiWpTimelineAdminJqueryUi', plugins_url().'/ahmeti-wp-timeline/Admin/Css/smoothness/jquery-ui-1.10.3.custom.min.css',array(),'','screen' );
        wp_enqueue_style( 'AhmetiWpTimelineAdminJqueryUi' );
    }
    
    wp_register_script('AhmetiWpTimelineAdminJs', plugins_url().'/ahmeti-wp-timeline/Admin/Js/AhmetiWpTimelineAdmin.js', array( 'jquery' ));
    wp_enqueue_script('AhmetiWpTimelineAdminJs');
    
    /* Start Add Js Variables */
    $JsData = array('pluginUrl' => plugins_url().'/ahmeti-wp-timeline/' ,'pluginAdminUrl' => AHMETI_WP_TIMELINE_ADMIN_URL);
    wp_localize_script('AhmetiWpTimelineAdminJs', 'AhmetiWpTimelineJsData', $JsData);
    /* End Add Js Variables */
    
    wp_register_style( 'AhmetiWpTimelineAdminCss', plugins_url().'/ahmeti-wp-timeline/Admin/Css/AhmetiWpTimelineAdmin.css',array(),'','screen' );
    wp_enqueue_style( 'AhmetiWpTimelineAdminCss' );
}



function Ahmeti_Wp_Timeline_Head()
{
    
    
    /* Get Short Code Parameters */
    $dat = array();
    preg_match("/\[ahmetiwptimeline (.+?)\]/", get_post_field('post_content', get_the_ID()), $dat);
    
    if ( empty($dat)){
        return false;
    }
    
    $dat = array_pop($dat);
    $dat= explode(" ", $dat);
    $params = array();
    foreach ($dat as $d){
        list($opt, $val) = explode("=", $d);
        $params[$opt] = trim($val, '"');
    }
    /* End Short Code Parameters */
    
    
    
    /* Wp User Head */
    load_plugin_textdomain('ahmeti-wp-timeline', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
    
    /* JS */
    wp_enqueue_script('jquery');
    
    wp_register_script('timelinerColorboxJs', plugins_url().'/ahmeti-wp-timeline/TimelinerJquery/inc/colorbox.js', array( 'timelinerTimelinerJs' ));
    wp_enqueue_script('timelinerColorboxJs');
    
    

    
    wp_register_script( 'timelinerTimelinerJs', plugins_url().'/ahmeti-wp-timeline/TimelinerJquery/js/timeliner.min.js', array( 'jquery' ));
    $translation_array = array( 'ExpandAll' => __('+ Expand All','ahmeti-wp-timeline'), 'CollapseAll' => __('- Collapse All','ahmeti-wp-timeline'), 'State'=> $params['state']);
    wp_localize_script( 'timelinerTimelinerJs', 'timelinerTimelinerJsObject', $translation_array );
    wp_enqueue_script('timelinerTimelinerJs');
    
    
    
    /* CSS */
    wp_register_style( 'timelinerColorboxCss', plugins_url().'/ahmeti-wp-timeline/TimelinerJquery/inc/colorbox.css',array(),'','screen' );
    wp_enqueue_style( 'timelinerColorboxCss' );
    
    wp_register_style( 'timelinerScreenCss', plugins_url().'/ahmeti-wp-timeline/TimelinerJquery/css/screen.css',array(),'','screen' );
    wp_enqueue_style( 'timelinerScreenCss' );
    
}
 


function Ahmeti_Wp_Timeline_Admin()
{
    /* Admin Menü */
    add_action('admin_enqueue_scripts', 'Ahmeti_Wp_Timeline_Admin_Head');
    add_menu_page( 'Ahmeti Wp Timeline', 'Timeline', 'edit_pages', 'ahmeti-wp-timeline/index.php', 'Ahmeti_Wp_Timeline_Index', plugins_url('ahmeti-wp-timeline/images/ahmeti-wp-timeline-icon.png') , '6.9');
    //load_plugin_textdomain('ahmeti-wp-timeline', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
}



function AhmetiWpTimelineDateTitle($mysqlDateTime,$options)
{

    $explTime=explode(' ',$mysqlDateTime);
    // $explTime[0] // 2012-12-12
    // $explTime[1] // 00:00:00

    $explDate=explode('-',$explTime[0]);
    // $explDate[0] // Year
    // $explDate[1] // Month
    // $explDate[2] // Day	

    
    if ($explDate[2] > 0 && $explTime[1] != '00:00:00'){
        // D-M-Y And H:i:s
        if ( empty($options->DateFormatYearMonthDay) || empty($options->DateFormatHourMinutesSecond)){
            return $mysqlDateTime;
        }else{
            return date( $options->DateFormatYearMonthDay." ".$options->DateFormatHourMinutesSecond, strtotime($mysqlDateTime));
        }
        
    }elseif( $explDate[2] > 0){
        // D-M-Y
        if ( empty($options->DateFormatYearMonthDay) ){
            return $explTime[0];
        }else{
            return date( $options->DateFormatYearMonthDay, strtotime($explDate[0].'-'.$explDate[1].'-'.$explDate[2]));
        }
        
    }elseif($explDate[1] > 0 ){
        // M-Y
        if ( empty($options->DateFormatYearMonth) ){
            return $explDate[0].'-'.$explDate[1];
        }else{
            return date( $options->DateFormatYearMonth, strtotime($explDate[0].'-'.$explDate[1]));
        }
        
    }else{
        // Y
        if ( empty($options->DateFormatYear) ){
            return $explDate[0];
        }else{
            return date( $options->DateFormatYear, strtotime(($explDate[0]+1).'-00'));
        }
        
    }

}



function AhmetiWpTimelineGetYear($mysqlDateTime){
    
    $explDate=explode('-',$mysqlDateTime,2);
    return $explDate[0];
}



function AhmetiWpTimelineShortCodeOutput( $atts ) {
    
    global $wpdb;
    
    /*
     * Aynı yıl içinde varsa bir kaç tane olay varsa yılın içine ekle...
     * 
     */
    
    //echo _e('Hepsini_Ac','ahmeti-wp-timeline');
    
    /* OPTIONS*/
    $ahmetiWpTimelineOpt=json_decode(get_option('AhmetiWpTimelineOptions'));

    /* SHORTCODE */
    $group_id=$atts['groupid'];
    $sort=@$atts['sort'];
    $state=@$atts['state'];
    
    
    if (empty($sort)){
        $sort=$ahmetiWpTimelineOpt->DefaultSort;
        if ( empty($sort)){
            $sort='DESC';
        }
    }
    
    if (empty($state)){
        $state=$ahmetiWpTimelineOpt->StartState;
        if ( empty($state)){
            $state='close';
        }
    }

    $AhmetiWpTimelineEndSqlYear='';
    $AhmetiWpTimelineOut='<div id="timelineContainer" unselectable="on">';
    
    if ($state=='open'){
        $AhmetiWpTimelineOut.='<a id="AhmetiExpandButton" class="expandAll expanded" style="float:right">'.__('- Collapse All','ahmeti-wp-timeline').'</a><br/>';
    }else{
        $AhmetiWpTimelineOut.='<a id="AhmetiExpandButton" class="expandAll" style="float:right">'.__('+ Expand All','ahmeti-wp-timeline').'</a><br/>';
    }
    
    

    $AhmetiSay=true;

    
    
    $sql_group = $wpdb->get_results( 'SELECT * FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE group_id="'.$group_id.'" AND type="event" ORDER BY timeline_bc '.$sort.', timeline_date '.$sort , ARRAY_A );
    
    foreach($sql_group as $row_group){

        if ($row_group['timeline_bc'] < 0 ){
            $AhmetiYear=$row_group['timeline_bc'];
        }elseif( AhmetiWpTimelineGetYear($row_group['timeline_date'] ) > 0 ){
            // Sadece Yılı Al...
            $AhmetiYear=AhmetiWpTimelineGetYear($row_group['timeline_date']);
        }
        
        
        
        if ($AhmetiYear==$AhmetiWpTimelineEndSqlYear){
            
            // Yılın İçine Ekle
            $AhmetiWpTimelineOut.='
                <dl class="timelineMinor">
                    <dt id="event'.$row_group['event_id'].'"><a>'.$row_group['title'].'</a></dt>
                    <dd class="timelineEvent" id="event'.$row_group['event_id'].'EX" style="display: none; ">
                        <div class="event_content">';
                            
                            // Tarih Detay
                            if($AhmetiYear > 0){
                                $AhmetiWpTimelineOut.='<span class="AhmetiDate">'.AhmetiWpTimelineDateTitle($row_group['timeline_date'],$ahmetiWpTimelineOpt).'</span>';
                            }
            
                            $AhmetiWpTimelineOut.=$row_group['event_content'].'</div><!-- event_content -->
                    </dd><!-- /.timelineEvent -->
                </dl><!-- /.timelineMinor -->';
        }else{
            
            // Ilk döngüyü atlatmak için...
            if ($AhmetiSay != true){
                $AhmetiWpTimelineOut.='</div><!-- /.timelineMajor -->';
            }
            
            // Yeni bir yıl ise major ekle...
            $AhmetiWpTimelineOut.='
            <div class="timelineMajor">
                <h2 class="timelineMajorMarker"><span>';
            
            if($AhmetiYear < 0){ $AhmetiWpTimelineOut.=__('BC','ahmeti-wp-timeline').' '.ltrim($AhmetiYear,'-'); }else{ $AhmetiWpTimelineOut.=(int)$AhmetiYear; }
            
            $AhmetiWpTimelineOut.='</span></h2>
            
                <dl class="timelineMinor">
                    <dt id="event'.$row_group['event_id'].'"><a>'.$row_group['title'].'</a></dt>
                    <dd class="timelineEvent" id="event'.$row_group['event_id'].'EX" style="display: none; ">
                        <div class="event_content">';
            
                            // Tarih Detay
                            if($AhmetiYear > 0){
                                $AhmetiWpTimelineOut.='<span class="AhmetiDate">'.AhmetiWpTimelineDateTitle($row_group['timeline_date'],$ahmetiWpTimelineOpt).'</span>';
                            }
                            
                        $AhmetiWpTimelineOut.=$row_group['event_content'].'</div><!-- event_content -->
                    </dd><!-- /.timelineEvent -->
                </dl><!-- /.timelineMinor -->';
            
        }
        
        $AhmetiWpTimelineEndSqlYear=$AhmetiYear;
        
        $AhmetiSay=false;
        
    } // While
    $AhmetiWpTimelineOut.='</div><!-- /.timelineMajor -->';
    $AhmetiWpTimelineOut.='</div><!-- /#timelineContainer -->';
    return $AhmetiWpTimelineOut;
}


$pageslang=__('Pages','ahmeti-wp-timeline');

function AhmetiWpTimelineSayfala($site_url,$top_sayfa,$page,$limit,$page_url)
{
    // Sayfalama Şeridimiz

    if($top_sayfa > $limit) :

        
        
        echo '<div id="sayfala"><span class="say_sabit">'.__('Pages','ahmeti-wp-timeline').'</span>';

        $x=5; // Aktif sayfadan önceki/sonraki sayfa gösterim sayisi
        $lastP=ceil($top_sayfa / $limit);

        // sayfa 1'i yazdir
        if($page == 1){
            echo '<span class="say_aktif">1</span>';
        }else{
            echo '<a class="say_a" href="'.$site_url.''.$page_url.'">1</a>';
        }

        // "..." veya direkt 2
        if($page - $x > 2){
            echo '<span class="say_b">...</span>';
            $i=$page - $x;
        }else{
            $i=2;
        }
        // +/- $x sayfalari yazdir
        for($i; $i <= $page + $x; $i++){
            if($i == $page)
                echo '<span class="say_aktif">'.$i.'</span>';
            else
                echo '<a class="say_a" href="'.$site_url.''.$page_url.'&is_page='.$i.'">'.$i.'</a>';
            if($i == $lastP)
                break;
        }

        // "..." veya son sayfa
        if($page + $x < $lastP - 1){
            echo '<span class="say_b">...</span>';
            echo '<a class="say_a" href="'.$site_url.''.$page_url.'&is_page='.$lastP.'">'.$lastP.'</a>';
        }elseif($page + $x == $lastP - 1){
            echo '<a class="say_a" href="'.$site_url.''.$page_url.'&is_page='.$lastP.'">'.$lastP.'</a>';
        }
        echo '</div>';//#sayfala
    endif;
}
    
    
class AhmetiWpTimelineAddEditorButton{
    # Plugin Name: mygallery
    # Plugin URI: http://wphardcore.com
    # Description: A simple user interface for Gallery shortcode
    # Version: 0.1
    # Author: Gary Cao
    # Author URI: http://garyc40.com



    public function __construct()
    {
        add_action('admin_init',array($this,'action_admin_init'));
    }

    public function action_admin_init()
    {
        // only hook up these filters if we're in the admin panel, and the current user has permission
        // to edit posts and pages
        if(current_user_can('edit_posts') && current_user_can('edit_pages')){
            
            global $wp_version;

            if (version_compare($wp_version,'3.9','<')){

                // Old TinyMce 3.0
                add_filter('mce_buttons',array($this,'filter_mce_button')); // < WP 3.9
                add_filter('mce_external_plugins',array($this,'filter_mce_plugin')); // < WP 3.9
            }else{

                // New TinyMce 4.0
                add_filter( 'mce_external_plugins', array($this,'my_add_tinymce_plugin') );
                add_filter( 'mce_buttons', array($this,'my_register_mce_button') );

            }

        }
    }

    public function filter_mce_button($buttons)
    {
        // add a separation before our button, here our button's id is "mygallery_button"
        array_push($buttons,'|','mygallery_button');
        return $buttons;
    }

    public function filter_mce_plugin($plugins)
    {
        
        // this plugin file will work the magic of our button
        
        if (WPLANG == 'tr_TR'){
            $plugins['mygallery']=plugins_url().'/ahmeti-wp-timeline/Admin/Js/EditorButton-tr_TR.js';
        }else{
            $plugins['mygallery']=plugins_url().'/ahmeti-wp-timeline/Admin/Js/EditorButton-en_US.js';
        }
        return $plugins;
    }
    
    // Declare script for new button
    public function my_add_tinymce_plugin( $plugin_array ) {
            $plugin_array['my_mce_button'] = plugins_url().'/ahmeti-wp-timeline/Admin/Js/EditorButtonTinyMce4.0.js';
            //$plugin_array['my_mce_button'] = plugins_url().'/ahmeti-wp-timeline/Admin/EditorButton/EditorButton-tr_TR.js';
            return $plugin_array;
    }

    // Register new button in the editor
    public function my_register_mce_button( $buttons ) {
            array_push( $buttons, 'my_mce_button' );
            return $buttons;
    }
    
}
?>