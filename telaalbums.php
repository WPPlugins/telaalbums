<?php
/*
Plugin Name: 	Telaaedifex's Albums: Google Photo Albums for Wordpress
Plugin URI: 	https://telaaedifex.com/albums/
Description:	A Google Photos plugin for Wordpress, Telaaedifex's Albums, allows you to display public and private (unlisted) albums on your site in your language!
Author: 	Isaac Brown
Version: 1.3.4
Author URI: 	https://telaaedifex.com
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action("wp_head", "telaalbums_Headers", "9999");
function telaalbums_Headers(){
	TELAALBUMS_CSS();
}



require_once dirname(__FILE__) . '/globals.php';
/*
* Requirements
*/

if  (!(in_array  ("curl", get_loaded_extensions()))) {
	echo "<p><strong>ERROR:</strong> Telaaedifex's Albums requires cURL and it is not enabled on your webserver.  Contact your hosting provider to enable cURL support.</p>";
	echo "<p><i>More info is available on the <a>Telaaedifex's Albums Wiki page on why curl is needed.</a>.</p>";
	exit;
}



/*
* Includes
*/
$TELAALBUMS_DEVELOPER_MODE = get_option("telaalbums_developer_mode");
add_action("wp_head", "teladev", "9999");function teladev(){if(file_exists(dirname(__FILE__)."/developer.php") && $TELAALBUMS_DEVELOPER_MODE = 1){require_once dirname(__FILE__)."/developer.php";}}
require_once dirname(__FILE__) . "/globals.php";
require_once dirname(__FILE__) . "/includes/telaalbums_functions.php";
require_once dirname(__FILE__) . "/includes/telaalbums_options.php";



require_once dirname(__FILE__) . "/menu/access.php";
require_once dirname(__FILE__) . "/menu/advanced.php";
require_once dirname(__FILE__) . "/menu/albums_and_photos.php";
require_once dirname(__FILE__) . "/menu/css.php";
require_once dirname(__FILE__) . "/menu/page.php";
//require_once dirname(__FILE__) . "/menu/slideshow.php";
require_once dirname(__FILE__) . "/menu/telaalbums.php";



require_once dirname(__FILE__) . "/dumpAlbumList.php";
require_once dirname(__FILE__) . "/embedAlbumContents.php";
require_once dirname(__FILE__) . "/shortcodes.php";
require_once dirname(__FILE__) . "/showAlbumContents.php";
require_once dirname(__FILE__) . "/Slideshow.php";



//require_once dirname(__FILE__) . "/widgets.php";



/*
* Add Menu
*/

add_action( "admin_menu", "telaalbums_add_menu" );


function telaalbums_add_menu() { 

	add_menu_page( "Telaaedifex's Albums", "Telaaedifex's Albums", "manage_options", "telaalbums", "telaalbums_main_page" );
	add_submenu_page( "telaalbums", "Telaaedifex's Albums Access Settings", "Access Settings", "manage_options", "telaalbumsaccess", "telaalbums_access_settings_page" );
	add_submenu_page( "telaalbums", "Telaaedifex's Albums Advanced Settings", "Advanced Settings", "manage_options", "telaalbumsadvanced", "telaalbums_advanced_settings_page" );
	add_submenu_page( "telaalbums", "Telaaedifex's Albums Albums and Photos Settings", "Albums/Photos Settings", "manage_options", "telaalbumsalbumsandphotos", "telaalbums_albums_and_photos_settings_page" );
	add_submenu_page( "telaalbums", "Telaaedifex's Albums CSS Settings", "CSS Settings", "manage_options", "telaalbumscss", "telaalbums_css_settings_page" );
	add_submenu_page( "telaalbums", "Telaaedifex's Albums Page Settings", "Page Settings", "manage_options", "telaalbumspage", "telaalbums_page_settings_page" );
	//add_submenu_page( "telaalbums", "Telaaedifex's Albums Slideshow Settings", "Slideshow Settings", "manage_options", "telaalbumsslideshow", "telaalbums_slideshow_settings_page" );


}

/**
 * Add Settings link to plugins - code from GD Star Ratings
 */
add_filter('plugin_action_links', 'telaalbums_add_settings_link', 10, 2 );
function telaalbums_add_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if ($file == $this_plugin){
		$settings_link = '<a href="admin.php?page=telaalbums">'.__("Settings", "telaalbums").'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}

/**
* Add shortcode for embedding the albums in pages 
*/
add_shortcode("telaalbums", "telaalbums_shortcode");
add_filter('widget_text', 'do_shortcode');
register_activation_hook( __FILE__, 'TELAALBUMS_Activate' );


?>