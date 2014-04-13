<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<h2><?php echo _e('Timeline Event List','ahmeti-wp-timeline'); ?></h2>
<?php

/* Sayfalama İçin */
$page=@$_GET['is_page'];
(int)$page_limit=get_option('AhmetiWpTimelinePageLimit');

$group_say=mysql_fetch_assoc(mysql_query('SELECT COUNT(event_id) FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE type="event" '));

if(empty($page) || !is_numeric($page)){
    $baslangic=1;
    $page=1;
}else{
    $baslangic=$page;
}

$groupDb=array();
$grouplist=mysql_query('SELECT group_id,title FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE type="group_name"');
while($group=mysql_fetch_array($grouplist)){
    $groupDb[$group['group_id']]=$group['title'];
}

$toplam_sayfa=(int)$group_say['COUNT(event_id)'];
$baslangic=($baslangic-1)*$page_limit;

$event_list=mysql_query('SELECT event_id,group_id,title,timeline_bc,timeline_date FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE type="event" ORDER BY event_id DESC LIMIT '.$baslangic.','.$page_limit);

if($toplam_sayfa > 0){
    ?>
    <table style="width: 700px" class="ahmetiwptablestd">
        <tr>
            <td style="padding: 5px;border: 1px solid #ddd;width: 20px;font-weight: bold"><?php echo _e('Event ID','ahmeti-wp-timeline'); ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold"><?php echo _e('Group Name','ahmeti-wp-timeline'); ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold"><?php echo _e('Event Title','ahmeti-wp-timeline'); ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold"><?php echo _e('Event Time','ahmeti-wp-timeline'); ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold"><?php echo _e('Edit','ahmeti-wp-timeline'); ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold"><?php echo _e('Delete','ahmeti-wp-timeline'); ?></td>
        </tr>
        <?php
        while ($event=mysql_fetch_assoc($event_list)){
        ?>
        <tr>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $event['event_id']; ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $groupDb[$event['group_id']]; ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $event['title']; ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;">
                <?php 
                if ($event['timeline_bc'] > 0 ){
                    echo 'M.Ö. '.$event['timeline_bc'];
                }else{
                    echo $event['timeline_date'];
                }
                ?>
            </td>
            <td style="padding: 5px;border: 1px solid #ddd;">
                <a href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditEventForm&event_id=<?php echo $event['event_id']; ?>"><img style="width: 20px" src="<?php echo plugins_url().'/ahmeti-wp-timeline/images/edit.png'; ?>" /></a>
            </td>
            <td style="padding: 5px;border: 1px solid #ddd;">
                <a onclick="return confirm('<?php echo _e('Are you sure you want to delete this event?','ahmeti-wp-timeline'); ?>')" href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=DeleteEventPost&event_id=<?php echo $event['event_id']; ?>"><img style="width: 20px" src="<?php echo plugins_url().'/ahmeti-wp-timeline/images/delete.png'; ?>" /></a>
            </td>            

        </tr>
        <?php
        }

        ?>
    </table>

    <?php            

        
        AhmetiWpTimelineSayfala(AHMETI_WP_TIMELINE_ADMIN_URL,$toplam_sayfa,$page,$page_limit,'&islem=EventList');

}else{
    // Söz yok ise uyarı mesajı ver.
    ?>
    <p class="ahmeti_hata"><?php echo _e('You have not added any event :(','ahmeti-wp-timeline'); ?></p>
    <?php
}
?>