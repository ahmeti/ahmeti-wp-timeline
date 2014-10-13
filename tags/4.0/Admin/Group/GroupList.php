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

$group_list = $wpdb->get_results( 'SELECT group_id,title FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE type="group_name" ORDER BY group_id DESC LIMIT '.$baslangic.','.$page_limit, ARRAY_A );

//var_dump($group_list);

if($toplam_sayfa > 0){
    ?>
    <table style="width: 700px" class="ahmetiwptablestd">
        <tr>
            <td style="padding: 5px;border: 1px solid #ddd;width: 20px;font-weight: bold"><?php echo _e('Group ID','ahmeti-wp-timeline'); ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold"><?php echo _e('Group Name','ahmeti-wp-timeline'); ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold"><?php echo _e('Edit','ahmeti-wp-timeline'); ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold"><?php echo _e('Delete','ahmeti-wp-timeline'); ?></td>
        </tr>
        <?php
        foreach($group_list as $group_name){
        ?>
        <tr>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $group_name['group_id']; ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $group_name['title']; ?></td>
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