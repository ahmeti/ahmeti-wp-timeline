<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
global $wpdb;
$event_id=(int)$_GET['event_id'];

$event = $wpdb->get_row( 'SELECT * FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE event_id='.$event_id.' AND type="event"', ARRAY_A );

$exp_date=explode(' ',$event['timeline_date']);


// event_date Value
if ($exp_date[0] == '0000-00-00'){
    $event_date_val='';
}else{
    $event_date_val=explode('-',$exp_date[0]);
    
    if ($event_date_val[0]=='0000'){ $event_date_val[0]=''; }
    if ($event_date_val[1]=='00'){ $event_date_val[1]=''; }
    if ($event_date_val[2]=='00'){ $event_date_val[2]=''; }
}


// event_time Value
if($exp_date[1]=='00:00:00'){
    $event_time_val='';
}else{
    $event_time_val=$exp_date[1];
}


// event_bc Value
if(empty($event['timeline_bc'])){
    $event_bc_val='';
}else{
    $event_bc_val=$event['timeline_bc'];
}





?>
<?php
function ShowTinyMCE() {
    // conditions here
    wp_enqueue_script( 'common' );
    wp_enqueue_script( 'jquery-color' );
    wp_print_scripts('editor');
    if (function_exists('add_thickbox')) add_thickbox();
    wp_print_scripts('media-upload');
    if (function_exists('wp_tiny_mce')) wp_tiny_mce();
    wp_admin_css();
    wp_enqueue_script('utils');
    do_action("admin_print_styles-post-php");
    do_action('admin_print_styles');
}
add_filter('admin_head','ShowTinyMCE');

wp_register_style( 'AhmetiWpTimelineJqueryUiCss', plugins_url().'/ahmeti-wp-timeline/Admin/Css/smoothness/jquery-ui-1.10.3.custom.min.css',array(),'','screen' );
wp_enqueue_style( 'AhmetiWpTimelineJqueryUiCss' );

?>
<h2><?php echo _e('Edit Event','ahmeti-wp-timeline'); ?></h2>

<div style="display: block;padding: 0 0 10px 0">
    <form id="form_gonder" action="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditEventPost" method="post">
        
        <h3 style="margin-bottom: 1px;"><?php echo _e('Group Name','ahmeti-wp-timeline'); ?></h3>
        <select name="group_id">
            <option><?php echo _e('Select Group...','ahmeti-wp-timeline'); ?></option>
            <?php
                $group_list = $wpdb->get_results( 'SELECT group_id,title FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE type="group_name" ORDER BY title ASC', ARRAY_A );
                foreach ($group_list as $group_row) {
                    ?>
                    <option value="<?php echo $group_row['group_id']; ?>" <?php if($event['group_id']==$group_row['group_id']){ echo 'selected="selected"'; } ?>><?php echo $group_row['title']; ?></option>
                    <?php
                }
            ?>
        </select>
        <br/><br/>
        
        <h3 style="margin-bottom: 1px;"><?php echo _e('Event Title','ahmeti-wp-timeline'); ?></h3>
        <input type="text" name="event_title" size="40" value="<?php echo $event['title']; ?>"/>
        <br/><br/>

        <h3 style="margin-bottom: 1px;"><?php echo _e('Event Time (If Anno Domini)','ahmeti-wp-timeline'); ?></h3>
        <!--<input type="text" id="MyDate" name="event_date" size="40" value="<?php echo $event_date_val; ?>"/> <?php echo _e('[ ! ] e.g.: 2010-01-30 [Year-Month-Day]','ahmeti-wp-timeline'); ?>-->
        
        <div style="overflow: hidden;padding-top: 10px;">
            
            <div style="float: left;margin-right: 20px">
                <div style="float: left;padding-top: 6px"><input style="visibility: hidden;width: 0;margin: 0;padding: 0;"type="text" class="ahmetiDate" name="event_date" size="1" /></div>
            </div>
            
            <div style="float: left;margin-right: 20px">
                <div style="float: left;padding: 5px 4px 0 0;font-weight: bold"><?php echo _e('Year','ahmeti-wp-timeline'); ?></div>
                <div style="float: left"><input type="text" class="ahmetiDateYear" name="event_date_year" size="4" value="<?php echo $event_date_val[0]; ?>"/></div>
            </div>
            
            <div style="float: left;margin-right: 20px">
                <div style="float: left;padding: 5px 4px 0 0;font-weight: bold"><?php echo _e('Month','ahmeti-wp-timeline'); ?></div>
                <div style="float: left"><input type="text" class="ahmetiDateMonth" name="event_date_month" size="2" value="<?php echo $event_date_val[1]; ?>"/></div>
            </div>
            
            <div style="float: left">
                <div style="float: left;padding: 5px 4px 0 0;font-weight: bold"><?php echo _e('Day','ahmeti-wp-timeline'); ?></div>
                <div style="float: left"><input type="text" class="ahmetiDateDay" name="event_date_day" size="2" value="<?php echo $event_date_val[2]; ?>"/></div>
            </div>
        </div>
        
        <br/>
        <input type="text" name="event_time" id="EventTime" size="10" maxlength="8" value="<?php echo $event_time_val; ?>"/> <?php echo _e('If you want you can also add time. [ ! ] e.g.: 14:30:45 [Hour-Minute-Second]','ahmeti-wp-timeline'); ?>
        <br/><br/>
        
        <h3 style="margin-bottom: 1px;"><?php echo _e('Event Time (If Before Christ)','ahmeti-wp-timeline'); ?></h3>
        <input type="text" name="event_bc" size="40" id="event_bc_input" value="<?php echo ltrim($event_bc_val,'-'); ?>"/> <?php echo _e('[ ! ] e.g.: 2000','ahmeti-wp-timeline'); ?>
        <br/><br/>
        <br/><br/>
        
        <div style="width: 650px">
            <?php wp_editor($event['event_content'],'event_content',$settings = array('textarea_rows'=> 20 ,'wpautop' => false));?>
        </div>
        
        <br/><br/>
        <input type="hidden" value="<?php echo $event['event_id']; ?>" name="event_id" />
        <input type="submit" value="<?php echo _e('Update Event','ahmeti-wp-timeline'); ?>" class="button" id="gonder_button"/>
        
    </form>
</div>