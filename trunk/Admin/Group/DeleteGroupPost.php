<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_GET)){

    (int)$id=mysql_real_escape_string(trim(stripslashes($_GET['group_id'])));

    if( empty($id) ){

        echo '<p class="ahmeti_hata">Bir hata oluştu.</p>';

    }else{

        $sql=mysql_query("DELETE FROM wp_ahmeti_wp_timeline WHERE group_id='$id' ");

        if ($sql){
            echo '<p class="ahmeti_ok">Grup başarıyla silindi.</p>';
        }else{
            echo '<p class="ahmeti_hata">Grup silinirken hata oluştu.</p>';
        }                
    }
}
?>