/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/js/build/vendor/redactor/googlefontfamily.js
 */

if (!RedactorPlugins) var RedactorPlugins = {};

(function($)
{
	RedactorPlugins.toesslabevents = function()
	{
		return {
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
        };
	};
})(jQuery);
