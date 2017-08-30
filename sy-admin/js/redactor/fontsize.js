if (!RedactorPlugins) var RedactorPlugins = {};

(function($)
{
	RedactorPlugins.fontsize = function()
	{
		return {
			init: function()
			{
				var fonts = [10, 11, 12, 14, 16, 18, 20, 24, 28, 30, 32, 34, 36,38,40,42,44,46,48,50,52,54,56,58,60,62,64,66,68,70,72];
				var that = this;
				var dropdown = {};

				$.each(fonts, function(i, s)
				{
					dropdown['s' + i] = { title: s + 'px', func: function() { that.fontsize.set(s); } };
				});

				dropdown.remove = { title: 'Remove Font Size', func: that.fontsize.reset };

				var button = this.button.add('fontsize', 'Change Font Size');
				this.button.addDropdown(button, dropdown);
			},
			set: function(size)
			{
				this.inline.format('span', 'style', 'font-size: ' + size + 'px;');
			},
			reset: function()
			{
				this.inline.removeStyleRule('font-size');
			}
		};
	};
})(jQuery);