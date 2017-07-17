<?PHP
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once dirname(__FILE__) . '/globals.php';
// For non-PHP5 users
if (!function_exists("stripos")) {
   function stripos($str,$needle,$offset=0) {
     return strpos(strtolower($str),strtolower($needle),$offset);
    }
}


function telaalbums_showAlbumContents($ALBUM,$IN_POST = null,$TAG,$overrides_array) {
require (dirname(__FILE__) . '/includes/telaalbums_options.php');


# ---------------------------------------------------------------------------
# Refresh the oauth2 token if it has expired
# ---------------------------------------------------------------------------
if (($TELAALBUMS_now > $TELAALBUMS_TOKEN_EXPIRES) && ($TELAALBUMS_PUBLIC_ONLY == '0')) {
        #echo "Time to refresh...";
        telaalbums_refreshOAuth2Token(); # do the refresh
        $TELAALBUMS_OAUTH_TOKEN = get_option("telaalbums_oauth_token"); # get the token again
}

if (isset($overrides_array["album"])) { $album = $overrides_array["album"];
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
# Request URL for Album list
#----------------------------------------------------------------------------
$TELAALBUMS_ALBUM_THUMBSIZE      	= get_option("telaalbums_album_thumbsize");
$file = "https://picasaweb.google.com/data/feed/api/user/" . $TELAALBUMS_GOOGLE_USER . "?kind=album&thumbsize=" . $TELAALBUMS_ALBUM_THUMBSIZE . "c";

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
						if (strpos($album,$title) !== false){
						$pos = 0;
						}else{$pos = 1;}
		
		if ($pos == 0) {
			
		   if (strpos($album,$title) !== false) {
			   
                        list($disp_name,$tags) = array_pad(explode('_',$title, 2), 2, null);
						global $ALBUM_NAME;
						unset($ALBUM_NAME);
						$ALBUM_NAME = $picasa_name;
						global $ALBUM_NAME;
		   }
		   global $ALBUM_NAME;
		   $ALBUM_NAME = $picasa_name;
       }
     #----------------------------------
     # Reset the variables
     #----------------------------------
                unset($thumb);
		unset($title);
        }
}

//-----------------------------------------------


global $TZM30, $TZM10;
$TZ10 = $TELAALBUMS_GALLERY_THUMBSIZE + round($TELAALBUMS_GALLERY_THUMBSIZE * .06);
$TZ20 = $TELAALBUMS_GALLERY_THUMBSIZE + round($TELAALBUMS_GALLERY_THUMBSIZE * .15);
$TZ30 = $TELAALBUMS_GALLERY_THUMBSIZE + round($TELAALBUMS_GALLERY_THUMBSIZE * .25);
$TZM10 = $TELAALBUMS_GALLERY_THUMBSIZE - round($TELAALBUMS_GALLERY_THUMBSIZE * .06);
$TZM20 = $TELAALBUMS_GALLERY_THUMBSIZE - round($TELAALBUMS_GALLERY_THUMBSIZE * .09);
$TZM30 = $TELAALBUMS_GALLERY_THUMBSIZE - round($TELAALBUMS_GALLERY_THUMBSIZE * .22);
$TZM2 = $TELAALBUMS_GALLERY_THUMBSIZE - 2;
$TZP10 = $TELAALBUMS_GALLERY_THUMBSIZE + 10;
$TZP2  = $TELAALBUMS_GALLERY_THUMBSIZE + 2;
$image_count=0;
$picasa_title="NULL";
$count=0;
$OPEN=0;
$TRUNCATE_FROM = $TELAALBUMS_CAPTION_LENGTH;       // Should be around 22, depending on font and thumbsize
$TRUNCATE_TO   = $TELAALBUMS_CAPTION_LENGTH - 3;   // Should be $TRUNCATE_FROM minus 3
$uri = $_SERVER["REQUEST_URI"];
$useragent = $_SERVER['HTTP_USER_AGENT']; // Check useragent to suppress hover for IE6
if(strchr($useragent,"MSIE 6.0")) { $USING_IE_6 = "TRUE"; }
$gphotoid="1234678";

//----------------------------------------------------------------------------
// Grab album data from URL
//----------------------------------------------------------------------------
// Reformat the album title for display
list($ALBUM_TITLE,$tags) = array_pad(explode('_',$ALBUM_NAME, 2), 2, null);

