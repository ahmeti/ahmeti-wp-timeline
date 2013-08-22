<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
$group_id=(int)$_GET['group_id'];

$group_name=mysql_fetch_array(mysql_query("SELECT group_id,title FROM wp_ahmeti_wp_timeline WHERE group_id=$group_id AND type='group_name' "));
?>

<h2>Grubu Düzenle</h2>
<div style="display: block;">
    <form id="form_gonder" action="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditGroupPost" method="post">
        <h3 style="margin-bottom: 1px;">Grup Adı</h3>
        <input type="text" name="group_name" size="40" value="<?php echo $group_name['title']; ?>"/>
        <input type="hidden" name="group_id" value="<?php echo $group_name['group_id']; ?>"/>
        <br/><br/>
        <input type="submit" value="Grubu Güncelle" class="button" id="gonder_button"/>
    </form>
</div>