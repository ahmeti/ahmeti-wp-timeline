(function() {
    tinymce.PluginManager.add('ahmeti_wp_timeline_button', function(editor, url) {
        editor.addButton('ahmeti_wp_timeline_button', {
            text: false,
            icon: 'ahmeti-timeline-mce-icon',
            onclick: function() {
                editor.windowManager.open({
                    title: 'Ahmeti Wp Timeline',
                    body: [
                        {
                            type: 'textbox',
                            name: 'timelineId',
                            label: 'Timeline ID',
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
                        editor.insertContent('[ahmetiwptimeline groupid="' + e.data.timelineId + '" sort="'+e.data.sort+'" state="'+e.data.state+'"]');
                    }
                });
            }
        });
    });
})();
