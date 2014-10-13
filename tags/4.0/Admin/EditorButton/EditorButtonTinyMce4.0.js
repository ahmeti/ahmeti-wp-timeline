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
                        },
                        {
                            type: 'listbox',
                            name: 'sort',
                            label: 'Sort',
                            'values': [
                                    {text: 'ASC', value: 'ASC'},
                                    {text: 'DESC', value: 'DESC'}
                            ]
                        },
                        {
                            type: 'listbox',
                            name: 'state',
                            label: 'State',
                            'values': [
                                    {text: 'Collapse All', value: 'close'},
                                    {text: 'Expand All', value: 'open'}
                            ]
                        }
                    ],
                    onsubmit: function(e) {
                        editor.insertContent('[ahmetiwptimeline groupid="' + e.data.textboxName + '" sort="'+e.data.sort+'" state="'+e.data.state+'"]');
                    }
                });
            }
        });
    });
})();