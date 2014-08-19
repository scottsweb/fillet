(function ($) {

    "use strict";

    // cftp/fillet iframe auto resizing.  Must go in footer.

    var $container = $('article'),                                  // restrict to actual content
        $iframes = $container.find('.fillet-embed').find('iframe'), // only iframes added using fillet plugin
        resizeLock = false;                                         // only one instance should run at once

    function iframeSetup() {

        // Calculate and store the aspect ratio for each iframe
        $iframes.each(function () {

            if ($(this).data('aspectRatio') === undefined) {
                // Must only ever set aspect ratio once (iframe will shrink each subsequent time)

                $(this)
                    .data('aspectRatio', this.height / this.width)
                    .data('originalWidth', this.width)
                    .data('originalHeight', this.height)
                    // remove the hard coded width/height
                    .removeAttr('height')
                    .removeAttr('width');
            }

        });

    }

    function resize($objects) {

        if (true === resizeLock) {
            // don't run more than one instance
            return false;
        }

        resizeLock = true;

        // Resize all videos according to their own aspect ratio
        $objects.each(function () {

            var $el = $(this),
                newWidth = $el.parents('figure').width();   // fillet wraps iframes in <figure>; may well have padding.

            if (newWidth >= $el.data('originalWidth')) {
                // don't make it any bigger than it was to begin with, but do reset to original size if there's room
                $el.width($el.data('originalWidth')).height($el.data('originalHeight'));
            } else {
                // scale down according to aspect ratio
                $el.width(newWidth).height(newWidth * $el.data('aspectRatio'));
            }

        });

        resizeLock = false; // indicate we've finished
    }

    // On page load...
    iframeSetup();
    resize($iframes);

    // When window resized / device orientation changes etc.
    $(window).resize(function () {
        // use doTimeout plugin to prevent resize event firing multiple times if window edges dragged
        $.doTimeout('resizing', 250, function () {
            resize($iframes);
        });
    });

}(jQuery));


