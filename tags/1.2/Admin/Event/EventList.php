<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<h2>Timeline Olay Listesi</h2>
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
    <table style="width: 700px">
        <tr>
            <td style="padding: 5px;border: 1px solid #ddd;width: 20px;font-weight: bold">Event_ID</td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold">Grup Adı</td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold">Olay Başlığı</td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold">Olay Zamanı</td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold">Düzenle</td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold">Sil</td>
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
                <a href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditEventForm&event_id=<?php echo $event['event_id']; ?>"><img src="<?php echo plugins_url().'/ahmeti-wp-timeline/images/edit.png'; ?>" /></a>
            </td>
            <td style="padding: 5px;border: 1px solid #ddd;">
                <a onclick="return confirm('Olayı silmek istediğinizden emin misiniz?')" href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=DeleteEventPost&event_id=<?php echo $event['event_id']; ?>"><img src="<?php echo plugins_url().'/ahmeti-wp-timeline/images/delete.png'; ?>" /></a>
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
    <p class="ahmeti_hata">Hiç olay eklememişsiniz :(</p>
    <?php
}
?>