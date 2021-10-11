jQuery(document).ready(function() {
    
    // datepicker eklentisi varsa fonksiyonu çalıştır...
    if ( jQuery().datepicker ) {
        
        jQuery(".ahmetiDate").datepicker({
            dateFormat : "yy-mm-dd",
            showOn: "both",
            buttonImageOnly: true,
            buttonImage: AhmetiWpTimelineJsData.pluginUrl+'images/calendar.gif',
            buttonText: "Calendar",
            onSelect: function(dateText, inst) {
                var date_Array=dateText.split("-");

                console.log(date_Array);

                jQuery(".ahmetiDateYear").val(date_Array[0]);
                jQuery(".ahmetiDateMonth").val(date_Array[1]);
                jQuery(".ahmetiDateDay").val(date_Array[2]);
                jQuery("#event_bc_input").val("");
            }
        });
    }
    
    
    jQuery("#event_bc_input").keyup(function() {
            jQuery(".ahmetiDateYear").val("");
            jQuery(".ahmetiDateMonth").val("");
            jQuery(".ahmetiDateDay").val("");
    });
    
    
    jQuery('#GorupIDFilter').change(function(){
        window.location = AhmetiWpTimelineJsData.pluginAdminUrl+'&islem=EventList&group_id='+jQuery('#GorupIDFilter').val();
    });

});