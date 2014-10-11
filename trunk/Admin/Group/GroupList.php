<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<h2><?php echo _e('Timeline Group List','ahmeti-wp-timeline'); ?></h2>

<?php
global $wpdb;

/* OPTIONS*/
$ahmetiWpTimelineOpt=json_decode(get_option('AhmetiWpTimelineOptions'));

/* Sayfalama İçin */
$page=@$_GET['is_page'];
(int)$page_limit=$ahmetiWpTimelineOpt->PageLimit;

$group_say = $wpdb->get_row( 'SELECT COUNT(group_id) as GroupSay FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE type="group_name"', OBJECT );

if(empty($page) || !is_numeric($page)){
    $baslangic=1;
    $page=1;
}else{
    $baslangic=$page;
}

$toplam_sayfa=(int)$group_say->GroupSay;
$baslangic=($baslangic-1)*$page_limit;

//$group_list = $wpdb->get_results( 'SELECT group_id,title FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE type="group_name" ORDER BY group_id DESC LIMIT '.$baslangic.','.$page_limit, ARRAY_A );


$group_list = $wpdb->get_results( 'SELECT `group_id`,`title`,
(select COUNT(`event_id`) from `'.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline` as `sub_ahmeti_wp_timeline` WHERE `sub_ahmeti_wp_timeline`.`group_id`=`wp_ahmeti_wp_timeline`.`group_id` AND `sub_ahmeti_wp_timeline`.`type`="event" ) as `event_count`
FROM `'.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline`
where `wp_ahmeti_wp_timeline`.`type` ="group_name" ORDER BY `wp_ahmeti_wp_timeline`.`group_id` DESC LIMIT '.$baslangic.','.$page_limit, ARRAY_A );

//var_dump($group_list);

//var_dump($wpdb->last_query());

if($toplam_sayfa > 0){
    ?>
    <table class="ahmetiwptablestd">
        <tr>
            <th style="padding: 5px;border: 1px solid #ddd;width: 20px;font-weight: bold"><?php echo _e('Group ID','ahmeti-wp-timeline'); ?></th>
            <th style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold"><?php echo _e('Group Name','ahmeti-wp-timeline'); ?></th>
            <th style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold"><?php echo _e('Count Events','ahmeti-wp-timeline'); ?></th>
            <th style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold"><?php echo _e('Edit','ahmeti-wp-timeline'); ?></th>
            <th style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold"><?php echo _e('Delete','ahmeti-wp-timeline'); ?></th>
        </tr>
        <?php
        foreach($group_list as $group_name){
        ?>
        <tr>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $group_name['group_id']; ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;"><a href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EventList&group_id=<?php echo $group_name['group_id']; ?>"><?php echo $group_name['title']; ?></a></td>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $group_name['event_count']; ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;">
                <a href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditGroupForm&group_id=<?php echo $group_name['group_id']; ?>"><img style="width: 20px" src="<?php echo plugins_url().'/ahmeti-wp-timeline/images/edit.png'; ?>" /></a>
            </td>
            <td style="padding: 5px;border: 1px solid #ddd;">
                <a onclick="return confirm('<?php echo _e('Events belonging to the group will also be deleted. Are you sure you want to delete this group?','ahmeti-wp-timeline'); ?>')" href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=DeleteGroupPost&group_id=<?php echo $group_name['group_id']; ?>"><img style="width: 20px" src="<?php echo plugins_url().'/ahmeti-wp-timeline/images/delete.png'; ?>" /></a>
            </td>            

        </tr>
        <?php
        }

        ?>
    </table>

    <?php            

        
        AhmetiWpTimelineSayfala(AHMETI_WP_TIMELINE_ADMIN_URL,$toplam_sayfa,$page,$page_limit,'&islem=GroupList');

}else{
    // Söz yok ise uyarı mesajı ver.
    ?>
    <p class="ahmeti_hata"><?php echo _e('You have not added any group :(','ahmeti-wp-timeline'); ?></p>
    <?php
}
?>