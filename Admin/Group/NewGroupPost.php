<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_POST)){

    $title=trim(stripslashes($_POST['group_name']));

    
    if(empty($title)){

        ?>
        <p class="ahmeti_hata"><?php echo _e('Do not leave empty fields.','ahmeti-wp-timeline'); ?></p>
        <?php

    }else{

        global $wpdb;
        
        $last_group_id = $wpdb->get_row( 'SELECT group_id FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline ORDER BY group_id DESC LIMIT 0,1', OBJECT );
        
        $group_id=@(int)$last_group_id->group_id + 1;
        
        $sql=$wpdb->query($wpdb->prepare( 
                "
                INSERT INTO ".AHMETI_WP_TIMELINE_DB_PREFIX."ahmeti_wp_timeline
                ( group_id, title, type , timeline_bc ,timeline_date , event_content)
                VALUES ( %d, %s, %s ,%s, %s, %s)
                ", 
                $group_id, 
                $title, 
                'group_name',
                '0',
                '0000-00-00 00:00:00',
                null
        ));
        
        if ($sql){
            ?>
            <p class="ahmeti_ok"><?php echo _e('The Group has successfully added.','ahmeti-wp-timeline'); ?></p>
            <?php
        }else{
            ?>
            <p class="ahmeti_hata"><?php echo _e('An error occurred while adding group.','ahmeti-wp-timeline'); ?></p>
            <?php
        }      
    }
}
?>