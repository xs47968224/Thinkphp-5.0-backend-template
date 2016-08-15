/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.format_tags = 'p;h1;h2;h3;pre';
	config.entities = false;
	config.htmlEncodeOutput = false;
	config.extraPlugins = 'codesnippet';
};
