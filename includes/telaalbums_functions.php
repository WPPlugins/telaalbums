<?PHP
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $THIS_VERSION;
$THIS_VERSION = "1.3.4";

if (!function_exists("telaalbums_buildDownloadDiv")) {
function telaalbums_buildDownloadDiv($filename,$orig_href,$style="NULL") {
	if ($style == "NULL") {
	                $result  = "<div class='telaalbums_download'>\n";
        } else {
                $result  = "<span style='$style'>\n";
        }

        $result .= "\t<a rel='nobox' 'Save $filename' title='Save $filename' href='$orig_href'>\n";
        $result .= "\t<img border=0 style='padding-left: 5px;' src='" . plugins_url('/attributes/images/disk_bw.png', dirname(__FILE__)) ."' /></a>\n";

        if ($style == "NULL") {
                $result  .= "</div>\n";
        } else {
                $result  .= "</span>\n";
        }
        return($result);
}
}

global $telaalbums_get_all_options;
$telaalbums_get_all_options = telaalbums_get_all_options();
function telaalbums_get_all_options() {
	
		$TELAALBUMS_ADD_WIDGET             = get_option("telaalbums_add_widget"); // Rob
		$TELAALBUMS_ALBUM_THUMBSIZE      	= get_option("telaalbums_album_thumbsize");
		$TELAALBUMS_ALBUMS_PER_PAGE		= get_option("telaalbums_albums_per_page");
		$TELAALBUMS_ALLOW_SLIDESHOW        = get_option("telaalbums_allow_slideshow");
		//$CACHE_THUMBNAILS       = get_option("telaalbums_cache_thumbs");
		$TELAALBUMS_CAPTION_LENGTH         = get_option("telaalbums_caption_length");
		$TELAALBUMS_CHECK_FOR_UPDATES  	= get_option("telaalbums_check_for_updates");
		$TELAALBUMS_clientId				= get_option("telaalbums_client_id");
		$TELAALBUMS_clientSecret			= get_option("telaalbums_client_secret");
		$TELAALBUMS_CROP_THUMBNAILS		= get_option("telaalbums_crop_thumbs");
		$TELAALBUMS_DATE_FORMAT			= get_option("telaalbums_date_format");
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
		$TELAALBUMS_PERMIT_IMG_DOWNLOAD  	= get_option("telaalbums_permit_download");
		$TELAALBUMS_GOOGLE_USER	 		= get_option("telaalbums_google_username");
		$TELAALBUMS_PUBLIC_ONLY 	 		= get_option("telaalbums_public_only");
		
		
		$TELAALBUMS_CSS_ALBUM_GRAYSCALE = get_option("telaalbums_css_album_grayscale");
		$TELAALBUMS_CSS_ALBUM_SHADOW = get_option("telaalbums_css_album_shadow");
		$TELAALBUMS_CSS_ALBUM_ROTATE = get_option("telaalbums_css_album_rotate");
		
		$TELAALBUMS_CSS_PHOTO_GRAYSCALE = get_option("telaalbums_css_photo_grayscale");
		$TELAALBUMS_CSS_PHOTO_SHADOW = get_option("telaalbums_css_photo_shadow");
		$TELAALBUMS_CSS_PHOTO_ROTATE = get_option("telaalbums_css_photo_rotate");
		
		$TELAALBUMS_VERSION		 = get_option("telaalbums_version");
		$TELAALBUMS_refreshToken			= get_option("telaalbums_refresh_token");
		$TELAALBUMS_REQUIRE_FILTER  		= get_option("telaalbums_require_filter");
		//$SHOW_ALBUM_ANIMATIONS  = get_option("telaalbums_show_album_animations","1"); //Isaac
		$TELAALBUMS_SHOW_ALBUM_DETAILS  	= get_option("telaalbums_album_details");
		//$SHOW_BUTTON            = get_option("telaalbums_show_button"); // Rob
		//$SHOW_COMMENTS          = get_option("telaalbums_show_comments");
		//$SHOW_DROP_BOX          = get_option("telaalbums_show_dropbox");
		$TELAALBUMS_SHOW_FOOTER            = get_option("telaalbums_show_footer");
		$TELAALBUMS_SHOW_IMG_CAPTION		= get_option("telaalbums_show_caption");
		$TELAALBUMS_SHOW_N_ALBUMS          = get_option("telaalbums_show_n_albums");
		$TELAALBUMS_SITE_LANGUAGE          = get_option("telaalbums_language");
		//$THIS_VERSION           = get_option("telaalbums_version");
		$TELAALBUMS_THUMBSIZE              = get_option("telaalbums_thumbnail_size");
		$TELAALBUMS_TOKEN_EXPIRES			= get_option("telaalbums_token_expires");
		$TELAALBUMS_TRUNCATE_ALBUM_NAME  	= get_option("telaalbums_truncate_names");
		$TELAALBUMS_WHICH_JQ               = get_option("telaalbums_which_jq");
	
}

