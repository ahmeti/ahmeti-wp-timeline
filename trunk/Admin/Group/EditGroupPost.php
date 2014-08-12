<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_POST)){

    $id=(int)$_POST['group_id'];
    $title=trim(stripslashes($_POST['group_name']));

    if( empty($title) || empty($id) ){
        ?>
        <p class="ahmeti_hata"><?php echo _e('Do not leave empty fields.','ahmeti-wp-timeline'); ?></p>
        <?php
    }else{

        global $wpdb;
        
        $sql=$wpdb->update( 
                AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline', 
                array( 
                    'title' => $title	// string
                ), 
                array( 'group_id' => $id ), 
                array( 
                    '%s'
                ), 
                array( '%d' )
        );

        if ($sql){
            ?>
            <p class="ahmeti_ok"><?php echo _e('Group successfully updated.','ahmeti-wp-timeline'); ?></p>
            <?php
        }else{
            ?>
            <p class="ahmeti_hata"><?php echo _e('An error occurred while updating the group.','ahmeti-wp-timeline'); ?></p>
            <?php
        }                
    }
}
?>