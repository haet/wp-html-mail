to get all dependencies:
npn install 

during development:
npm run dev 
Enable SCRIPT_DEBUG for your DEV site in wp-config.php
define('SCRIPT_DEBUG',true);



to build for production
cd src; npm run build; cd ..; grunt copy:free_main; grunt copy:free_trunk


Build webfonts add-on for production
cd wp-html-mail-webfonts; npm run build; cd ..; grunt release_webfonts