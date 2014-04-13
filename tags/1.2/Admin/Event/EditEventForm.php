<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
$event_id=(int)$_GET['event_id'];

$event=mysql_fetch_array(mysql_query('SELECT * FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE event_id='.$event_id.' AND type="event" '));


$exp_date=explode(' ',$event['timeline_date']);


// event_date Value
if ($exp_date[0] == '0000-00-00'){
    $event_date_val='';
}else{
    $event_date_val=$exp_date[0];
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
<h2>Olayı Düzenle</h2>

<div style="display: block;padding: 0 0 10px 0">
    <form id="form_gonder" action="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditEventPost" method="post">
        
        <h3 style="margin-bottom: 1px;">Grup Adı</h3>
        <select name="group_id">
            <option>Grubu Seçiniz...</option>
            <?php
                $group_list=mysql_query('SELECT group_id,title FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE type="group_name" ORDER BY title ASC');
                while($group_row=mysql_fetch_array($group_list)){
                    ?>
                    <option value="<?php echo $group_row['group_id']; ?>" <?php if($event['group_id']==$group_row['group_id']){ echo 'selected="selected"'; } ?>><?php echo $group_row['title']; ?></option>
                    <?php
                }
            ?>
        </select>
        <br/><br/>
        
        <h3 style="margin-bottom: 1px;">Olay Başlığı</h3>
        <input type="text" name="event_title" size="40" value="<?php echo $event['title']; ?>"/>
        <br/><br/>

        <h3 style="margin-bottom: 1px;">Olay Zamanı (Milattan Sonra)</h3>
        <input type="text" id="MyDate" name="event_date" size="40" value="<?php echo $event_date_val; ?>"/> [ ! ] Örnegin: 2010-01-30 [Yıl-Ay-Gün]
        <br/>
        <input type="text" name="event_time" id="EventTime" size="40" maxlength="8" value="<?php echo $event_time_val; ?>"/> İsterseniz zamanı ekleyebilirsiniz. [ ! ] Örnegin: 14:30:45 [Saat-Dakika-Saniye]
        <br/><br/>
        
        <h3 style="margin-bottom: 1px;">Olay Zamanı (Milattan Önce)</h3>
        <input type="text" name="event_bc" size="40" value="<?php echo ltrim($event_bc_val,'-'); ?>"/> [ ! ] Örnegin: 15000
        <br/><br/>
        <br/><br/>
        
        <div style="width: 650px">
            <?php wp_editor($event['event_content'],'event_content',$settings = array('textarea_rows'=> 20 ,'wpautop' => false));?>
        </div>
        
        <br/><br/>
        <input type="hidden" value="<?php echo $event['event_id']; ?>" name="event_id" />
        <input type="submit" value="Olayı Güncelle" class="button" id="gonder_button"/>
        
    </form>
</div>