//----------------------------------------------------------------------------
// Check for required variables from config file
//----------------------------------------------------------------------------
if (!isset($TELAALBUMS_OAUTH_TOKEN, $TELAALBUMS_GOOGLE_USER, $TELAALBUMS_IMG_MAX, $TELAALBUMS_GALLERY_THUMBSIZE, $TELAALBUMS_REQUIRE_FILTER, $TELAALBUMS_STANDALONE_MODE, $TELAALBUMS_IMAGES_PER_PAGE)) {

	echo "<h1>" . $LANG_MISSING_VAR_H1 . "</h1><h3>" . $LANG_MISSING_VAR_H3 . "</h3>";
	exit;
}

$meta_tag = "";

//----------------------------------------------------------------------------
// VARIABLES FOR PAGINATION
//----------------------------------------------------------------------------
if ($IN_POST == "TRUE") {
        $TELAALBUMS_IMAGES_PER_PAGE = 0;
} else if ($IN_POST == "SLIDESHOW") {
	$TELAALBUMS_IMG_MAX = "d";	
	$TELAALBUMS_SHOW_IMG_CAPTION = "SLIDESHOW";
}

if ($TELAALBUMS_CROP_THUMBNAILS == "1") { $CROP_CHAR = "c"; }
else { $CROP_CHAR = "u"; }

global $ALBUM_NAME;
$file = "https://picasaweb.google.com/data/feed/api/user/" . $TELAALBUMS_GOOGLE_USER;
if ($ALBUM_NAME != "NULL") { global $ALBUM_NAME;$file .= "/album/" . $ALBUM_NAME; }
$file.= "?kind=photo&thumbsize=" . $TELAALBUMS_GALLERY_THUMBSIZE . $CROP_CHAR . "&imgmax=" . $TELAALBUMS_IMG_MAX;	

if ($TAG != "NULL") { $file .= "&tag=$TAG"; }


if ($TELAALBUMS_IMAGES_PER_PAGE != 0) {
	
	if (!isset($_GET['pg'])){$page = 1;}else{$page = $_GET['pg'];}
	if (!(isset($page))) {
		$page = 1;
	}
	if ($page > 1) {
		$start_image_index = (($page - 1) * $TELAALBUMS_IMAGES_PER_PAGE) + 1;
	} else {
		$start_image_index = 1;
	}

	$file .= "&max-results=" . $TELAALBUMS_IMAGES_PER_PAGE . "&start-index=" . $start_image_index;

}
	
	$vals = telaalbums_doCurlExec($file); 

// Iterate over the array and extract the info we want
//----------------------------------------------------------------------------
unset($thumb);
unset($title);
unset($href);
unset($path);
unset($url);
unset($gphotoid);

