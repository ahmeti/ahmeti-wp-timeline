<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_POST)){

    $id=mysql_real_escape_string(trim(stripslashes($_POST['group_id'])));
    $title=mysql_real_escape_string(trim(stripslashes($_POST['group_name'])));

    if( empty($title) || empty($id) ){

        echo '<p class="ahmeti_hata">Boş alan bırakmayınız.</p>';

    }else{

        $sql=mysql_query("UPDATE wp_ahmeti_wp_timeline SET title='$title' WHERE group_id='$id' AND type='group_name' ");

        if ($sql){
            echo '<p class="ahmeti_ok">Grup başarıyla güncellendi.</p>';
        }else{
            echo '<p class="ahmeti_hata">Grup güncellenirken bir hata oluştu.</p>';
        }                
    }
}
?>