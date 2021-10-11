<?php
/*
    Plugin Name: Ahmeti Wp Timeline
    Text Domain: ahmeti-wp-timeline
    Plugin URI: http://ahmeti.net/
    Description: A nice plugin that allows you to make a timeline about anything. Herhangi bir konu hakkında bir timeline oluşarabilirsiniz.
    Author: Ahmet Imamoglu
    Version: 5.1
    Author URI: http://ahmeti.net/
    License: GPLv2 or later (license.txt)
*/

/*
 *  Copyright 2014 Ahmet İmamoğlu ( ahmet@ahmeti.net )
 *
 *  LICENSE:
 * 
 *  Everyone is permitted to copy and distribute verbatim copies
 *  of this license document, but changing it is not allowed.
 * 
 */

define('AHMETI_WP_TIMELINE_KONTROL',true);
define('AHMETI_WP_TIMELINE_ADMIN_URL', admin_url().'admin.php?page=ahmeti-wp-timeline/index.php');

global $wpdb;
define('AHMETI_WP_TIMELINE_DB_PREFIX',$wpdb->prefix);

require_once 'AhmetiWpTimelineFunction.php';

//  OPTION LIST
// $ahmetiWpTimelineOpt->DefaultSort = ASC
// $ahmetiWpTimelineOpt->StartState = open
// $ahmetiWpTimelineOpt->PageLimit = 20
 

if ( isset($_GET['activate']) && @$_GET['activate'] == 'true' )
{
    // Eğer kullanıcı "Etkinleştir" bağlantısına tıkladıysa, fonksiyonunu çağır
    /* Kurulum */
    add_action('init', 'Ahmeti_Wp_Timeline_Kurulum');
}



if (!is_admin()) {
    
    // Wp User Head
    add_action('wp_enqueue_scripts', 'Ahmeti_Wp_Timeline_Head');
    add_shortcode( 'ahmetiwptimeline', 'AhmetiWpTimelineShortCodeOutput' );
    
}else{

    // Admin Panel - Yonetim Paneli Olustur
    add_action('admin_menu', 'Ahmeti_Wp_Timeline_Admin');
    
    // Add Editor Button Short Code
    new AhmetiWpTimelineAddEditorButton();
} 


function Ahmeti_Wp_Timeline_Index(){   //ahmeti_index
    
    require_once 'header.php';

    
    /*      A C T I O N S     */
    
    
    /* Group Actions */
    if      (@$_GET['islem']=='NewGroupForm'){
        require_once 'Admin/Group/NewGroupForm.php';
        
    }elseif (@$_GET['islem']=='NewGroupPost'){
        require_once 'Admin/Group/NewGroupPost.php';
        
    }elseif (@$_GET['islem']=='EditGroupForm'){
        require_once 'Admin/Group/EditGroupForm.php';
        
    }elseif (@$_GET['islem']=='EditGroupPost'){
        require_once 'Admin/Group/EditGroupPost.php';
        
    }elseif (@$_GET['islem']=='DeleteGroupPost'){
        require_once 'Admin/Group/DeleteGroupPost.php';        

        
        
    /* Event Actions */
    }elseif (@$_GET['islem'] == 'EventList'){
        require_once 'Admin/Event/EventList.php';
        
    }elseif (@$_GET['islem']=='NewEventForm'){
        require_once 'Admin/Event/NewEventForm.php';
            
    }elseif (@$_GET['islem']=='NewEventPost'){
        require_once 'Admin/Event/NewEventPost.php';

    }elseif (@$_GET['islem']=='EditEventForm'){
        require_once 'Admin/Event/EditEventForm.php';
        
    }elseif (@$_GET['islem']=='EditEventPost'){
        require_once 'Admin/Event/EditEventPost.php';
        
    }elseif (@$_GET['islem']=='DeleteEventPost'){
        require_once 'Admin/Event/DeleteEventPost.php';
    
        
        
    /* Settings Action*/
    }elseif (@$_GET['islem']=='EditSettingsForm'){
        require_once 'Admin/Settings/EditSettingsForm.php';
    }elseif (@$_GET['islem']=='EditSettingsPost'){
        require_once 'Admin/Settings/EditSettingsPost.php';
        
        
    /* Anasyafa */
    }else{
        require_once 'Admin/Group/GroupList.php';
    }

    
    require_once 'footer.php';
}
?>