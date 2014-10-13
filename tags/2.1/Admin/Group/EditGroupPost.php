<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_POST)){

    $id=mysql_real_escape_string(trim(stripslashes($_POST['group_id'])));
    $title=mysql_real_escape_string(trim(stripslashes($_POST['group_name'])));

    if( empty($title) || empty($id) ){
        ?>
        <p class="ahmeti_hata"><?php echo _e('Do not leave empty fields.','ahmeti-wp-timeline'); ?></p>
        <?php
    }else{

        $sql=mysql_query('UPDATE '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline SET title="'.$title.'" WHERE group_id="'.$id.'" AND type="group_name" ');

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