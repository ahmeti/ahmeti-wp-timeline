<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_POST)){

    global $wpdb;

    
    $event_id=trim(stripslashes($_POST['event_id']));
    $group_id=trim(stripslashes($_POST['group_id']));
    $event_title=trim(stripslashes($_POST['event_title']));
    
    $event_date_year=trim(stripslashes($_POST['event_date_year']));
    $event_date_month=trim(stripslashes($_POST['event_date_month']));
    $event_date_day=trim(stripslashes($_POST['event_date_day']));
    
    if (empty($event_date_year)) {$event_date_year='0000';}
    if (empty($event_date_month)) {$event_date_month='00';}
    if (empty($event_date_day)) {$event_date_day='00';}
    
    
    $event_date=$event_date_year.'-'.$event_date_month.'-'.$event_date_day;
    
    $event_time=trim(stripslashes($_POST['event_time']));  // 00:00:00
    $event_bc=(int)trim(stripslashes($_POST['event_bc']));  // 10000
    $event_content=trim(stripslashes($_POST['event_content']));
    
    
    /*
    if (preg_match( '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $event_date)){
        //it's ok 
        $event_date_is=true;
    }*/

    $event_date_is=false;
    if ($event_date != '0000-00-00' ){
        //it's ok 
        $event_date_is=true;
    }
    
    $event_time_is=false;
    if(preg_match('/^(([0-1][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$/', $event_time)){ 
        //it's ok 
        $event_time_is=true;
    }
    
    $event_bc_is=false;
    if ($event_bc > 0){
        //it's ok 
        $event_bc_is=true;
    }
    
    
    if(empty($event_id) || empty($event_title) || empty($group_id) ){
        ?>
        <p class="ahmeti_hata"><?php echo _e('Do not leave empty fields.','ahmeti-wp-timeline'); ?></p>
        <?php
        
    }elseif($event_date_is == true && $event_bc_is==true){
        ?>
        <p class="ahmeti_hata"><?php echo _e('Both before Christ and Anno Domini value, you entered. Please try again by entering only one.','ahmeti-wp-timeline'); ?></p>
        <?php
        
    }elseif($event_date_is == false && $event_bc_is==false){
        ?>
        <p class="ahmeti_hata"><?php echo _e('Please enter a value in any of the two. (Before Christ or Anno Domini)','ahmeti-wp-timeline'); ?></p>
        <?php
        
    }else{
        
        $sql_bc_colon='';
        $sql_datetime_colon='';
        
        if ($event_date_is==true){ // M.S. Tarih girilmiş
            $sql_datetime_colon=$event_date;
            $sql_bc_colon='0';
            
            if ($event_time_is==true){ // Zaman geçerli
                $sql_datetime_colon.=' '.$event_time;
            }else{
                $sql_datetime_colon.=' 00:00:00';
            }
            
        }elseif($event_bc_is){ // M.Ö. Tarih girilmiş
            $sql_datetime_colon='0000-00-00 00:00:00';
            $sql_bc_colon='-'.$event_bc;
        }

        $sql=$wpdb->update( 
                AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline',
                array( 
                    'group_id' => $group_id,
                    'timeline_bc' => $sql_bc_colon,
                    'timeline_date' => $sql_datetime_colon,
                    'title' => $event_title,
                    'event_content' => $event_content,
                    'type' => 'event',
                ), 
                array( 'event_id' => $event_id ),  // WHERE
                array( 
                    '%d', 
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ), 
                array( '%d' )  // WHERE TYPE
        );

        if ($sql){
            ?>
            <p class="ahmeti_ok"><?php echo _e('Event was successfully updated.','ahmeti-wp-timeline'); ?></p>
            <?php
        }else{
            ?>
            <p class="ahmeti_hata"><?php echo _e('An error occurred while updating this event.','ahmeti-wp-timeline'); ?></p>
            <?php
        }                
    }
}
?>