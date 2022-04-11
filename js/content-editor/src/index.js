import domReady from '@wordpress/dom-ready';
import { render } from '@wordpress/element';
import { registerCoreBlocks } from '@wordpress/block-library';
import Editor from '../components/editor';


domReady( function() {
	const settings = window.mailbuilderSettings || {};
	registerCoreBlocks();
	render( <Editor settings={ settings } />, document.getElementById( 'wp-html-mail-content-editor' ) );
} ); 