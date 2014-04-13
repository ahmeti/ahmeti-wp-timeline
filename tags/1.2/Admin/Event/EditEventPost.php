<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_POST)){

    $event_id=mysql_real_escape_string(trim(stripslashes($_POST['event_id'])));
    $group_id=mysql_real_escape_string(trim(stripslashes($_POST['group_id'])));
    $event_title=mysql_real_escape_string(trim(stripslashes($_POST['event_title'])));
    $event_date=mysql_real_escape_string(trim(stripslashes($_POST['event_date'])));  // 0000-00-00
    $event_time=mysql_real_escape_string(trim(stripslashes($_POST['event_time'])));  // 00:00:00
    $event_bc=(int)mysql_real_escape_string(trim(stripslashes($_POST['event_bc'])));  // 10000
    $event_content=mysql_real_escape_string(trim(stripslashes($_POST['event_content'])));
    
    
    if (preg_match( '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $event_date)){
        //it's ok 
        $event_date_is=true;
    }
    
    if(preg_match('/^(([0-1][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$/', $event_time)){ 
        //it's ok 
        $event_time_is=true;
    }
    
    if ($event_bc > 0){
        //it's ok 
        $event_bc_is=true;
    }
    
    
    if(empty($event_id) || empty($event_title) || empty($group_id) ){
        echo '<p class="ahmeti_hata">Boş alan bırakmayınız.</p>';
        
    }elseif($event_date_is == true && $event_bc_is==true){
        echo '<p class="ahmeti_hata">Hem milattan önce hem de milattan sonraya değer girmişsiniz. Sadece birisine girerek tekrar deneyiniz.</p>';

    }elseif($event_date_is == false && $event_bc_is==false){
        echo '<p class="ahmeti_hata">Milattan önceye veya milattan sonraya değer giriniz.</p>';
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
        
        $sql=mysql_query('UPDATE '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline SET 
            group_id="'.$group_id.'",
            timeline_bc="'.$sql_bc_colon.'",
            timeline_date="'.$sql_datetime_colon.'",
            title="'.$event_title.'",
            event_content="'.$event_content.'",
            type="event" WHERE event_id="'.$event_id.'"');

        if ($sql){
            echo '<p class="ahmeti_ok">Olay başarıyla güncellendi.</p>';
        }else{
            echo '<p class="ahmeti_hata">Olay güncellenirken hata oluştu.</p>';
        }                
    }
}
?>