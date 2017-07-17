<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

		//$CACHE_THUMBNAILS       = get_option("telaalbums_cache_thumbs");
		//$SHOW_ALBUM_ANIMATIONS  = get_option("telaalbums_show_album_animations","1"); //Isaac
		//$SHOW_BUTTON            = get_option("telaalbums_show_button"); // Rob
		//$SHOW_COMMENTS          = get_option("telaalbums_show_comments");
		//$SHOW_DROP_BOX          = get_option("telaalbums_show_dropbox");
		//$THIS_VERSION           = get_option("telaalbums_version");
		$TELAALBUMS_ADD_WIDGET             = get_option("telaalbums_add_widget"); // Rob
		$TELAALBUMS_ALBUM_THUMBSIZE      	= get_option("telaalbums_album_thumbsize");
		$TELAALBUMS_ALBUMS_PER_PAGE		= get_option("telaalbums_albums_per_page");
		$TELAALBUMS_ALLOW_SLIDESHOW        = get_option("telaalbums_allow_slideshow");
		$TELAALBUMS_CAPTION_LENGTH         = get_option("telaalbums_caption_length");
		$TELAALBUMS_CHECK_FOR_UPDATES  	= get_option("telaalbums_check_for_updates");
		$TELAALBUMS_clientId				= get_option("telaalbums_client_id");
		$TELAALBUMS_clientSecret			= get_option("telaalbums_client_secret");
		$TELAALBUMS_CROP_THUMBNAILS		= get_option("telaalbums_crop_thumbs");
		$TELAALBUMS_DATE_FORMAT			= get_option("telaalbums_date_format");
		$TELAALBUMS_DEBUG 					= 0;
		$TELAALBUMS_DESC_ON_ALBUM_PAGE     = get_option("telaalbums_albpage_desc");
		$TELAALBUMS_DESCRIPTION_LENGTH     = get_option("telaalbums_description_length");
		$TELAALBUMS_DEVELOPER_MODE  		= get_option("telaalbums_developer_mode"); //Isaac
		$TELAALBUMS_GALLERY_THUMBSIZE      = get_option("telaalbums_thumbnail_size");
		$TELAALBUMS_OAUTH_TOKEN            = get_option("telaalbums_oauth_token");
		$TELAALBUMS_HIDE_VIDEO             = get_option("telaalbums_hide_video");
		$TELAALBUMS_IMAGES_ON_FRONT        = get_option("telaalbums_images_on_front");   // Rob
		$TELAALBUMS_IMAGES_PER_PAGE        = get_option("telaalbums_images_per_page");
		$TELAALBUMS_IMG_MAX                 = get_option("telaalbums_image_size");
		$TELAALBUMS_JQ_PAGINATION_STYLE    = get_option("telaalbums_jq_pagination");
		$TELAALBUMS_MAIN_PHOTO_PAGE        = get_option("telaalbums_main_photo_page");
		$TELAALBUMS_now					= date("U");
		
		$TELAALBUMS_PERMIT_IMG_DOWNLOAD  	= get_option("telaalbums_permit_download");
		$TELAALBUMS_GOOGLE_USER	 		= get_option("telaalbums_google_username");
		$TELAALBUMS_PUBLIC_ONLY 	 		= get_option("telaalbums_public_only");
		
		
		$TELAALBUMS_CSS_ALBUM_GRAYSCALE = get_option("telaalbums_css_album_grayscale");
		$TELAALBUMS_CSS_ALBUM_ROTATE = get_option("telaalbums_css_album_rotate");
		$TELAALBUMS_CSS_ALBUM_SHADOW = get_option("telaalbums_css_album_shadow");
		
		$TELAALBUMS_CSS_PHOTO_GRAYSCALE = get_option("telaalbums_css_photo_grayscale");
		$TELAALBUMS_CSS_PHOTO_ROTATE = get_option("telaalbums_css_photo_rotate");
		$TELAALBUMS_CSS_PHOTO_SHADOW = get_option("telaalbums_css_photo_shadow");
		
		
		$TELAALBUMS_VERSION		 = get_option("telaalbums_version");
		$TELAALBUMS_refreshToken			= get_option("telaalbums_refresh_token");
		$TELAALBUMS_REQUIRE_FILTER  		= get_option("telaalbums_require_filter");
		$TELAALBUMS_SHOW_ALBUM_DETAILS  	= get_option("telaalbums_album_details");
		$TELAALBUMS_SHOW_FOOTER            = get_option("telaalbums_show_footer");
		$TELAALBUMS_SHOW_IMG_CAPTION		= get_option("telaalbums_show_caption");
		$TELAALBUMS_SHOW_N_ALBUMS          = get_option("telaalbums_show_n_albums");
		$TELAALBUMS_SITE_LANGUAGE          = get_option("telaalbums_language");
		$TELAALBUMS_STANDALONE_MODE		= "1";
		$TELAALBUMS_THUMBSIZE              = get_option("telaalbums_thumbnail_size");
		$TELAALBUMS_TOKEN_EXPIRES			= get_option("telaalbums_token_expires");
		$TELAALBUMS_TRUNCATE_ALBUM_NAME  	= get_option("telaalbums_truncate_names");
		$TELAALBUMS_WHICH_JQ               = get_option("telaalbums_which_jq");

//global $getalloptions;		
//$getalloptions = getalloptions();
//function getalloptions(){
//		get_option("telaalbums_add_widget"); // Rob
//		get_option("telaalbums_album_thumbsize");
//		get_option("telaalbums_albums_per_page");
//		get_option("telaalbums_allow_slideshow");
//		get_option("telaalbums_caption_length");
//		get_option("telaalbums_check_for_updates");
//		get_option("telaalbums_client_id");
//		get_option("telaalbums_client_secret");
//		get_option("telaalbums_crop_thumbs");
//		get_option("telaalbums_date_format");
//
//		get_option("telaalbums_albpage_desc");
//		get_option("telaalbums_description_length");
//		get_option("telaalbums_developer_mode"); //Isaac
//		get_option("telaalbums_thumbnail_size");
//		get_option("telaalbums_oauth_token");
//		get_option("telaalbums_hide_video");
//		get_option("telaalbums_images_on_front");   // Rob
//		get_option("telaalbums_images_per_page");
//		get_option("telaalbums_image_size");
//		get_option("telaalbums_jq_pagination");
//		get_option("telaalbums_main_photo_page");
//	
//		get_option("telaalbums_permit_download");
//		get_option("telaalbums_google_username");
//		get_option("telaalbums_public_only");
//		
//		
//		get_option("telaalbums_css_album_grayscale");
//		get_option("telaalbums_css_album_rotate");
//		get_option("telaalbums_css_album_shadow");
//		
//		get_option("telaalbums_css_photo_grayscale");
//		get_option("telaalbums_css_photo_rotate");
//		get_option("telaalbums_css_photo_shadow");
//		
//		
//		get_option("telaalbums_version");
//		get_option("telaalbums_refresh_token");
//		get_option("telaalbums_require_filter");
//		get_option("telaalbums_album_details");
//		get_option("telaalbums_show_footer");
//		get_option("telaalbums_show_caption");
//		get_option("telaalbums_show_n_albums");
//		get_option("telaalbums_language");
//	
//		get_option("telaalbums_thumbnail_size");
//		get_option("telaalbums_token_expires");
//		get_option("telaalbums_truncate_names");
//		get_option("telaalbums_which_jq");
//}
			
?>