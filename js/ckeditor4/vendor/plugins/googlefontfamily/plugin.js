CKEDITOR.plugins.add( 'googlefontfamily', {
    //icons: this.path + 'plugins/googlefontfamily/images/icon.png',
    init: function( editor ) {
console.log(editor)
        //dropdown.remove = { title: 'Remove Font Family'};

        //editor.addMenuItem('googlefontfamily', dropdown);
        //editor.addCommand('googlefontfamily', new CKEDITOR.dialogCommand('googlefontfamily', {}));
        /*
        editor.ui.addButton('googlefontfamily', {
            label : 'wtf',
            toolbar : 'tools',
            command : 'googlefontfamily',
           // icon : 'plugins/googlefontfamily/images/icon.png'
        });
        */
        CKEDITOR.config = function () {
            config.fonts = window.font_family;
            var dropdown = {};

            $.each(config.fonts, function(i, s)
            {
                config.dropdown['s' + i] = { title: s};
            });

        }
    }
});
