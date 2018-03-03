if (typeof CKEDITOR !== 'undefined') {


 /*   CKEDITOR.plugins.add( 'toesslabevents', {
        icons: 'abbr',
        init: function ()
        {
            this.opts.keyupCallback = function(e) {
                if (this.$textarea.val() !== window.input_contents[this.$textarea.attr('name')]) {
                    jQuery('#preview_go_newsletter').hide();
                } else {
                    jQuery('#preview_go_newsletter').show();
                }
            };
            window.redactorInstances.push(this);
        }
    });
*/
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.plugins.addExternal(
            'googlefontfamily',
            'vendor/plugins/googlefontfamily/'
        );
    }

}
