/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/*
 WARNING: clear browser's cache after you modify this file.
 If you don't do this, you may notice that browser is ignoring all your changes.
 */
CKEDITOR.editorConfig = function(config) {
		// Add custom templates.
		if (typeof(CKEDITOR.addTemplates) !== 'undefined') {
				CKEDITOR.addTemplates('default', {
						templates: [
								{
										title: 'Table with 3 columns',
										// image: 'block/test1.jpg',
										description: 'Loop book table',
										html: '<p>Hmm …</p>'
								}
						]
				});

				;;; console.debug(CKEDITOR.getTemplates('default'));
		}
};
