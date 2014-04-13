<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
if (!empty($_POST)){

    $title=mysql_real_escape_string(trim(stripslashes($_POST['group_name'])));

    if(empty($title)){

        echo '<p class="ahmeti_hata">Boş alan bırakmayınız.</p>';

    }else{

        @$last_group_id=mysql_fetch_array(mysql_query('SELECT group_id FROM '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline ORDER BY group_id DESC LIMIT 0,1'));
        $group_id=(int)@$last_group_id['group_id'] + 1;
        
        $sql=mysql_query('insert into '.AHMETI_WP_TIMELINE_DB_PREFIX.'ahmeti_wp_timeline (group_id,title,type,timeline_bc,timeline_date,event_content) values ("'.$group_id.'","'.$title.'","group_name","0","0000-00-00 00:00:00",null) ');

        if ($sql){
            echo '<p class="ahmeti_ok">Grup başarıyla eklendi.</p>';
        }else{
            echo '<p class="ahmeti_hata">Grup eklenirken hata oluştu.</p>';
        }                
    }
}
?>