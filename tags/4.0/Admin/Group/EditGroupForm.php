<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
$group_id=(int)$_GET['group_id'];

global $wpdb;
$group_name = $wpdb->get_row( 'SELECT group_id,title FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE group_id="'.$group_id.'" AND type="group_name" ', ARRAY_A );

?>

<h2><?php echo _e('Edit Group','ahmeti-wp-timeline'); ?></h2>
<div style="display: block;">
    <form id="form_gonder" action="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditGroupPost" method="post">
        <h3 style="margin-bottom: 1px;"><?php echo _e('Group Name','ahmeti-wp-timeline'); ?></h3>
        <input type="text" name="group_name" size="40" value="<?php echo $group_name['title']; ?>"/>
        <input type="hidden" name="group_id" value="<?php echo $group_name['group_id']; ?>"/>
        <br/><br/>
        <input type="submit" value="<?php echo _e('Update Group','ahmeti-wp-timeline'); ?>" class="button" id="gonder_button"/>
    </form>
</div>