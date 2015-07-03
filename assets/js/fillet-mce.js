(function() {
	tinymce.PluginManager.add('fillet', function( editor, url ) {
		editor.addButton( 'fillet', {
			icon: false,
			type: 'button',
			title: 'iFrame Embed',
			onclick: function() {
				editor.windowManager.open( {
					title: 'iFrame Embed',
					width: 600,
					height: 150,
					body: [
						{
							type: 'textbox',
							name: 'url',
							label: 'iFrame URL',
							value: 'http://',
							autofocus: true
						},
						{
							type: 'textbox',
							name: 'width',
							label: 'Width (px)',
							value: '700',
							size: 4,
							maxLength: 4
						},
						{
							type: 'textbox',
							name: 'height',
							label: 'Height (px)',
							value: '300',
							size: 4,
							maxLength: 4
						}
					],
					onsubmit: function( e ) {

						var shortcode = '<p>[iframe';

						// iframe url
						if (e.data.url != '') {
							shortcode += ' url="' + e.data.url + '"';
						}

						// width
						if (e.data.width != '') {
							shortcode += ' width="' + e.data.width + '"';
						}

						// width
						if (e.data.height != '') {
							shortcode += ' height="' + e.data.height + '"';
						}

						shortcode += ']</p>';
						editor.insertContent( shortcode );
					}
				});
			}
		});
	});
})();