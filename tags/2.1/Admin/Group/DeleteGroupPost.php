<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_GET)){

    (int)$id=mysql_real_escape_string(trim(stripslashes($_GET['group_id'])));

    if( empty($id) ){
    ?>
        <p class="ahmeti_hata"><?php echo _e('An error has occurred.','ahmeti-wp-timeline'); ?></p>
    <?php
    }else{

        $sql=mysql_query('DELETE FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE group_id="'.$id.'"');

        if ($sql){
            ?>
            <p class="ahmeti_ok"><?php echo _e('Group deleted successfully.','ahmeti-wp-timeline'); ?></p>
            <?php
        }else{
            ?>
            <p class="ahmeti_hata"><?php echo _e('An error occurred while deleting the group.','ahmeti-wp-timeline'); ?></p>
            <?php
        }                
    }
}
?>