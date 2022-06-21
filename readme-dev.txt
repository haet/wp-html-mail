to get all dependencies:
npn install 

during development:
go to template-designer/ folder
npm start 
Enable SCRIPT_DEBUG for your DEV site in wp-config.php
define('SCRIPT_DEBUG',true);



to build for production
cd src; npm run build; cd ..; grunt copy:free_main; grunt copy:free_trunk
the only file of the template designer needed for production is js/template-designer/dist/main.js
but we also add js/template-designer/dev/main.js in case someone has SCRIPT_DEBUG enabled

Build webfonts add-on for production
cd wp-html-mail-webfonts; npm run build; cd ..; grunt release_webfonts