foreach ($vals as $val) {

        if ($OPEN != 1) {
	   switch ($val["tag"]) {
		   
		case "ENTRY":
                     if ($val["type"] == "open") {
                         $OPEN=1;
                     }
                     break;

		case "TITLE":
                     if ($picasa_title == "NULL") {
                         $picasa_title = $val["value"];
                     }
					break;
		 case "GPHOTO:NUMPHOTOS":
                     // Fix for Issue 12
					 global $numphotos; // Fixes undefined
                     if (!is_numeric($numphotos)) {
                         $numphotos = $val["value"];
                     }
                     break;

		 case "GPHOTO:ID":
                     $albumid = $val["value"];
                     break;

		 case "OPENSEARCH:TOTALRESULTS":
		 			global $telaalbums_result_count;
                     $telaalbums_result_count = $val["value"];
                     break;
	   }

        } else {
           switch ($val["tag"]) {

                        case "ENTRY":
                                if ($val["type"] == "close") {
                                        $OPEN=0;
                                }
                                break;
                        case "MEDIA:THUMBNAIL":
    				$tnht = $val["attributes"]["HEIGHT"];
    				$tnwd = $val["attributes"]["WIDTH"];
    				// Temporary? fix for google api bug 2011-05-28
    				if (($tnht == $TELAALBUMS_GALLERY_THUMBSIZE) || ($tnwd == $TELAALBUMS_GALLERY_THUMBSIZE)) {
        				$thumb = trim($val["attributes"]["URL"] . "\n");
    				}
    				break;
                        case "MEDIA:CONTENT":
                                $href = $val["attributes"]["URL"];
								$orig_href = str_replace("s$TELAALBUMS_IMG_MAX","d",$href);
								$filename = basename($href);
                                $imght = $val["attributes"]["HEIGHT"];
                                $imgwd = $val["attributes"]["WIDTH"];
                                break;
                        case "SUMMARY":
						if(isset($val["value"])){
							global $text;
                                $text = $val["value"];
						}
								
                                break;
                        case "GPHOTO:ID":
                                //if (!isset($gphotoid)) {
                                        $gphotoid = trim($val["value"]);
                                //}
                                break;
	   }
        }
		


        //----------------------------------------------------------------------------
        // Once we have all the pieces of info we want, dump the output
        //----------------------------------------------------------------------------
        if (isset($thumb) && isset($href) &&  isset($gphotoid)) {

		$add_s = "";

		if (function_exists("telaalbums_cache_thumb")) {
			$thumb = telaalbums_cache_thumb($thumb);
		}
		
		// Grab the album title once
			if (!isset($STOP_FLAG)){
				global $STOP_FLAG;
				$STOP_FLAG=0;
			} 
            if (isset($STOP_FLAG)){
				
				 if ($STOP_FLAG != 1) {
					list($AT,$tags) = array_pad(explode('_',$picasa_title, 2), 2, null);
					$AT = str_replace("\"", "", $AT);
                        $AT = str_replace("'", "",$AT);
			if (($IN_POST != "TRUE") && ($IN_POST != "SLIDESHOW")) {
				if (($TAG == "") || ($TAG == "NULL")) {
						global $out;
                                	$out .= "<div id='title'><h2>$AT</h2>";
				} else {
					global $out;
					$out .= "<div id='title'><h2>Photos tagged '$TAG'</h2>";
				}
				global $out;
                        }
						global $STOP_FLAG;
                        $STOP_FLAG=1;
                }
			}
		
		// Set image caption
				global $text;
                if ($text != "") {
                        $caption = htmlentities( $text , ENT_QUOTES );
                } else {
                        $caption = $AT . " - " . $filename;
                }
		// Keep count of images
                $count++;

		// Shorten caption as necessary
                if ((strlen($caption) > $TRUNCATE_FROM) && ($TELAALBUMS_TRUNCATE_ALBUM_NAME == "1")) {
                        $short_caption = substr($caption,0,$TRUNCATE_TO) . "...";
			if (strlen($short_caption) > $TRUNCATE_FROM) { 
				$short_caption = substr($filename,0,$TRUNCATE_FROM);
			}
                } else {
			$short_caption = $caption;
		}

		// Hide Videos
                $vidpos = stripos($href, "googlevideo");

		if (($vidpos == "") || ($TELAALBUMS_HIDE_VIDEO == "0")) {

		   if ($TELAALBUMS_SHOW_IMG_CAPTION == "SLIDESHOW") {

                        echo "<img src='" . $href . "' width='" . $imgwd . "' height='" . $imght . "' />\n";

		   // CASE: CAPTION = HOVER & IE6 = FALSE
                   } else if (($TELAALBUMS_SHOW_IMG_CAPTION == "HOVER") && ($USING_IE_6 != "TRUE")){

			// ONLY WANT HEIGHT IF NON-CROPPED THUMBNAILS
			global $out;
			$out .= "<div class='telaalbums_thumbnail' style='width: " . $TZ10 . "px; ";

			if ($TELAALBUMS_CROP_THUMBNAILS == "0") {
                                global $out;
								$out .= "height: " . $TZ30 . "px; ";
                        }
global $out;
			$out .= "text-align: center;'>\n";
                        $caption_link_tweak = telaalbums_setupCaption($caption,$count);
						$out .= " <a $caption_link_tweak href='$href'><img class='telaalbums_img' src='$thumb' alt='$caption' /></a>\n";
                        $out .= " <div id='options' style='width:" . $TZ10 . "px;'>\n";
                        $out .= "  <span class='short_caption'>$short_caption</a></span>\n";

                        // Show Download Icon
                        if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == "1") {
							global $out;
				$out .= telaalbums_buildDownloadDiv($filename,$orig_href,"margin-left: " . $TZM20 . "px; padding-top: 3px;");
                       	} 
						global $out;
                        $out .= "</div>\n";
                        $out .= "</div>";

		   // CASE: CAPTION = ALWAYS && DOWNLOAD = TRUE
		   //       CAPTION = HOVER & IE6 = TRUE 
                   } else if (($TELAALBUMS_SHOW_IMG_CAPTION == "ALWAYS") || (($TELAALBUMS_SHOW_IMG_CAPTION == "HOVER") && ($USING_IE_6 == "TRUE"))) {

			// ONLY WANT HEIGHT IF NON-CROPPED THUMBNAILS
                        global $out;
						$out .= " <div class='telaalbums_thumbnail' style='width:" . $TZ10 . "px; ";

			if ($TELAALBUMS_CROP_THUMBNAILS == "0") {
				global $out;
				$out .= "height: " . $TZ30 . "px; ";
			}
global $out;
			$out .= "text-align: center; padding-bottom: 10px;'>\n";
                        $caption_link_tweak = telaalbums_setupCaption($caption,$count);
                        $out .= " <a $caption_link_tweak href='$href'><img class='telaalbums_img' src='$thumb' alt='$caption' /></a>\n";
                        $out .= "  <span class='short_caption2'>$short_caption</span>\n";

			// Show Download Icon
                	if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == "1") {
                        	global $out;
							$out .= telaalbums_buildDownloadDiv($filename,$orig_href,"float: right; padding-top: 3px;");
                	}
			
			$out .= "</div>\n";

		   // CASE CUSTOM STYLE
                   } else if ($TELAALBUMS_SHOW_IMG_CAPTION == "CUSTOM") {
                        global $out;
						$out .= "<div class='telaalbums_thumbnail'>\n";
                                $out .= "\t<a class='telaalbums_imglink' href='$href'><img class='telaalbums_img' src='$thumb' alt='$caption'></a>\n";
                                $out .= "\t<div class='telaalbums_caption'><p class='telaalbums_captext'>$short_caption</p>\n";

                        // Show Download Icon
                        if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == "1") {
                                global $out;
								$out .= telaalbums_buildDownloadDiv($filename,$orig_href);
                        }

                        $out .= "</div></div>";

		   } else {
global $out;
                        $out .= "<p class='blocPhoto' style='width: " . $TZ10 . "px; height: " . $TZ20 . "px;'>";

                        if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == "1") {
                                global $out;
								$out .= "<a class='dl_link' rel='nobox' href='$orig_href' title='Download $filename'><img border='0' src='" . plugins_url("/attributes/images/disk_bw.png", dirname(__FILE__)) . "' alt='Save' /></a>";
                        }

			$caption_link_tweak = telaalbums_setupCaption($caption,$count);
                        global $out;
						$out .= "<a style=\"width: " . $TZP2 . "px; height: " . $TZP2 . "px;\" class='photo'$caption_link_tweak href='$href'>";
                        global $out;
						$out .= "<span class='border' style='width: " . $TELAALBUMS_GALLERY_THUMBSIZE . "px; height: " . $TELAALBUMS_GALLERY_THUMBSIZE . "px;'><img src='$thumb' />";
			if ($TELAALBUMS_SHOW_IMG_CAPTION != "NEVER") {
                        	global $out;
							$out .= "<span class='title' style='width: " . $TELAALBUMS_GALLERY_THUMBSIZE . "px;'><span>$short_caption</span></span>";
			}global $out;
                        $out .= "</span></a>";
                        $out .= "</p>";

                   }
		}

                //----------------------------------
                // Reset the variables
                //----------------------------------
                unset($thumb);
                unset($picasa_title);
                unset($href);
                unset($path);
                unset($url);
		unset($text);
		unset($gphotoid);
        }
}

	//----------------------------------------------------------------------------
	// Show output for pagination
	//----------------------------------------------------------------------------
	global $telaalbums_result_count;
	unset($picasa_title);

	//if ($TELAALBUMS_STANDALONE_MODE == "TRUE") {
	//
	//}
	global $out;
	$out .= "<div style='clear: both'></div>"; // Ensure Telaaedifex's Albums doesn't break theme layout
	return($out);
	unset($out);

} else {

//====================================================================




//Added isset because of undefined error
if (isset($overrides_array["images_per_page"])) { $TELAALBUMS_IMAGES_PER_PAGE = $overrides_array["images_per_page"];}
if (isset($overrides_array["image_size"])) { $TELAALBUMS_IMG_MAX = $overrides_array["image_size"];}
if (isset($overrides_array["thumbnail_size"])) { $TELAALBUMS_GALLERY_THUMBSIZE = $overrides_array["thumbnail_size"];}
if (isset($overrides_array["picasaweb_user"])) { $TELAALBUMS_GOOGLE_USER = $overrides_array["picasaweb_user"];}

// Added to support format adjustments when using wptouch, need to check if wptouch is enabled first
//global $wptouch_plugin;

//Replaced with WP_IS_MOBILE becuase of issue with wptouch.

if ( wp_is_mobile() == "TRUE") {

	$TELAALBUMS_SHOW_ALBUM_DETAILS = "0";
	$TELAALBUMS_PERMIT_IMG_DOWNLOAD = "0";
	$TELAALBUMS_SHOW_IMG_CAPTION = "NEVER";

}

//-----------------------------------------------------------------------------------------
// Load Language File
//-----------------------------------------------------------------------------------------
		$TELAALBUMS_SITE_LANGUAGE          = get_option("telaalbums_language");

//----------------------------------------------------------------------------
// VARIABLES 
//----------------------------------------------------------------------------
global $TZM30, $TZM10;
$TZ10 = $TELAALBUMS_GALLERY_THUMBSIZE + round($TELAALBUMS_GALLERY_THUMBSIZE * .06);
$TZ20 = $TELAALBUMS_GALLERY_THUMBSIZE + round($TELAALBUMS_GALLERY_THUMBSIZE * .15);
$TZ30 = $TELAALBUMS_GALLERY_THUMBSIZE + round($TELAALBUMS_GALLERY_THUMBSIZE * .25);
$TZM10 = $TELAALBUMS_GALLERY_THUMBSIZE - round($TELAALBUMS_GALLERY_THUMBSIZE * .06);
$TZM20 = $TELAALBUMS_GALLERY_THUMBSIZE - round($TELAALBUMS_GALLERY_THUMBSIZE * .09);
$TZM30 = $TELAALBUMS_GALLERY_THUMBSIZE - round($TELAALBUMS_GALLERY_THUMBSIZE * .22);
$TZM2 = $TELAALBUMS_GALLERY_THUMBSIZE - 2;
$TZP10 = $TELAALBUMS_GALLERY_THUMBSIZE + 10;
$TZP2  = $TELAALBUMS_GALLERY_THUMBSIZE + 2;
$image_count=0;
$picasa_title="NULL";
$count=0;
$OPEN=0;
$TRUNCATE_FROM = $TELAALBUMS_CAPTION_LENGTH;       // Should be around 22, depending on font and thumbsize
$TRUNCATE_TO   = $TELAALBUMS_CAPTION_LENGTH - 3;   // Should be $TRUNCATE_FROM minus 3
$uri = $_SERVER["REQUEST_URI"];
$useragent = $_SERVER['HTTP_USER_AGENT']; // Check useragent to suppress hover for IE6
if(strchr($useragent,"MSIE 6.0")) { $USING_IE_6 = "TRUE"; }
$gphotoid="1234678";

//----------------------------------------------------------------------------
// Check Permalink Structure 
//----------------------------------------------------------------------------
if ( get_option('permalink_structure') != '' ) { 
	// permalinks enabled
	list($back_link,$uri_tail) = explode('?',$uri);
	$urlchar = '?';
        $splitchar = '\?';
} else {
	list($back_link,$uri_tail) = explode('&',$uri);
	$urlchar = '&';
        $splitchar = $urlchar;
}

//----------------------------------------------------------------------------
// Grab album data from URL
//----------------------------------------------------------------------------
// Reformat the album title for display
list($ALBUM_TITLE,$tags) = array_pad(explode('_',$ALBUM, 2), 2, null);

//----------------------------------------------------------------------------
// Check for required variables from config file
//----------------------------------------------------------------------------
if (!isset($TELAALBUMS_OAUTH_TOKEN, $TELAALBUMS_GOOGLE_USER, $TELAALBUMS_IMG_MAX, $TELAALBUMS_GALLERY_THUMBSIZE, $TELAALBUMS_REQUIRE_FILTER, $TELAALBUMS_STANDALONE_MODE, $TELAALBUMS_IMAGES_PER_PAGE)) {

	echo "<h1>" . $LANG_MISSING_VAR_H1 . "</h1><h3>" . $LANG_MISSING_VAR_H3 . "</h3>";
	exit;
}

$meta_tag = "";

//----------------------------------------------------------------------------
// VARIABLES FOR PAGINATION
//----------------------------------------------------------------------------
if ($IN_POST == "TRUE") {
        $TELAALBUMS_IMAGES_PER_PAGE = 0;
} else if ($IN_POST == "SLIDESHOW") {
	$TELAALBUMS_IMG_MAX = "d";	
	$TELAALBUMS_SHOW_IMG_CAPTION = "SLIDESHOW";
}

if ($TELAALBUMS_CROP_THUMBNAILS == "1") { $CROP_CHAR = "c"; }
else { $CROP_CHAR = "u"; }


$file = "https://picasaweb.google.com/data/feed/api/user/" . $TELAALBUMS_GOOGLE_USER;

if ($ALBUM != "NULL") { $file .= "/album/" . $ALBUM; }

$file.= "?kind=photo&thumbsize=" . $TELAALBUMS_GALLERY_THUMBSIZE . $CROP_CHAR . "&imgmax=" . $TELAALBUMS_IMG_MAX;	

if ($TAG != "NULL") { $file .= "&tag=$TAG"; }


if ($TELAALBUMS_IMAGES_PER_PAGE != 0) {
	
	if (!isset($_GET['pg'])){$page = 1;}else{$page = $_GET['pg'];}
	if (!(isset($page))) {
		$page = 1;
	}
	if ($page > 1) {
		$start_image_index = (($page - 1) * $TELAALBUMS_IMAGES_PER_PAGE) + 1;
	} else {
		$start_image_index = 1;
	}

	$file .= "&max-results=" . $TELAALBUMS_IMAGES_PER_PAGE . "&start-index=" . $start_image_index;

}
	
	$vals = telaalbums_doCurlExec($file); 


// Iterate over the array and extract the info we want
//----------------------------------------------------------------------------
unset($thumb);
unset($title);
unset($href);
unset($path);
unset($url);
unset($gphotoid);

foreach ($vals as $val) {

        if ($OPEN != 1) {
	   switch ($val["tag"]) {
		   
		case "ENTRY":
                     if ($val["type"] == "open") {
                         $OPEN=1;
                     }
                     break;

		case "TITLE":
                     if ($picasa_title == "NULL") {
                         $picasa_title = $val["value"];
                     }
					break;
		 case "GPHOTO:NUMPHOTOS":
                     // Fix for Issue 12
					 global $numphotos; // Fixes undefined
                     if (!is_numeric($numphotos)) {
                         $numphotos = $val["value"];
                     }
                     break;

		 case "GPHOTO:ID":
                     $albumid = $val["value"];
                     break;

		 case "OPENSEARCH:TOTALRESULTS":
		 			global $telaalbums_result_count;
                     $telaalbums_result_count = $val["value"];
                     break;
	   }

        } else {
           switch ($val["tag"]) {

                        case "ENTRY":
                                if ($val["type"] == "close") {
                                        $OPEN=0;
                                }
                                break;
                        case "MEDIA:THUMBNAIL":
    				$tnht = $val["attributes"]["HEIGHT"];
    				$tnwd = $val["attributes"]["WIDTH"];
    				// Temporary? fix for google api bug 2011-05-28
    				if (($tnht == $TELAALBUMS_GALLERY_THUMBSIZE) || ($tnwd == $TELAALBUMS_GALLERY_THUMBSIZE)) {
        				$thumb = trim($val["attributes"]["URL"] . "\n");
    				}
    				break;
                        case "MEDIA:CONTENT":
                                $href = $val["attributes"]["URL"];
								$orig_href = str_replace("s$TELAALBUMS_IMG_MAX","d",$href);
								$filename = basename($href);
                                $imght = $val["attributes"]["HEIGHT"];
                                $imgwd = $val["attributes"]["WIDTH"];
                                break;
                        case "SUMMARY":
						if(isset($val["value"])){
							global $text;
                                $text = $val["value"];
						}
								
                                break;
                        case "GPHOTO:ID":
                                //if (!isset($gphotoid)) {
                                        $gphotoid = trim($val["value"]);
                                //}
                                break;
	   }
        }
		


        //----------------------------------------------------------------------------
        // Once we have all the pieces of info we want, dump the output
        //----------------------------------------------------------------------------
        if (isset($thumb) && isset($href) &&  isset($gphotoid)) {

		$add_s = "";

		if (function_exists("telaalbums_cache_thumb")) {
			$thumb = telaalbums_cache_thumb($thumb);
		}
		
		// Grab the album title once
			if (!isset($STOP_FLAG)){
				global $STOP_FLAG;
				$STOP_FLAG=0;
			} 
            if (isset($STOP_FLAG)){
				
				 if ($STOP_FLAG != 1) {
					list($AT,$tags) = array_pad(explode('_',$picasa_title, 2), 2, null);
					$AT = str_replace("\"", "", $AT);
                        $AT = str_replace("'", "",$AT);
			if (($IN_POST != "TRUE") && ($IN_POST != "SLIDESHOW")) {
				if (($TAG == "") || ($TAG == "NULL")) {
						global $out;
                                	$out .= "<div id='title'><h2>$AT</h2>";
				} else {
					global $out;
					$out .= "<div id='title'><h2>Photos tagged '$TAG'</h2>";
				}
				global $out;
				$out .= "<span><a class='back_to_list' href='" . $back_link . "'>...Back to Albums</a></span></div>\n";
                        }
						global $STOP_FLAG;
                        $STOP_FLAG=1;
                }
			}
		
		// Set image caption
				global $text;
                if ($text != "") {
                        $caption = htmlentities( $text , ENT_QUOTES );
                } else {
                        $caption = $AT . " - " . $filename;
                }
		// Keep count of images
                $count++;

		// Shorten caption as necessary
                if ((strlen($caption) > $TRUNCATE_FROM) && ($TELAALBUMS_TRUNCATE_ALBUM_NAME == "1")) {
                        $short_caption = substr($caption,0,$TRUNCATE_TO) . "...";
			if (strlen($short_caption) > $TRUNCATE_FROM) { 
				$short_caption = substr($filename,0,$TRUNCATE_FROM);
			}
                } else {
			$short_caption = $caption;
		}

		// Hide Videos
                $vidpos = stripos($href, "googlevideo");

		if (($vidpos == "") || ($TELAALBUMS_HIDE_VIDEO == "0")) {

		   if ($TELAALBUMS_SHOW_IMG_CAPTION == "SLIDESHOW") {

                        echo "<img src='" . $href . "' width='" . $imgwd . "' height='" . $imght . "' />\n";

		   // CASE: CAPTION = HOVER & IE6 = FALSE
                   } else if (($TELAALBUMS_SHOW_IMG_CAPTION == "HOVER") && ($USING_IE_6 != "TRUE")){

			// ONLY WANT HEIGHT IF NON-CROPPED THUMBNAILS
			global $out;
			$out .= "<div class='telaalbums_thumbnail' style='width: " . $TZ10 . "px; ";

			if ($TELAALBUMS_CROP_THUMBNAILS == "0") {
                                global $out;
								$out .= "height: " . $TZ30 . "px; ";
                        }
global $out;
			$out .= "text-align: center;'>\n";
                        $caption_link_tweak = telaalbums_setupCaption($caption,$count);
						$out .= " <a $caption_link_tweak href='$href'><img class='telaalbums_img' src='$thumb' alt='$caption' /></a>\n";
                        $out .= " <div id='options' style='width:" . $TZ10 . "px;'>\n";
                        $out .= "  <span class='short_caption'>$short_caption</a></span>\n";

                        // Show Download Icon
                        if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == "1") {
							global $out;
				$out .= telaalbums_buildDownloadDiv($filename,$orig_href,"margin-left: " . $TZM20 . "px; padding-top: 3px;");
                       	} 
						global $out;
                        $out .= "</div>\n";
                        $out .= "</div>";

		   // CASE: CAPTION = ALWAYS && DOWNLOAD = TRUE
		   //       CAPTION = HOVER & IE6 = TRUE 
                   } else if (($TELAALBUMS_SHOW_IMG_CAPTION == "ALWAYS") || (($TELAALBUMS_SHOW_IMG_CAPTION == "HOVER") && ($USING_IE_6 == "TRUE"))) {

			// ONLY WANT HEIGHT IF NON-CROPPED THUMBNAILS
                        global $out;
						$out .= " <div class='telaalbums_thumbnail' style='width:" . $TZ10 . "px; ";

			if ($TELAALBUMS_CROP_THUMBNAILS == "0") {
				global $out;
				$out .= "height: " . $TZ30 . "px; ";
			}
global $out;
			$out .= "text-align: center; padding-bottom: 10px;'>\n";
                        $caption_link_tweak = telaalbums_setupCaption($caption,$count);
                        $out .= " <a $caption_link_tweak href='$href'><img class='telaalbums_img' src='$thumb' alt='$caption' /></a>\n";
                        $out .= "  <span class='short_caption2'>$short_caption</span>\n";

			// Show Download Icon
                	if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == "1") {
                        	global $out;
							$out .= telaalbums_buildDownloadDiv($filename,$orig_href,"float: right; padding-top: 3px;");
                	}
			
			$out .= "</div>\n";

		   // CASE CUSTOM STYLE
                   } else if ($TELAALBUMS_SHOW_IMG_CAPTION == "CUSTOM") {
                        global $out;
						$out .= "<div class='telaalbums_thumbnail'>\n";
                                $out .= "\t<a class='telaalbums_imglink' href='$href'><img class='telaalbums_img' src='$thumb' alt='$caption'></a>\n";
                                $out .= "\t<div class='telaalbums_caption'><p class='telaalbums_captext'>$short_caption</p>\n";

                        // Show Download Icon
                        if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == "1") {
                                global $out;
								$out .= telaalbums_buildDownloadDiv($filename,$orig_href);
                        }

                        $out .= "</div></div>";

		   } else {
global $out;
                        $out .= "<p class='blocPhoto' style='width: " . $TZ10 . "px; height: " . $TZ20 . "px;'>";

                        if ($TELAALBUMS_PERMIT_IMG_DOWNLOAD == "1") {
                                global $out;
								$out .= "<a class='dl_link' rel='nobox' href='$orig_href' title='Download $filename'><img border='0' src='" . plugins_url("/attributes/images/disk_bw.png", dirname(__FILE__)) . "' alt='Save' /></a>";
                        }

			$caption_link_tweak = telaalbums_setupCaption($caption,$count);
                        global $out;
						$out .= "<a style=\"width: " . $TZP2 . "px; height: " . $TZP2 . "px;\" class='photo'$caption_link_tweak href='$href'>";
                        global $out;
						$out .= "<span class='border' style='width: " . $TELAALBUMS_GALLERY_THUMBSIZE . "px; height: " . $TELAALBUMS_GALLERY_THUMBSIZE . "px;'><img src='$thumb' />";
			if ($TELAALBUMS_SHOW_IMG_CAPTION != "NEVER") {
                        	global $out;
							$out .= "<span class='title' style='width: " . $TELAALBUMS_GALLERY_THUMBSIZE . "px;'><span>$short_caption</span></span>";
			}global $out;
                        $out .= "</span></a>";
                        $out .= "</p>";

                   }
		}

                //----------------------------------
                // Reset the variables
                //----------------------------------
                unset($thumb);
                unset($picasa_title);
                unset($href);
                unset($path);
                unset($url);
		unset($text);
		unset($gphotoid);
        }
}

	//----------------------------------------------------------------------------
	// Show output for pagination
	//----------------------------------------------------------------------------
	global $telaalbums_result_count;
	if (($TELAALBUMS_IMAGES_PER_PAGE != 0) && ($telaalbums_result_count > $TELAALBUMS_IMAGES_PER_PAGE)){

		global $out;
		$out .= "<div id='pages'>";
		$paginate = ($telaalbums_result_count/$TELAALBUMS_IMAGES_PER_PAGE) + 1;
		$out .= "PAGE: ";

		// List pages
		for ($i=1; $i<$paginate; $i++) {

		   $link_image_index=($i - 1) * ($TELAALBUMS_IMAGES_PER_PAGE + 1);
		
		   $uri = $_SERVER["REQUEST_URI"];
		   list($uri,$tail) = explode($urlchar,$_SERVER['REQUEST_URI']);
		   $href = $uri . $urlchar . "album=$ALBUM&pg=$i";
		   

		  // Show current page
		  if ($i == $page) {
			global $out;
			$out .= "<strong>$i </strong>";
		   } else {
			global $out;
			$out .= "<a class='page_link' href='$href'>$i</a> ";
		   }
		}

		global $out;
		$out .= "</div>";

	}

	unset($picasa_title);

	//if ($TELAALBUMS_STANDALONE_MODE == "TRUE") {
	//
	//}
	global $out;
	$out .= "<div style='clear: both'></div>"; // Ensure Telaaedifex's Albums doesn't break theme layout
	return($out);
	unset($out);

}
}
?>