<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<div id="ahmeti_wrap" style="padding:10px">
    <h1 style="font:oblique 30px/30px Georgia,serif; color:grey;background-image: url('<?php echo plugins_url(); ?>/ahmeti-wp-timeline/images/ahmeti-wp-timeline-logo.png');background-repeat: no-repeat;padding: 0px 10px 10px 47px;background-position: 0 0;">Ahmeti WP Timeline <sup style="font-size: 14px">2.1</sup></h1>

    <a style="margin-right:15px;" class="button" href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>"><?php echo _e('Group List','ahmeti-wp-timeline'); ?></a>
    <a style="margin-right:15px;" class="button" href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=NewGroupForm"><?php echo _e('Add New Group','ahmeti-wp-timeline'); ?></a>

    &nbsp;&nbsp;

    <a style="margin-right:15px;" class="button" href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EventList"><?php echo _e('Event List','ahmeti-wp-timeline'); ?></a>
    <a style="margin-right:15px;" class="button" href="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=NewEventForm"><?php echo _e('Add New Event','ahmeti-wp-timeline'); ?></a>
<br/><br/>