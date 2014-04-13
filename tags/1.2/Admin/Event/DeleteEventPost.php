<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_GET)){

    (int)$id=mysql_real_escape_string(trim(stripslashes($_GET['event_id'])));

    if( empty($id) ){

        echo '<p class="ahmeti_hata">Bir hata oluştu.</p>';

    }else{

        $sql=mysql_query('DELETE FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline WHERE event_id="'.$id.'" AND type="event" ');

        if ($sql){
            echo '<p class="ahmeti_ok">Olay başarıyla silindi.</p>';
        }else{
            echo '<p class="ahmeti_hata">Olay silinirken hata oluştu.</p>';
        }                
    }
}
?>