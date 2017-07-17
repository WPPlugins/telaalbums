<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 

/**
* Delete Telaaedifex's Albums options in database
*/
 
delete_option("telaalbums_access_token");
delete_option("telaalbums_add_widget");
delete_option("telaalbums_albpage_desc");
delete_option("telaalbums_album_details");
delete_option("telaalbums_album_thumbsize");
delete_option("telaalbums_albums_per_page");
delete_option("telaalbums_allow_slideshow");
delete_option("telaalbums_cache_thumbs"); //Remove after a few versions
delete_option("telaalbums_caption_length");
delete_option("telaalbums_check_for_updates");
delete_option("telaalbums_client_id");
delete_option("telaalbums_client_secret");
delete_option("telaalbums_comments_widget_title");
delete_option("telaalbums_crop_thumbs");
		
		
delete_option("telaalbums_css_album_grayscale");
delete_option("telaalbums_css_album_shadow");
delete_option("telaalbums_css_album_turn");
delete_option("telaalbums_css_album_rotate");

delete_option("telaalbums_css_photo_grayscale");
delete_option("telaalbums_css_photo_shadow");
delete_option("telaalbums_css_photo_turn");
delete_option("telaalbums_css_photo_rotate");
		
		
delete_option("telaalbums_date_format");
delete_option("telaalbums_description_length");
delete_option("telaalbums_developer_mode");
delete_option("telaalbums_oauth_token");
delete_option("telaalbums_hide_video");
delete_option("telaalbums_image_size");
delete_option("telaalbums_images_on_front");
delete_option("telaalbums_images_per_page");
delete_option("telaalbums_jq_pagination");
delete_option("telaalbums_language");
delete_option("telaalbums_site_language");
delete_option("telaalbums_main_photo"); //Remove
delete_option("telaalbums_main_photo_page");
delete_option("telaalbums_oauth_token");
delete_option("telaalbums_permit_download");
delete_option("telaalbums_photo_widget_title");
delete_option("telaalbums_google_username");
delete_option("telaalbums_public_only"); //Remove
delete_option("telaalbums_public_albums_only");
delete_option("telaalbums_refresh_token");
delete_option("telaalbums_require_filter");
delete_option("telaalbums_show_album_animations"); //Can remove after a few versions
delete_option("telaalbums_show_button");
delete_option("telaalbums_show_caption");
delete_option("telaalbums_show_comments"); //Remove
delete_option("telaalbums_show_dropbox"); //REmove
delete_option("telaalbums_show_footer");
delete_option("telaalbums_show_n_albums");
delete_option("telaalbums_thumbnail_size");
delete_option("telaalbums_token_expires");
delete_option("telaalbums_truncate_names");
delete_option("telaalbums_updates"); //Can remove after a few versions
delete_option("telaalbums_version");
delete_option("telaalbums_which_jq");
delete_option("telaalbums_widget_album_name");
delete_option("telaalbums_widget_comments");
delete_option("telaalbums_widget_num_random_photos");
delete_option("telaalbums_widget_size");
delete_option("telaalbums_widget");





//$option_name = 'wporg_option';
//delete_option($option_name);
// for site options in Multisite
//delete_site_option($option_name);
// drop a custom database table
//global $wpdb;
//$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable");
?>