<?PHP
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require (dirname(__FILE__) . '/includes/telaalbums_options.php');

function telaalbums_dumpAlbumList($FILTER,$COVER = "FALSE",$overrides_array,$TELAALBUMS_SLIDESHOW = "NULL") {
	
$client_id = get_option("telaalbums_client_id");$client_secret = get_option("telaalbums_client_secret");$TELAALBUMS_GOOGLE_USER = get_option("telaalbums_google_username");$TELAALBUMS_OAUTH_TOKEN = get_option("telaalbums_oauth_token");$site_url = admin_url();$settings_url = $site_url . "/admin.php?page=telaalbums";if (($TELAALBUMS_OAUTH_TOKEN == "FALSE" || $TELAALBUMS_OAUTH_TOKEN == "" || $TELAALBUMS_OAUTH_TOKEN == "NULL" )){echo "Your settings are not configured.  <a href='".$settings_url."'>Please set up the plugin by clicking here.</a>";exit;} // Exit if settings are not correct.

require_once dirname(__FILE__) . '/globals.php';

require (dirname(__FILE__) . '/includes/telaalbums_options.php');

# ---------------------------------------------------------------------------
# Refresh the oauth2 token if it has expired
# ---------------------------------------------------------------------------
if ($TELAALBUMS_DEBUG) { echo "<p>DEBUG: Now is $TELAALBUMS_now, token expires at $TELAALBUMS_TOKEN_EXPIRES</p>"; }
if (($TELAALBUMS_now > $TELAALBUMS_TOKEN_EXPIRES) && ($TELAALBUMS_PUBLIC_ONLY == '0')) {
	if ($TELAALBUMS_DEBUG) { echo "<p>DEBUG: [dumpAlbumContents] Token is expired, calling function to refresh it</p>"; }
	telaalbums_refreshOAuth2Token(); # do the refresh
	$TELAALBUMS_OAUTH_TOKEN = get_option("telaalbums_oauth_token"); # get the token again
} else {
	$time_until_expiry = $TELAALBUMS_TOKEN_EXPIRES - $TELAALBUMS_now;
	if ($TELAALBUMS_DEBUG) { echo "<p>DEBUG: [dumpAlbumContents] Token is still valid for another $time_until_expiry secs</p>"; }
}
# The overrides
//Added isset because of undefined error
if (isset($overrides_array["images_per_page"])) { $TELAALBUMS_IMAGES_PER_PAGE = $overrides_array["images_per_page"];}
if (isset($overrides_array["image_size"])) { $TELAALBUMS_IMG_MAX = $overrides_array["image_size"];}
if (isset($overrides_array["thumbnail_size"])) { $TELAALBUMS_GALLERY_THUMBSIZE = $overrides_array["thumbnail_size"];}
if (isset($overrides_array["picasaweb_user"])) { $TELAALBUMS_GOOGLE_USER = $overrides_array["picasaweb_user"];}

#----------------------------------------------------------------------------
# Check Permalink Structure 
#----------------------------------------------------------------------------
if ( get_option('permalink_structure') != '' ) { 
	# permalinks enabled
	if(isset($_SERVER["REQUEST_URI"])){
	$uri = $_SERVER["REQUEST_URI"];
	}
	list($back_link,$uri_tail) = array_pad(explode('?',$uri, 2), 2, null);
	$urlchar = '?';
        $splitchar = '\?';
} else {
	$uri = $_SERVER["REQUEST_URI"];
	list($back_link,$uri_tail) = array_pad(explode('&',$uri, 2), 2, null);
	$urlchar = '&';
        $splitchar = $urlchar;
}

# Added to support format adjustments when using wptouch, need to check if wptouch is enabled first
//global $wptouch_plugin;
#echo "WP: $wptouch_plugin->applemobile";
//if ($wptouch_plugin->applemobile == "1") {
	
//Replaced with WP_IS_MOBILE becuase of issue with wptouch.

if ( wp_is_mobile() == "TRUE") {

	$TELAALBUMS_SHOW_ALBUM_DETAILS = "0";
	$TELAALBUMS_PERMIT_IMG_DOWNLOAD = "0";
	$TELAALBUMS_CAPTION_LENGTH = "15";

}

#-----------------------------------------------------------------------------------------
# Load Language File 
#-----------------------------------------------------------------------------------------

			$TELAALBUMS_SITE_LANGUAGE          = get_option("telaalbums_language");


#----------------------------------------------------------------------------
# CONFIGURATION
#----------------------------------------------------------------------------
$TRUNCATE_FROM = $TELAALBUMS_CAPTION_LENGTH;       # Should be around 25, depending on font and thumbsize
$TRUNCATE_TO   = $TELAALBUMS_CAPTION_LENGTH - 3;   # Should be $TRUNCATE_FROM minus 3
$TELAALBUMS_DESCRIPTION_LENGTH_TO = $TELAALBUMS_DESCRIPTION_LENGTH - 3;
$OPEN=0;
$TW20 = $TELAALBUMS_ALBUM_THUMBSIZE + round($TELAALBUMS_ALBUM_THUMBSIZE * .1);
$TWM10 = $TELAALBUMS_ALBUM_THUMBSIZE - 8;

#----------------------------------------------------------------------------
# Check for required variables from config file
#----------------------------------------------------------------------------
require (dirname(__FILE__) . '/includes/telaalbums_options.php');
$TELAALBUMS_REQUIRE_FILTER  		= get_option("telaalbums_require_filter","0");

if (!isset($TELAALBUMS_OAUTH_TOKEN, $TELAALBUMS_GOOGLE_USER, $TELAALBUMS_ALBUM_THUMBSIZE, $TELAALBUMS_REQUIRE_FILTER, $TELAALBUMS_STANDALONE_MODE)) {
	require (dirname(__FILE__) . '/includes/telaalbums_options.php');
        echo "Data Token: ".$TELAALBUMS_OAUTH_TOKEN;
		echo "User: ".$TELAALBUMS_GOOGLE_USER;
		echo "Album Thumb Size: ".$TELAALBUMS_ALBUM_THUMBSIZE;
		echo "Require Filter: ".$TELAALBUMS_REQUIRE_FILTER;
		echo "Standalone Mode: ".$TELAALBUMS_STANDALONE_MODE;
        exit;
}

#----------------------------------------------------------------------------
# VARIABLES
#----------------------------------------------------------------------------
if ($TELAALBUMS_REQUIRE_FILTER != "0") {
	if ((!isset($FILTER)) || ($FILTER == "")) {
		die($LANG_PERM_FILTER);
	}
}

#----------------------------------------------------------------------------
# Request URL for Album list
#----------------------------------------------------------------------------
$TELAALBUMS_ALBUM_THUMBSIZE      	= get_option("telaalbums_album_thumbsize");
$file = "https://picasaweb.google.com/data/feed/api/user/" . $TELAALBUMS_GOOGLE_USER . "?kind=album&thumbsize=" . $TELAALBUMS_ALBUM_THUMBSIZE . "c";

#----------------------------------------------------------------------------
# Pagination for Album list
#----------------------------------------------------------------------------
if ($TELAALBUMS_ALBUMS_PER_PAGE != 0) {

	if (!isset($_GET['pg'])){$page = 1;}else{$page = $_GET['pg'];}
	if (!(isset($page))) {
		$page = 1;
	}
	if ($page > 1) {
		$start_image_index = (($page - 1) * $TELAALBUMS_ALBUMS_PER_PAGE) + 1;
	} else {
		$start_image_index = 1;
	}

	$file .= "&max-results=" . $TELAALBUMS_ALBUMS_PER_PAGE . "&start-index=" . $start_image_index;

}

$vals = telaalbums_doCurlExec($file);


#----------------------------------------------------------------------------
# Iterate over the array and extract the info we want
#----------------------------------------------------------------------------
foreach ($vals as $val) {

	if ($OPEN != 1) {

		if ($val["tag"] == "ENTRY") {

			if ($val["type"] == "open") {

				$OPEN = 1;

			}
		} else if ($val["tag"] == "OPENSEARCH:TOTALRESULTS") {
			global $ALBUM_COUNT;
			$ALBUM_COUNT = $val["value"];
			$random_album = rand(0,$ALBUM_COUNT);

	}
		
	} else {

	   switch ($val["tag"]) {

			case "ENTRY":
				if ($val["type"] == "close") {
					$OPEN=0;
				}
				break;
			case "MEDIA:THUMBNAIL":
				$thumb = trim($val["attributes"]["URL"] . "\n");
				break;	
				
					//case "MEDIA:DESCRIPTION":
					//$desc = trim($val["value"]) . "\n";
					//break;
						
						case "MEDIA:TITLE":
								$desc = trim($val["value"]) . "\n";
                                $title = trim($val["value"]);
                                break;
                        case "LINK":
				if ($val["attributes"]["REL"] == "alternate") {
                                	$href = trim($val["attributes"]["HREF"]);
				}
                                break;
                        case "GPHOTO:NUMPHOTOS":
                                $num = trim($val["value"]);
                                break;
								
			case "GPHOTO:LOCATION":
				if(isset($val["value"])){
                                $loc = trim($val["value"]);
				}
                                break;
			#case "PUBLISHED":
                	 #$published = trim($val["value"]);
					 #$published = substr($published,0,10);
                     #break;
						
						
			case "GPHOTO:TIMESTAMP":
				$epoch = $val["value"];
				break;
				
			case "GPHOTO:ACCESS":
				$access = trim($val["value"]);
				if ($access == "protected") { $daccess = "Private"; }
				else { $daccess = "Public"; }
				break;
				
			case "GPHOTO:NAME":
				$picasa_name = trim($val["value"]);
				break;
	    }
        }

	#----------------------------------------------------------------------------
	# Once we have all the pieces of info we want, dump the output
	#----------------------------------------------------------------------------
	if (isset($thumb) && isset($title) && isset($href) && isset($num) && isset($epoch)) {
		
		if (($FILTER != "") && ($FILTER != "RANDOM")) {
				$pos = strlen(strpos($title,$FILTER));
				if ($pos > 0) { 
					
					$pos = 0; 
				} else if (in_array($title,$ALBUMS_TO_HIDE)) {
                    
					$pos = 1;
				} else { 
					
					$pos = 1; 
				}
				if ($FILTER == $picasa_name) { $pos = 0; }
				} else {
						if (isset($overrides_array['hide_albums'])){$ALBUMS_TO_HIDE = ($overrides_array['hide_albums']) ? explode(",",$overrides_array['hide_albums']) : array();}
						
						$pos = strlen(strpos($title,"_hide"));
						if (isset($ALBUMS_TO_HIDE)){
							if (in_array($title,$ALBUMS_TO_HIDE)) {
									$pos = 1;
							}
						} else {
                                $pos = strlen(strpos($title,"_hide"));
						}
		}
		
		if ($pos == 0) {
		

		   if ((($FILTER == "RANDOM") && ($random_album == $ALBUM_COUNT)) || ($FILTER != "RANDOM")) {

                        list($disp_name,$tags) = array_pad(explode('_',$title, 2), 2, null);

			# --------------------------------------------------------------------
			# Added via issue 7, known problem: long names can break div layout
			# --------------------------------------------------------------------
			if ((strlen($disp_name) > $TRUNCATE_FROM) && ($TELAALBUMS_TRUNCATE_ALBUM_NAME == "1")) {
                                $disp_name = substr($disp_name,0,$TRUNCATE_TO) . "...";
                        }
						global $total_images;
					$total_images = $total_images + $num;
			
						global $out;
                        $out .= "<div class='telaalbums_albumcover'>\n";
			$uri = $_SERVER["REQUEST_URI"];
			list($back_link,$uri_tail) = array_pad(explode('?',$uri, 2), 2, null);
                   	if ( get_option('permalink_structure') != '' ) {
                        	# permalinks enabled
				$permalinks_on = 1;
                        	$urlchar = '?';
                   	} else {
				$permalinks_on = 0;
                        	$urlchar = '&';
                   	}
			if (($FILTER == "RANDOM") || ($COVER == "TRUE")){
				$blog_url = get_bloginfo('url');
				$RANDOM_URI = $blog_url . "/?page_id=" . $TELAALBUMS_MAIN_PHOTO_PAGE;
				$out .= "<a style='width: " . $TWM10 . "px;' class='overlay' href=\"" . $RANDOM_URI . "&album=$picasa_name\"><img class='telaalbums_img' alt='$picasa_name' title='$picasa_name' src=\"$thumb\" />";
			} else {
				list($paged_head,$paged_tail) = array_pad(explode('?',$_SERVER['REQUEST_URI'], 2), 2, null);
				if ($permalinks_on) {
                                        $out .= "<a style='width: " . $TWM10 . "px;' class='overlay' href=\"" . $paged_head . $urlchar . "album=$picasa_name\"><img class='telaalbums_img' alt='$picasa_name' title='$picasa_name' src=\"$thumb\" />";
                                } else {
                                        $out .= "<a style='width: " . $TWM10 . "px;' class='overlay' href='" . $_SERVER["REQUEST_URI"] . $urlchar . "album=" . $picasa_name. "'><img class='telaalbums_img' alt='$picasa_name' title='$picasa_name' src=\"$thumb\" />";
                                }
			}

			$trim_epoch = substr($epoch,0,10);
			$published = date($TELAALBUMS_DATE_FORMAT, $trim_epoch);

			# ------------------------------------------------
			# Overlay album details on thumbnail if requested
			# ------------------------------------------------
			if (($TELAALBUMS_SHOW_ALBUM_DETAILS == "1") && ($COVER != "TRUE")) {
				if ($desc != "") {
					if (strlen($desc) > $TELAALBUMS_DESCRIPTION_LENGTH) {
						$desc = substr($desc,0,$TELAALBUMS_DESCRIPTION_LENGTH_TO) . "...";
					}
                                        $out .= "<span>";
					$out .= "<p class='overlaypg'>$desc</p>";
					
					if (!empty($loc)) {
						$out .= "<p class='overlaystats'>$LANG_WHERE: $loc</p>";
					}
					$out .= "<p class='overlaystats'>$LANG_ACCESS: $daccess</p>";
					$out .= "</span>\n";
				}
                        }
			$out .= "</a>";
                        $out .= "<div class='telaalbums_galdata'>\n";
			if (($FILTER == "RANDOM") || ($COVER == "TRUE")) {
				if ($COVER != "TRUE") {
					$RANDOM_URI = $back_link . "?page_id=" . $TELAALBUMS_MAIN_PHOTO_PAGE;
                        		$out .= "<a class='album_link' href='" . $RANDOM_URI . $urlchar . "album=$picasa_name'>$disp_name</a>\n";
				}
			} else {
                                list($paged_head,$paged_tail) = array_pad(explode('?',$_SERVER['REQUEST_URI'], 2), 2, null);
                                if ($permalinks_on) {
                                        $out .= "<a class='album_link' href='" . $paged_head . $urlchar . "album=$picasa_name'>$disp_name</a>\n";
                                } else {
                                        $out .= "<a class='album_link' href='" . $_SERVER["REQUEST_URI"] . $urlchar . "album=$picasa_name'>$disp_name</a>\n";
                                }
			}
			if ((wp_is_mobile() !== "TRUE") && ($COVER != "TRUE")) {
                        $out .= "<span class='telaalbums_albstat'>$published, $num Images</span>\n";
			} else {
			$out .= "<span class='albstat-wpt'>&nbsp;</span>\n";
			}
                        $out .= "</div>";
                        $out .= "</div>\n";

		   }

                }
                #----------------------------------
                # Reset the variables
                #----------------------------------
                unset($thumb);
		unset($title);
        }
}
   if ( ($FILTER != "RANDOM") && (strtoupper($COVER) != "TRUE")) {
	$header = "<div style='clear: both'></div><div id='telaheader'>";
	if (wp_is_mobile() !== "FALSE") {
		
		$header .= "<span class='lang_gallery'>$FILTER Gallery</span>";
		$header .= "<span class='total_images'>$total_images Photos in $ALBUM_COUNT Albums</span></div>\n";
	} else { 
 $header .= "<span class='total_images_wpt'>$total_images Photos in $ALBUM_COUNT Albums</span></div>\n";
               $header .= "</div>\n";
	}
	global $out;
	$out = $header . $out;
		
	if ($TELAALBUMS_SHOW_FOOTER == "1") {
		$out .= "<div id='telafooter'>$LANG_GENERATED <a href='http://code.google.com/p/telaalbums/'>Telaaedifex's Albums</a> v" . $THIS_VERSION . ".</div>";
	}
   }

	#----------------------------------------------------------------------------
	# Show output for pagination
	#----------------------------------------------------------------------------
	if (($TELAALBUMS_ALBUMS_PER_PAGE != 0) && ($ALBUM_COUNT > $TELAALBUMS_ALBUMS_PER_PAGE) && ($COVER != "TRUE")){

		$out .= "<div id='pages'>";
		$paginate = ($ALBUM_COUNT/$TELAALBUMS_ALBUMS_PER_PAGE) + 1;
		$out .= "Page: ";

		# List pages
		for ($i=1; $i<$paginate; $i++) {

		   $link_image_index=($i - 1) * ($TELAALBUMS_ALBUMS_PER_PAGE + 1);
		
		   $uri = $_SERVER["REQUEST_URI"];
		   list($uri,$tail) = array_pad(explode($urlchar,$_SERVER['REQUEST_URI'], 2), 2, null);
		   $href = $uri . $urlchar . "pg=$i";

		  # Show current page
		  if ($i == $page) {
			$out .= "<strong>$i </strong>";
		   } else {
			$out .= "<a class='page_link' href='$href'>$i</a> ";
		   }
		}

		$out .= "</div>";

	}

   $out .= "<div style='clear: both'></div><div style='clear: both'></div>"; # Ensure Telaaedifex's Albums doesn't break theme layout
   #----------------------------------------------------------------------------
   # Output footer if required
   #----------------------------------------------------------------------------
   #if ($TELAALBUMS_STANDALONE_MODE == "TRUE") {
#
	#$out .= "</div>" . "\n";
#   }

return $out;
unset($out);
}
?>