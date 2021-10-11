<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>

<h2><?php echo _e('Add New Group','ahmeti-wp-timeline'); ?></h2>

<div style="display: block;padding: 0 0 10px 0">
    <form id="form_gonder" action="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=NewGroupPost" method="post">
        <h3 style="margin-bottom: 1px;"><?php echo _e('Group Name','ahmeti-wp-timeline'); ?></h3>
        <input type="text" name="group_name" size="40"/>
        <br/><br/>
        <input type="submit" value="<?php echo _e('Add Group','ahmeti-wp-timeline'); ?>" class="button" id="gonder_button"/>
    </form>
</div>