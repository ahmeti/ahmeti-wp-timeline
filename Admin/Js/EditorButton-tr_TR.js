// closure to avoid namespace collision
(function(){
    

    
	// creates the plugin
	tinymce.create('tinymce.plugins.mygallery', {
		// creates control instances based on the control's id.
		// our button's id is "mygallery_button"
		createControl : function(id, controlManager) {
			if (id === 'mygallery_button') {
				// creates the button
				var button = controlManager.createButton('mygallery_button', {
					title : 'Timeline (Zaman Çizelgesi)', // title of the button
					image : AhmetiWpTimelineJsData.pluginUrl+'images/ahmeti-wp-timeline-button.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 400 < width ) ? 400 : width;
						W = W - 80;
						H = H - 500;
						tb_show( 'Timeline (Zaman Çizelgesi)', '#TB_inline?width='+W+'&height='+H+'&inlineId=mygallery-form' );
                                                
                                                jQuery('#TB_window').css('width',W+'px').css('height',H+'px');
					}
				});
				return button;
			}
			return null;
		}
	});
	
    	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('mygallery', tinymce.plugins.mygallery);
        
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="mygallery-form"><table id="mygallery-table" class="form-table">\
			<tr>\
				<td><br />Timeline (Zaman Çizelgesi) Grup_ID<br /><br /><small>Grup_ID değerine Admin Paneli -> Timeline -> Grup Listesinden ulaşabilirsiniz.</small></td>\
			</tr>\
			<tr>\
				<td>Grup ID: <input type="text" id="mygallery-groupid" size="10" name="groupid" value="" /><br />\
				<!--<small>specify the number of columns.</small></td>-->\
			</tr>\
			<tr>\
				<td>Sıralama: <select id="mygallery-sort" name="sort"><option value="ASC">A-Z</option><option value="DESC">Z-A</option></select><br />\
				<!--<small>specify the number of columns.</small></td>-->\
			</tr>\
			<tr>\
				<td>Durum: <select id="mygallery-state" name="state"><option value="close">Tek Tek Açılsın</option><option value="open">Hepsi Açık Olsun</option></select><br />\
				<!--<small>specify the number of columns.</small></td>-->\
			</tr>\
			<tr>\
				<td>\
                                    <p class="submit">\
                                            <input type="button" id="mygallery-submit" class="button-primary" value="Timeline (Zaman Çizelgesi) Ekle" name="submit" />\
                                    </p>\
                                </td>-->\
			</tr>\
		</table>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#mygallery-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var options = { 
				'groupid'    : '',
                                'sort'    : '',
                                'state'    : ''
				};
			var shortcode = '[ahmetiwptimeline';
			
			for( var index in options) {
				var value = table.find('#mygallery-' + index).val();
				
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})();