function TELAALBUMS_Activate() {
	global $THIS_VERSION;
				// Activation code here...
		update_option("telaalbums_add_widget", "1"); // Rob
		update_option("telaalbums_albpage_desc","0");
		update_option("telaalbums_album_details","0");
		update_option("telaalbums_album_thumbsize",240);
		update_option("telaalbums_albums_per_page",0);
		update_option("telaalbums_allow_slideshow","1");
		//update_option("telaalbums_cache_thumbs","0"); //Need to remove
		update_option("telaalbums_caption_length","25");
		update_option("telaalbums_check_for_updates", "1"); //Isaac
		update_option("telaalbums_crop_thumbs","1");
		
		update_option("telaalbums_css_album_grayscale","1");
		update_option("telaalbums_css_album_shadow","1");
		update_option("telaalbums_css_album_rotate","1");
		
		update_option("telaalbums_css_photo_grayscale","1");
		update_option("telaalbums_css_photo_shadow","1");
		update_option("telaalbums_css_photo_rotate","1");
		
		
		update_option("telaalbums_date_format","Y-m-d");
		update_option("telaalbums_description_length","25");
		update_option("telaalbums_developer_mode","0"); //Isaac
		update_option("telaalbums_hide_video","0");
		update_option("telaalbums_image_size","640");
		update_option("telaalbums_images_on_front",0);   // Rob
		update_option("telaalbums_images_per_page",0);
		update_option("telaalbums_jq_pagination","fade");
		update_option("telaalbums_language", "en_us");
		update_option("telaalbums_main_photo_page", "");
		update_option("telaalbums_permit_download","1");
		update_option("telaalbums_public_only","0");
		update_option("telaalbums_require_filter","0");
		update_option("telaalbums_show_album_animations","1"); //Isaac
		update_option("telaalbums_show_button", "1"); // Rob
		update_option("telaalbums_show_caption","ALWAYS");
		//update_option("telaalbums_show_comments, 0"); //Need to remove
		//update_option("telaalbums_show_dropbox","0"); //Need to remove
		update_option("telaalbums_show_footer","0");
		update_option("telaalbums_show_n_albums",0);
		update_option("telaalbums_thumbnail_size",240);
		update_option("telaalbums_truncate_names","1");
		
		update_option("telaalbums_version",$THIS_VERSION);
		
		update_option("telaalbums_which_jq","TELAALBUMS");
	}
	
	
