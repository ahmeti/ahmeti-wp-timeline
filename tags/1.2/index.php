<?php
/*
    Plugin Name: Ahmeti Wp Timeline
    Plugin URI: http://ahmeti.net/
    Description: Herhangi bir konu hakkında güzel bir timeline (zaman çizelgesi) oluşarabileceğiniz bir eklenti.
    Author: Ahmet Imamoglu
    Version: 1.3
    Author URI: http://ahmeti.net/
*/

/*
    Copyright 2012 Ahmet İmamoğlu ( ahmet@ahmeti.net )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('AHMETI_WP_TIMELINE_KONTROL',true);
define('AHMETI_WP_TIMELINE_ADMIN_URL', admin_url().'admin.php?page=ahmeti-wp-timeline/index.php');

global $wpdb;
define('AHMETI_WP_TIMELINE_DB_PREFIX',$wpdb->prefix);

require_once 'AhmetiWpTimelineFunction.php';


/*
 *  OPTION LIST
 * 
 *  get_option('AhmetiWpTimelinePageLimit')  // Sayfa listeleme limiti
 * 
 */


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
    /* Wp Admin Head */
    add_action('admin_enqueue_scripts', 'Ahmeti_Wp_Timeline_Admin_Head');
    
    // Admin Panel - Yonetim Paneli Olustur
    add_action('admin_menu', 'Ahmeti_Wp_Timeline_Admin');    
    
    // Add Editor Button Short Code
    new AhmetiWpTimelineAddEditorButton();
} 
 



function Ahmeti_Wp_Timeline_Index(){   //ahmeti_index
   
    require_once 'header.php';

    
    /*      A C T I O N S     */
    
    
    /* Group Actions */
    if      ($_GET['islem']=='NewGroupForm'){
        require_once 'Admin/Group/NewGroupForm.php';
        
    }elseif ($_GET['islem']=='NewGroupPost'){
        require_once 'Admin/Group/NewGroupPost.php';
        
    }elseif ($_GET['islem']=='EditGroupForm'){
        require_once 'Admin/Group/EditGroupForm.php';
        
    }elseif ($_GET['islem']=='EditGroupPost'){
        require_once 'Admin/Group/EditGroupPost.php';
        
    }elseif ($_GET['islem']=='DeleteGroupPost'){
        require_once 'Admin/Group/DeleteGroupPost.php';        

        
        
    /* Event Actions */
    }elseif ($_GET['islem'] == 'EventList'){
        require_once 'Admin/Event/EventList.php';
        
    }elseif ($_GET['islem']=='NewEventForm'){
        require_once 'Admin/Event/NewEventForm.php';
            
    }elseif ($_GET['islem']=='NewEventPost'){
        require_once 'Admin/Event/NewEventPost.php';

    }elseif ($_GET['islem']=='EditEventForm'){
        require_once 'Admin/Event/EditEventForm.php';
        
    }elseif ($_GET['islem']=='EditEventPost'){
        require_once 'Admin/Event/EditEventPost.php';
        
    }elseif ($_GET['islem']=='DeleteEventPost'){
        require_once 'Admin/Event/DeleteEventPost.php';
        

    /* Anasyafa */
    }else{
        require_once 'Admin/Group/GroupList.php';
    }

    
    require_once 'footer.php';
}

?>