<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once dirname(__FILE__) . '/globals.php';
// For non-PHP5 users
if (!function_exists("stripos")) {
   function stripos($str,$needle,$offset=0) {
     return strpos(strtolower($str),strtolower($needle),$offset);
    }
}

function telaalbums_SlideShow($slideshow,$IN_POST = null,$TAG,$overrides_array) {
require (dirname(__FILE__) . '/includes/telaalbums_options.php');

if (isset($overrides_array["slideshow"])) { $slideshow = $overrides_array["slideshow"];}
# ---------------------------------------------------------------------------
# Refresh the oauth2 token if it has expired
# ---------------------------------------------------------------------------
if (($TELAALBUMS_now > $TELAALBUMS_TOKEN_EXPIRES) && ($TELAALBUMS_PUBLIC_ONLY == '0')) {
        #echo "Time to refresh...";
        telaalbums_refreshOAuth2Token(); # do the refresh
        $TELAALBUMS_OAUTH_TOKEN = get_option("telaalbums_oauth_token"); # get the token again
}


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

#----------------------------------------------------------------------------
# Pagination for Album list
#----------------------------------------------------------------------------

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
						
						$pos = 0;
		
		if ($pos == 0 && strpos($slideshow,$title) !== false) {

                        list($disp_name,$tags) = array_pad(explode('_',$title, 2), 2, null);
						global $ALBUM;
						unset($ALBUM);
						$ALBUM = $picasa_name;

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
                                	$out .= "<div id='title'><h2>$AT</h2></div>";
				} else {
					global $out;
					$out .= "<div id='title'><h2>Photos tagged '$TAG'</h2></div>";
				}
				global $out;
                        }
						global $STOP_FLAG;
                        $STOP_FLAG=1;
						function telaalbums_slidesshow_atts(){
							?>
							<script src="<? echo plugin_dir_url(__FILE__).'attributes/slideshows/js/jssor.slider-23.1.5.min.js'?>" type='text/javascript'></script>
							<script type='text/javascript'>
								jssor_1_slider_init = function() {

									var jssor_1_SlideshowTransitions = [
									  {$Duration:1200,$Zoom:11,$Rotate:-1,$Easing:{$Zoom:$Jease$.$InQuad,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Round:{$Rotate:0.5},$Brother:{$Duration:1200,$Zoom:1,$Rotate:1,$Easing:$Jease$.$Swing,$Opacity:2,$Round:{$Rotate:0.5},$Shift:90}},
									  {$Duration:1400,x:0.25,$Zoom:1.5,$Easing:{$Left:$Jease$.$InWave,$Zoom:$Jease$.$InSine},$Opacity:2,$ZIndex:-10,$Brother:{$Duration:1400,x:-0.25,$Zoom:1.5,$Easing:{$Left:$Jease$.$InWave,$Zoom:$Jease$.$InSine},$Opacity:2,$ZIndex:-10}},
									  {$Duration:1200,$Zoom:11,$Rotate:1,$Easing:{$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Round:{$Rotate:1},$ZIndex:-10,$Brother:{$Duration:1200,$Zoom:11,$Rotate:-1,$Easing:{$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Round:{$Rotate:1},$ZIndex:-10,$Shift:600}},
									  {$Duration:1500,x:0.5,$Cols:2,$ChessMode:{$Column:3},$Easing:{$Left:$Jease$.$InOutCubic},$Opacity:2,$Brother:{$Duration:1500,$Opacity:2}},
									  {$Duration:1500,x:-0.3,y:0.5,$Zoom:1,$Rotate:0.1,$During:{$Left:[0.6,0.4],$Top:[0.6,0.4],$Rotate:[0.6,0.4],$Zoom:[0.6,0.4]},$Easing:{$Left:$Jease$.$InQuad,$Top:$Jease$.$InQuad,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Brother:{$Duration:1000,$Zoom:11,$Rotate:-0.5,$Easing:{$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Shift:200}},
									  {$Duration:1500,$Zoom:11,$Rotate:0.5,$During:{$Left:[0.4,0.6],$Top:[0.4,0.6],$Rotate:[0.4,0.6],$Zoom:[0.4,0.6]},$Easing:{$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Brother:{$Duration:1000,$Zoom:1,$Rotate:-0.5,$Easing:{$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Shift:200}},
									  {$Duration:1500,x:0.3,$During:{$Left:[0.6,0.4]},$Easing:{$Left:$Jease$.$InQuad,$Opacity:$Jease$.$Linear},$Opacity:2,$Outside:true,$Brother:{$Duration:1000,x:-0.3,$Easing:{$Left:$Jease$.$InQuad,$Opacity:$Jease$.$Linear},$Opacity:2}},
									  {$Duration:1200,x:0.25,y:0.5,$Rotate:-0.1,$Easing:{$Left:$Jease$.$InQuad,$Top:$Jease$.$InQuad,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Brother:{$Duration:1200,x:-0.1,y:-0.7,$Rotate:0.1,$Easing:{$Left:$Jease$.$InQuad,$Top:$Jease$.$InQuad,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2}},
									  {$Duration:1600,x:1,$Rows:2,$ChessMode:{$Row:3},$Easing:{$Left:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$Brother:{$Duration:1600,x:-1,$Rows:2,$ChessMode:{$Row:3},$Easing:{$Left:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2}},
									  {$Duration:1600,x:1,$Rows:2,$ChessMode:{$Row:3},$Easing:{$Left:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$Brother:{$Duration:1600,x:-1,$Rows:2,$ChessMode:{$Row:3},$Easing:{$Left:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2}},
									  {$Duration:1600,y:-1,$Cols:2,$ChessMode:{$Column:12},$Easing:{$Top:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$Brother:{$Duration:1600,y:1,$Cols:2,$ChessMode:{$Column:12},$Easing:{$Top:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2}},
									  {$Duration:1200,y:1,$Easing:{$Top:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$Brother:{$Duration:1200,y:-1,$Easing:{$Top:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2}},
									  {$Duration:1200,x:1,$Easing:{$Left:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$Brother:{$Duration:1200,x:-1,$Easing:{$Left:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2}},
									  {$Duration:1200,y:-1,$Easing:{$Top:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$ZIndex:-10,$Brother:{$Duration:1200,y:-1,$Easing:{$Top:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$ZIndex:-10,$Shift:-100}},
									  {$Duration:1200,x:1,$Delay:40,$Cols:6,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Easing:{$Left:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$ZIndex:-10,$Brother:{$Duration:1200,x:1,$Delay:40,$Cols:6,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Easing:{$Top:$Jease$.$InOutQuart,$Opacity:$Jease$.$Linear},$Opacity:2,$ZIndex:-10,$Shift:-100}},
									  {$Duration:1500,x:-0.1,y:-0.7,$Rotate:0.1,$During:{$Left:[0.6,0.4],$Top:[0.6,0.4],$Rotate:[0.6,0.4]},$Easing:{$Left:$Jease$.$InQuad,$Top:$Jease$.$InQuad,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2,$Brother:{$Duration:1000,x:0.2,y:0.5,$Rotate:-0.1,$Easing:{$Left:$Jease$.$InQuad,$Top:$Jease$.$InQuad,$Opacity:$Jease$.$Linear,$Rotate:$Jease$.$InQuad},$Opacity:2}},
									  {$Duration:1600,x:-0.2,$Delay:40,$Cols:12,$During:{$Left:[0.4,0.6]},$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Assembly:260,$Easing:{$Left:$Jease$.$InOutExpo,$Opacity:$Jease$.$InOutQuad},$Opacity:2,$Outside:true,$Round:{$Top:0.5},$Brother:{$Duration:1000,x:0.2,$Delay:40,$Cols:12,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Assembly:1028,$Easing:{$Left:$Jease$.$InOutExpo,$Opacity:$Jease$.$InOutQuad},$Opacity:2,$Round:{$Top:0.5}}}
									];

									var jssor_1_options = {
									  $AutoPlay: 1,
									  $FillMode: 5,
									  $SlideshowOptions: {
										$Class: $JssorSlideshowRunner$,
										$Transitions: jssor_1_SlideshowTransitions,
										$TransitionsOrder: 1
									  },
									  $ArrowNavigatorOptions: {
										$Class: $JssorArrowNavigator$
									  },
									  $BulletNavigatorOptions: {
										$Class: $JssorBulletNavigator$
									  }
									};

									var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

									/*responsive code begin*/
									/*remove responsive code if you dont want the slider scales while window resizing*/
									function ScaleSlider() {
										var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
										if (refSize) {
											refSize = Math.min(refSize, 600);
											jssor_1_slider.$ScaleWidth(refSize);
										}
										else {
											window.setTimeout(ScaleSlider, 30);
										}
									}
									ScaleSlider();
									$Jssor$.$AddEvent(window, "load", ScaleSlider);
									$Jssor$.$AddEvent(window, "resize", ScaleSlider);
									$Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
									/*responsive code end*/
								};
							</script>
							<style>
								/* jssor slider bullet navigator skin 13 css */
								/*
								.jssorb13 div           (normal)
								.jssorb13 div:hover     (normal mouseover)
								.jssorb13 .av           (active)
								.jssorb13 .av:hover     (active mouseover)
								.jssorb13 .dn           (mousedown)
								*/
								.jssorb13 {
									position: absolute;
								}
								.jssorb13 div, .jssorb13 div:hover, .jssorb13 .av {
									position: absolute;
									/* size of bullet elment */
									width: 21px;
									height: 21px;
									background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 21 21' width='21px' height='21px'><path d='M 6, 12m 0, 0a 6,6 0 1,0 12,0a 6,6 0 1,0 -12,0' stroke='white' stroke-width='1' fill='#373737'/></svg>") no-repeat;
									overflow: hidden;
									cursor: pointer;
									z-index: auto;
								}
								.jssorb13 div { background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 21 21' width='21px' height='21px'><path d='M 6, 12m 0, 0a 6,6 0 1,0 12,0a 6,6 0 1,0 -12,0' stroke='white' stroke-width='1' fill='#373737'/></svg>") no-repeat; }
								
								.jssorb13 div:hover, .jssorb13 .av:hover { background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 21 21' width='21px' height='21px'><path d='M 6, 12m 0, 0a 6,6 0 1,0 12,0a 6,6 0 1,0 -12,0' stroke='white' stroke-width='1' fill='#8c8c8c'/></svg>") no-repeat; }
								
								.jssorb13 .av { background: url("data:image/svg+xml;charset=utf-8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 21 21' width='21px' height='21px'><path d='M 6, 12m 0, 0a 6,6 0 1,0 12,0a 6,6 0 1,0 -12,0' stroke='white' stroke-width='1' fill='#373737'/></svg>") no-repeat; }
								.jssorb13 .dn, .jssorb13 .dn:hover {}

								/* jssor slider arrow navigator skin 06 css */
								/*
								.jssora06l                  (normal)
								.jssora06r                  (normal)
								.jssora06l:hover            (normal mouseover)
								.jssora06r:hover            (normal mouseover)
								.jssora06l.jssora06ldn      (mousedown)
								.jssora06r.jssora06rdn      (mousedown)
								.jssora06l.jssora06lds      (disabled)
								.jssora06r.jssora06rds      (disabled)
								*/
								.jssora06l, .jssora06r {
									display: block;
									position: absolute;
									/* size of arrow element */
									width: 45px;
									height: 45px;
									cursor: pointer;
									background: url('<? echo plugin_dir_url(__FILE__)."attributes/slideshows/img/a06.png"?>') no-repeat;
									overflow: hidden;
								}
								.jssora06l { background-position: -8px -38px; }
								.jssora06r { background-position: -68px -38px; }
								.jssora06l:hover { background-position: -128px -38px; }
								.jssora06r:hover { background-position: -188px -38px; }
								.jssora06l.jssora06ldn { background-position: -248px -38px; }
								.jssora06r.jssora06rdn { background-position: -308px -38px; }
								.jssora06l.jssora06lds { background-position: -8px -38px; opacity: .3; pointer-events: none; }
								.jssora06r.jssora06rds { background-position: -68px -38px; opacity: .3; pointer-events: none; }
							</style>
							<?
						}
					 	$out .= telaalbums_slidesshow_atts();
						//$out .= "<div id='telaalbums_slideshow_container'>";
						$out .= "<div id='jssor_1' style='position:relative;margin:0 auto;top:0px;left:0px;width:600px;height:500px;overflow:hidden;visibility:hidden;'>
						<div data-u='slides' style='cursor:default;position:relative;top:0px;left:0px;width:600px;height:500px;overflow:hidden;'>";
						//$out .= "<figure>";
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
			
			
			$out .= "<div style='background-color:#000000;'><img data-u='image' src='" . $href . "' width='" . $imgwd . "' height='" . $imght . "' /></div>";
			//$out .= "<figure><img src='" . $href . "' width='" . $imgwd . "' height='" . $imght . "' /></figure>";
			//<figcaption>Caption goes here</figcaption>
			//$out .= "<li><img src='" . $href . "' width='" . $imgwd . "' height='" . $imght . "' /></li>";
			
			
			//\n
			//$out .= "<li><img src='" . $href . "'/></li>";//\n
			
			
		}// End if No Video

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

							$out .= "</div>";// End Container
							
							$out .= " 
								<!-- Bullet Navigator -->
								<div data-u='navigator' class='jssorb13' style='bottom:16px;right:16px;' data-autocenter='1'>
									<!-- bullet navigator item prototype -->
									<div data-u='prototype' style='width:21px;height:21px;'></div>
								</div>
								<!-- Arrow Navigator -->
								<span data-u='arrowleft' class='jssora06l' style='top:0px;left:8px;width:45px;height:45px;' data-autocenter='2'></span>
								<span data-u='arrowright' class='jssora06r' style='top:0px;right:8px;width:45px;height:45px;' data-autocenter='2'></span>
								</div>
								<script type='text/javascript'>jssor_1_slider_init();</script>
								";		
	global $out;
	$out .= "<div style='clear: both'></div>"; // Ensure Telaaedifex's Albums doesn't break theme layout
	return($out);
	unset($out);
}//Function End
?>