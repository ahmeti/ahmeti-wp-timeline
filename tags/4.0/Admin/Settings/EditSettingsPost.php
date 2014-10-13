<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_POST)){

    $sort=trim(stripslashes($_POST['DefaultSort']));
    $state=trim(stripslashes($_POST['StartState']));
    $pagelimit=(int)$_POST['PageLimit'];
    
        
    $options=array(
        'DefaultSort' => $sort,
        'StartState' => $state,
        'PageLimit' => $pagelimit
    );

    if (update_option('AhmetiWpTimelineOptions', json_encode($options))){
        ?>
        <p class="ahmeti_ok"><?php echo _e('Settings were successfully updated.','ahmeti-wp-timeline'); ?></p>
        <?php
    }else{
        ?>
        <p class="ahmeti_hata"><?php echo _e('An error occurred while updating settings.','ahmeti-wp-timeline'); ?></p>
        <?php
    }                

}
?>