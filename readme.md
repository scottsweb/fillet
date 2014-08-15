# Fillet

Fillet adds a button to the toolbar in the WordPress visual editor.  You can still insert any iframe, but by using a [shortcode](http://codex.wordpress.org/Shortcode), rather than copying and pasting HTML, it's safer (and future proof.)

# Auto iframe resizing

We use javascript to scale down iframes to fit on tablet/mobile devices, whilst maintaining the correct aspect ratio.

- Requires: jQuery (included in WordPress) and [jQuery.doTimeout](https://github.com/cowboy/jquery-dotimeout) (included with Fillet)
- Browser compatibility: all modern standards-compliant browsers, IE9 and up.
- All javascript is added in the footer for performance.
- Requires posts to use an `<article>` tag (standard semantic HTML).
- We look for **all** iframes on the page.
- Fillet's PHP wraps `<iframe>`s in a `<figure>` tag. Our javascript looks at that to determine the available width, so check for margins on the `<figure>` if you have unwanted white space.
- Auto resizing is (not yet) compatible with infinite scroll.
- Tested with [TwentyFourteen](http://wordpress.org/themes/twentyfourteen) and [Bootiful](https://github.com/cftp/bootiful) themes.