<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_GET)){

    (int)$id=trim(stripslashes($_GET['event_id']));

    if( empty($id) ){
    ?>
        <p class="ahmeti_hata"><?php echo _e('An error has occurred.','ahmeti-wp-timeline'); ?></p>
    <?php
    }else{

        global $wpdb;
        $sql=$wpdb->delete( AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline', array( 'event_id' => $id, 'type'=>'event' ), array( '%d','%s' ) );

        if ($sql){
            ?>
            <p class="ahmeti_ok"><?php echo _e('Event deleted successfully.','ahmeti-wp-timeline'); ?></p>
            <?php
        }else{
            ?>
            <p class="ahmeti_hata"><?php echo _e('An error occurred while deleting this event.','ahmeti-wp-timeline'); ?></p>
            <?php
        }                
    }
}
?>