if (!function_exists("TELAALBUMS_CSS")) {
function TELAALBUMS_CSS() {
		wp_enqueue_style( 'telaalbumsstyles', plugins_url('/attributes/css/style.css?v=1.1', dirname(__FILE__)));
		$TELAALBUMS_CSS_ALBUM_GRAYSCALE = get_option("telaalbums_css_album_grayscale");
		$TELAALBUMS_CSS_ALBUM_SHADOW = get_option("telaalbums_css_album_shadow");
		$TELAALBUMS_CSS_ALBUM_ROTATE = get_option("telaalbums_css_album_rotate");
		
		$TELAALBUMS_CSS_PHOTO_GRAYSCALE = get_option("telaalbums_css_photo_grayscale");
		$TELAALBUMS_CSS_PHOTO_SHADOW = get_option("telaalbums_css_photo_shadow");
		$TELAALBUMS_CSS_PHOTO_ROTATE = get_option("telaalbums_css_photo_rotate");
	
	//Start Album CSS options
	
	if ($TELAALBUMS_CSS_ALBUM_GRAYSCALE !== "0") {
		echo '<style>
				a.overlay{
					-webkit-transition: 0.2s; /* Safari */
					transition: 0.2s;
					-webkit-filter: grayscale(0%);
				}
				a.overlay:hover{
					-webkit-transition: 0.2s; /* Safari */
					transition: 0.2s;
					filter: url(filters.svg#grayscale); /* Firefox 3.5+ */
					filter: gray; /* IE6-9 */
					-webkit-filter: grayscale(100%); /* Chrome 19+ & Safari 6+ */
				}
			</style>';
	} else {
	}
	
	if ($TELAALBUMS_CSS_ALBUM_SHADOW !== "0") {
		echo '<style>
				.telaalbums_img        {
					box-shadow: 8px 8px 5px #888888;
				}
			</style>';
	} else {
	}
	
	if ($TELAALBUMS_CSS_ALBUM_ROTATE !== "0") {
		echo '<style>
				a.overlay{
					-webkit-transition: 0.2s; /* Safari */
					transition: 0.2s;
				}
				a.overlay:hover{
					-webkit-transition: 0.2s; /* Safari */
					transition: 0.2s;
					-ms-transform: rotate(-7deg); /* IE 9 */
					-webkit-transform: rotate(-7deg); /* Chrome, Safari, Opera */
					transform: rotate(-7deg);
				}
			</style>';
	} else {
	}
	
	//Start Photo CSS options
	
	if ($TELAALBUMS_CSS_PHOTO_GRAYSCALE !== "0") {
		echo '<style>
				.telaalbums_img{
					-webkit-transition: 0.2s; /* Safari */
					transition: 0.2s;
					-webkit-filter: grayscale(0%);
				}
				.telaalbums_img:hover{
					-webkit-transition: 0.2s; /* Safari */
					transition: 0.2s;
					filter: url(filters.svg#grayscale); /* Firefox 3.5+ */
					filter: gray; /* IE6-9 */
					-webkit-filter: grayscale(100%); /* Chrome 19+ & Safari 6+ */
				}
			</style>';
	} else {
	}
	
	if ($TELAALBUMS_CSS_PHOTO_SHADOW !== "0") {
		echo '<style>
				.telaalbums_img        {
					box-shadow: 8px 8px 5px #888888;
				}
			</style>';
	} else {
	}
	
	if ($TELAALBUMS_CSS_PHOTO_ROTATE !== "0") {
		echo '<style>
				.telaalbums_img{
					-webkit-transition: 0.2s; /* Safari */
					transition: 0.2s;
				}
				.telaalbums_img:hover{
					-webkit-transition: 0.2s; /* Safari */
					transition: 0.2s;
					-ms-transform: rotate(-7deg); /* IE 9 */
					-webkit-transform: rotate(-7deg); /* Chrome, Safari, Opera */
					transform: rotate(-7deg);
				}
			</style>';
	} else {
	}

}
}
	

function TELAALBUMS_Upgrade(){
	global $THIS_VERSION;
	require_once (dirname(__FILE__) . '/telaalbums_options.php');
		if ($TELAALBUMS_ADD_WIDGET == 'FALSE'){update_option("telaalbums_add_widget", "1");}
		if ($TELAALBUMS_ALBUM_THUMBSIZE == 'FALSE'){update_option("telaalbums_album_thumbsize",240);}
		if ($TELAALBUMS_ALBUMS_PER_PAGE == 'FALSE'){update_option("telaalbums_albums_per_page",0);}
		if ($TELAALBUMS_ALLOW_SLIDESHOW == 'FALSE'){update_option("telaalbums_allow_slideshow","1");}
		if ($TELAALBUMS_CAPTION_LENGTH == 'FALSE'){update_option("telaalbums_caption_length","25");}
		if ($TELAALBUMS_CHECK_FOR_UPDATES == 'FALSE'){update_option("telaalbums_check_for_updates", "1");}
		if ($TELAALBUMS_CROP_THUMBNAILS == 'FALSE'){update_option("telaalbums_crop_thumbs","1");}
		if ($TELAALBUMS_DATE_FORMAT == 'FALSE'){update_option("telaalbums_date_format","Y-m-d");}
		if ($TELAALBUMS_DESC_ON_ALBUM_PAGE == 'FALSE'){update_option("telaalbums_albpage_desc","0");}
		if ($TELAALBUMS_DESCRIPTION_LENGTH == 'FALSE'){update_option("telaalbums_description_length","25");}
		if ($TELAALBUMS_DEVELOPER_MODE == 'FALSE'){update_option("telaalbums_developer_mode","0");}
		if ($TELAALBUMS_GALLERY_THUMBSIZE == 'FALSE'){update_option("telaalbums_thumbnail_size",240);}
		if ($TELAALBUMS_HIDE_VIDEO == 'FALSE'){update_option("telaalbums_hide_video","0");}
		if ($TELAALBUMS_IMAGES_ON_FRONT == 'FALSE'){update_option("telaalbums_images_on_front",0);}
		if ($TELAALBUMS_IMAGES_PER_PAGE == 'FALSE'){update_option("telaalbums_images_per_page",0);}
		if ($TELAALBUMS_IMG_MAX == 'FALSE'){update_option("telaalbums_image_size","640");}
		if ($TELAALBUMS_JQ_PAGINATION_STYLE == 'FALSE'){update_option("telaalbums_jq_pagination","fade");}
		if ($TELAALBUMS_MAIN_PHOTO_PAGE == 'FALSE'){update_option("telaalbums_main_photo_page");}
		if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == 'FALSE'){update_option("telaalbums_permit_download","1");}
		if ($TELAALBUMS_PUBLIC_ONLY == 'FALSE'){update_option("telaalbums_public_only","0");}
		
		if ($TELAALBUMS_CSS_ALBUM_GRAYSCALE == 'FALSE'){update_option("telaalbums_css_album_grayscale","1");}
		if ($TELAALBUMS_CSS_ALBUM_SHADOW == 'FALSE'){update_option("telaalbums_css_album_shadow","1");}
		if ($TELAALBUMS_CSS_ALBUM_ROTATE == 'FALSE'){update_option("telaalbums_css_album_rotate","1");}
		
		
		if ($TELAALBUMS_CSS_PHOTO_GRAYSCALE == 'FALSE'){update_option("telaalbums_css_photo_grayscale","1");}
		if ($TELAALBUMS_CSS_PHOTO_SHADOW == 'FALSE'){update_option("telaalbums_css_photo_shadow","1");}
		if ($TELAALBUMS_CSS_PHOTO_ROTATE == 'FALSE'){update_option("telaalbums_css_photo_rotate","1");}
		
		
		if ($TELAALBUMS_REQUIRE_FILTER == 'FALSE'){update_option("telaalbums_require_filter","0");}
		if ($TELAALBUMS_SHOW_ALBUM_DETAILS == 'FALSE'){	update_option("telaalbums_album_details","0");}
		if ($SHOW_BUTTON == 'FALSE'){update_option("telaalbums_show_button", "1");}
		if ($TELAALBUMS_SHOW_FOOTER == 'FALSE'){update_option("telaalbums_show_footer","0");}
		if ($TELAALBUMS_SHOW_IMG_CAPTION == 'FALSE'){update_option("telaalbums_show_caption","ALWAYS");}
		if ($TELAALBUMS_SHOW_N_ALBUMS == 'FALSE'){update_option("telaalbums_show_n_albums",0);}
		if ($TELAALBUMS_SITE_LANGUAGE == 'FALSE'){update_option("telaalbums_language", "en_us");}
		if ($TELAALBUMS_THUMBSIZE == 'FALSE'){update_option("telaalbums_thumbnail_size",240);}
		if ($TELAALBUMS_TRUNCATE_ALBUM_NAME == 'FALSE'){update_option("telaalbums_truncate_names","1");}
		if ($TELAALBUMS_WHICH_JQ == 'FALSE'){update_option("telaalbums_which_jq","telaalbums");}
		
		if ($TELAALBUMS_VERSION == 'FALSE' || $TELAALBUMS_VERSION < $THIS_VERSION){update_option("telaalbums_version",$THIS_VERSION);}
		
		delete_option("telaalbums_cache_thumbs");
		delete_option("telaalbums_show_album_animations"); //Can remove after a few versions
		delete_option("telaalbums_show_comments"); //Remove
		delete_option("telaalbums_show_dropbox"); //REmove
		delete_option("telaalbums_updates"); //Can remove after a few versions
	
}



if (!function_exists("telaalbums_setupCaption")) {
function telaalbums_setupCaption($caption,$count) {
	
                $return = "alt=\"$caption\" title=\"$caption\"";
        return($return);
}
}


function telaalbums_refreshOAuth2Token() {
	$TELAALBUMS_DEBUG = 0;
    $TELAALBUMS_now = date("U");
    $TELAALBUMS_clientId = get_option("telaalbums_client_id");
    $TELAALBUMS_clientSecret = get_option("telaalbums_client_secret");
    $TELAALBUMS_refreshToken = get_option("telaalbums_refresh_token");
    $postBody = 'client_id='.urlencode($TELAALBUMS_clientId)
              .'&client_secret='.urlencode($TELAALBUMS_clientSecret)
              .'&refresh_token='.urlencode($TELAALBUMS_refreshToken)
              .'&grant_type=refresh_token';
          
    $curl = curl_init();
	if (!isset($OAUTH2_REFERER)){$OAUTH2_REFERER = "";}//Fixes Undefined
    curl_setopt_array( $curl,
                     array( CURLOPT_CUSTOMREQUEST => 'POST'
                           , CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/token'
                           , CURLOPT_HTTPHEADER => array( 'Content-Type: application/x-www-form-urlencoded'
                                                         , 'Content-Length: '.strlen($postBody)
                                                         , 'User-Agent: HoltstromLifeCounter/0.1 +http://holtstrom.com/michael'
                                                         )
                           , CURLOPT_POSTFIELDS => $postBody                              
                           , CURLOPT_REFERER => $OAUTH2_REFERER
                           , CURLOPT_RETURNTRANSFER => 1 // means output will be a return value from curl_exec() instead of simply echoed
                           , CURLOPT_TIMEOUT => 12 // max seconds to wait
                           , CURLOPT_FOLLOWLOCATION => 0 // don't follow any Location headers, use only the CURLOPT_URL, this is for security
                           , CURLOPT_FAILONERROR => 0 // do not fail verbosely fi the http_code is an error, this is for security
                           , CURLOPT_SSL_VERIFYPEER => 1 // do verify the SSL of CURLOPT_URL, this is for security
                           , CURLOPT_VERBOSE => 0 // don't output verbosely to stderr, this is for security
                     ) );
    $orig_response = curl_exec($curl);
    $response = json_decode($orig_response, true); // convert returned objects into associative arrays
    $TELAALBUMS_TOKEN_EXPIRES = $TELAALBUMS_now + $response['expires_in'];
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($response['access_token']) {
		if ($TELAALBUMS_DEBUG) {
			echo "DEBUG: [telaalbums_refreshOAuth2Token] got the following response:</p>";
			echo "DEBUG: [telaalbums_refreshOAuth2Token] $orig_response </p>";
			echo "DEBUG: [telaalbums_refreshOAuth2Token] using refreshToken $TELAALBUMS_refreshToken</p>";
		}
        update_option("telaalbums_oauth_token",$response['access_token']);          # save the access token
        update_option("telaalbums_token_expires",$TELAALBUMS_TOKEN_EXPIRES);                   # save the epoch when the token expires
    } else {
        echo "telaalbums_refreshOAuth2Token got the following response:<br />";
        echo $orig_response;
		echo "using refreshToken $TELAALBUMS_refreshToken";
    }

}



function telaalbums_doCurlExec($file) {

	$TELAALBUMS_DEBUG = 0;
	$TELAALBUMS_PUBLIC_ONLY = get_option("telaalbums_public_only","1");
	#----------------------------------------------------------------------------
	# Curl code to store XML data from TELA in a variable
	#----------------------------------------------------------------------------
	$ch = curl_init();
	$timeout = 0; // set to zero for no timeout
	curl_setopt($ch, CURLOPT_URL, $file);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	
	# Display only public albums if PUBLIC_ONLY=1 in config.php
	if ($TELAALBUMS_PUBLIC_ONLY == "0") {
		$TELAALBUMS_OAUTH_TOKEN = get_option("telaalbums_oauth_token");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                 'Authorization: Bearer ' . $TELAALBUMS_OAUTH_TOKEN
                ));
	}

	$addressData = curl_exec($ch);
	if ($TELAALBUMS_DEBUG) { echo $addressData; }
	curl_close($ch);

	#----------------------------------------------------------------------------
	# Parse the XML data into an array
	#----------------------------------------------------------------------------
	$p = xml_parser_create();
	xml_parse_into_struct($p, $addressData, $vals, $index);
	xml_parser_free($p);

	return ($vals);

}


//if (!function_exists("check_for_updates")) {
//function check_for_updates($my_version) {
//
//        $version = file_get_contents('http://telaalbums.smccandl.net/wp-pro-ver.html');
//        if ($version !== false) {
//                $version=trim($version);
//                if ($version > $my_version) {
//                        return("<table><tr class='plugin-update-tr'><td class='plugin-update'><div class='update-message'>New Version Available.  <a href='http://code.google.com/p/telaalbums/downloads/list'>Get v$version!</a></div></td></tr></table>");
//                } else {
//                        return("Thanks for your donation!");
//                }
//        } else {
//                # We had an error, fake a high version number so no message is printed.
//                $version = "9999";
//        }
//
//}
//}

//if (!function_exists("isProActive")) {
//function isProActive() {
//	if (function_exists("telaalbums_pro_validateCachePerms")) {
//        	return("1");									This is old.  No pro anymore... COMPLETELY FREE
//	} else {
//        	return("1");	
//	}
//}
//}
?>