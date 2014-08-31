(function() {
    tinymce.PluginManager.add('my_mce_button', function(editor, url) {
        editor.addButton('my_mce_button', {
            text: false,
            icon: 'ahmeti-timeline-mce-icon',
            onclick: function() {
                editor.windowManager.open({
                    title: 'Ahmeti Wp Timeline',
                    body: [
                        {
                            type: 'textbox',
                            name: 'textboxName',
                            label: 'Grup ID',
                            value: ''
                        }


                    ],
                    onsubmit: function(e) {
                        editor.insertContent('[ahmetiwptimeline groupid="' + e.data.textboxName + '"]');
                    }
                });
            }
        });
    });
})();