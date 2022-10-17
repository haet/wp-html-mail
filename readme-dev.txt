to get all dependencies:
npn install 

during development:
go to template-designer/ folder
npm start 
Enable SCRIPT_DEBUG for your DEV site in wp-config.php
define('SCRIPT_DEBUG',true);



to build for production
npm run build; grunt copy:free_main; grunt copy:free_trunk
the only file of the template designer needed for production is js/template-designer/dist/main.js
but we also add js/template-designer/dev/main.js in case someone has SCRIPT_DEBUG enabled

Build webfonts add-on for production
cd wp-html-mail-webfonts; npm run build; grunt release_webfonts


WordPress Components
List of Components & Documentation:
https://developer.wordpress.org/block-editor/reference-guides/components/
Component Demos:
https://wordpress.github.io/gutenberg/?path=/docs/components-snackbar--with-explicit-dismiss


Embedding React parts from addons
We use the webpack ModuleFederationPlugin which has to be configured in each webpack.config.js
As a starting point we used this post: https://stackoverflow.com/questions/40615038/load-react-js-component-from-external-script-in-run-time/61823689#61823689
