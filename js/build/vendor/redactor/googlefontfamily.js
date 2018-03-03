/**
 * Created by https://toesslab.ch/
 * User: Daenu
 * Date: 11/18/15
 * Time: 1:26 AM
 * Project: toesslab - Newsletter
 * Description: Send Newsletters to registered users
 * File: /packages/toess_lab_news_letter/js/build/vendor/redactor/googlefontfamily.js
 */

if (!RedactorPlugins) {
	var RedactorPlugins = {};
}

(function($)
{
	RedactorPlugins.googlefontfamily = function()
	{
		return {
			init: function ()
			{
				var fonts = window.font_family;
				var that = this;
				var dropdown = {};

				$.each(fonts, function(i, s)
				{
					dropdown['s' + i] = { title: s, func: function() { that.googlefontfamily.set(s); }};
				});

				dropdown.remove = { title: 'Remove Font Family', func: that.googlefontfamily.reset };

				var button = this.button.add('fontfamily', 'Change Font Family');
				/* concrete5 */
				this.button.setAwesome('fontfamily', 'fa fa-font');
				/* end concrete5 */
				this.button.addDropdown(button, dropdown);
                this.opts.keyupCallback = function(e) {
                    jQuery('#preview_go_newsletter').hide();
                };
            },
			set: function (value)
			{
				this.inline.format('span', 'style', 'font-family:' + value + ';');
			},
			reset: function()
			{
				this.inline.removeStyleRule('font-family');
			}
        };
	};
})(jQuery);