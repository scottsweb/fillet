(function ($) {

	"use strict";

	// fillet iframe auto resizing.  Must go in footer.

	function fillet() {
		var $container = $('article'),								// restrict to actual content
		$iframes = $container.find('.fillet-embed').find('iframe');	// only iframes added using fillet plugin

		$iframes.each(function () {

			if ($(this).data('aspectRatio') === undefined) {

				$(this).parents('figure')
					.data('aspectRatio', this.height / this.width)
					.data('originalWidth', this.width)
					.data('originalHeight', this.height)
					.css('padding-bottom', 100 * (this.height / this.width) + '%' );

				// remove the hard coded width/height
				$(this).removeAttr('height').removeAttr('width');

			}
		});
	}

	fillet();

	// infinite scroll support
	$(document).on("infinite-scrolled", function(ev) {
		fillet();
	});
}(jQuery));