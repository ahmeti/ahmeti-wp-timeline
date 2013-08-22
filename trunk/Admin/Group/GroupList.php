<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<h2>Timeline Grup Listesi</h2>
<?php

/* Sayfalama İçin */
$page=@$_GET['is_page'];
(int)$page_limit=get_option('AhmetiWpTimelinePageLimit');

$group_say=mysql_fetch_assoc(mysql_query("SELECT COUNT(group_id) FROM wp_ahmeti_wp_timeline WHERE type='group_name' "));

if(empty($page) || !is_numeric($page)){
    $baslangic=1;
    $page=1;
}else{
    $baslangic=$page;
}

$toplam_sayfa=(int)$group_say['COUNT(group_id)'];
$baslangic=($baslangic-1)*$page_limit;

$group_list=mysql_query("SELECT group_id,title FROM wp_ahmeti_wp_timeline WHERE type='group_name' ORDER BY group_id DESC LIMIT $baslangic,$page_limit");

if($toplam_sayfa > 0){
    ?>
    <table style="width: 700px">
        <tr>
            <td style="padding: 5px;border: 1px solid #ddd;width: 20px;font-weight: bold">Grup_ID</td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 100px;font-weight: bold">Grup Adı</td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold">Düzenle</td>
            <td style="padding: 5px;border: 1px solid #ddd;width: 80px;font-weight: bold">Sil</td>
        </tr>
        <?php
        while ($group_name=mysql_fetch_assoc($group_list)){
        ?>
        <tr>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $group_name['group_id']; ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;"><?php echo $group_name['title']; ?></td>
            <td style="padding: 5px;border: 1px solid #ddd;">
                <a href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditGroupForm&group_id=<?php echo $group_name['group_id']; ?>"><img src="<?php echo plugins_url().'/ahmeti-wp-timeline/images/edit.png'; ?>" /></a>
            </td>
            <td style="padding: 5px;border: 1px solid #ddd;">
                <a onclick="return confirm('Gruba ait olaylar da silinecektir. Grubu silmek istediğinizden emin misiniz?')" href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=DeleteGroupPost&group_id=<?php echo $group_name['group_id']; ?>"><img src="<?php echo plugins_url().'/ahmeti-wp-timeline/images/delete.png'; ?>" /></a>
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
    <p class="ahmeti_hata">Hiç grup eklememişsiniz :(</p>
    <?php
}
?>