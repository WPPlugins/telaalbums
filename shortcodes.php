<?
// ----------------------------------------------------------------------------------------------------------
// Setup the shortcode
// ----------------------------------------------------------------------------------------------------------
if (!function_exists("telaalbums_shortcode")) {

function telaalbums_shortcode( $atts, $content = null ) {

	$overrides_array=array(); // Rob - Fixes undefined.

	//Shortcode options
	extract(shortcode_atts(array("album" => 'NULL'), $atts));
	extract(shortcode_atts(array("filter" => ''), $atts));
	extract(shortcode_atts(array("tag" => 'NULL'), $atts));

	extract(shortcode_atts(array("cover" => ''), $atts));
	extract(shortcode_atts(array("comments" => ''), $atts));
	extract(shortcode_atts(array("header" => 'NULL'), $atts));
	extract(shortcode_atts(array("hide_albums" => 'NULL'), $atts));

	extract(shortcode_atts(array("images_per_page" => 'NULL'), $atts));
	extract(shortcode_atts(array("image_size" => 'NULL'), $atts));
	extract(shortcode_atts(array("thumbnail_size" => 'NULL'), $atts));
	extract(shortcode_atts(array("picasaweb_user" => 'NULL'), $atts));

	extract(shortcode_atts(array("page_header" => 'NULL'), $atts));
	extract(shortcode_atts(array("random_photos" => 'NULL'), $atts));
	extract(shortcode_atts(array("show_n_albums" => 'NULL'), $atts));
	extract(shortcode_atts(array("slideshow" => 'NULL'), $atts));

	// Overrides handling
	if (($images_per_page != "") && ($images_per_page != "NULL"))
		$overrides_array["images_per_page"] = $images_per_page;
	if (($image_size) && ($image_size != "NULL"))
		$overrides_array["image_size"] = $image_size;
	if (($thumbnail_size != "") && ($thumbnail_size != "NULL")) // Rob 
		$overrides_array["thumbnail_size"] = $thumbnail_size;
	if (($picasaweb_user) && ($picasaweb_user != "NULL"))
			$overrides_array["picasaweb_user"] = $picasaweb_user;
	if (($hide_albums) && ($hide_albums != "NULL"))
                       $overrides_array["hide_albums"] = $hide_albums;
					   global $hide_albums;
	if (($page_header) && ($page_header != "NULL"))
			$overrides_array["page_header"] = $page_header;
	if (($show_n_albums) && ($show_n_albums != "NULL"))
			$overrides_array["show_n_albums"] = $show_n_albums;
	if (($random_photos) && ($random_photos != "NULL"))			//Rob
			$overrides_array["random_photos"] = $random_photos;	//
	if (($slideshow) && ($slideshow != "NULL"))			//Isaac
			$overrides_array["slideshow"] = $slideshow;	//
	if (($album) && ($album != "NULL"))			//Isaac
			$overrides_array["album"] = $album;	//
	




	//if ((isset($comments)) && ($comments != "")) {
//		if (function_exists("telaalbums_getRecentComments")) {
//			//$out = telaalbums_getRecentComments($comments);
//			$out = "<strong>Need To Remove!</strong>";
//		}
//		return($out);
//	
//	} else if ( ($cover == "1") && ((!isset($_GET["album"])) || (isset($album))) ){
//		
//		$out = "<strong>Need to replace</strong>";
//		return($out);
//
//	

if ($slideshow != "" && $slideshow != "NULL") {
	if (function_exists("telaalbums_SlideShow")){
		$out = telaalbums_SlideShow($slideshow,"0",$tag,$overrides_array);
	}
	return($out);
	
} else if (($album == "NULL") && (!isset($_GET["album"])) && ($random_photos == "NULL") && ($tag == "NULL") && ($slideshow = "NULL")) {
		
			$out = telaalbums_dumpAlbumList($filter,"0",$overrides_array);
                return($out);

	
	} else if ($random_photos != "NULL") {		
		$out = telaalbums_randomPhoto($overrides_array);	//
                return($out);				//

	} else {
		
		if ($album != "NULL") {
			
			if ($album == "random_photo") {
				
					$out = "<strong>Need to remove!</strong>";
					
			} else if ($album == "random_album") {	
					$out = telaalbums_dumpAlbumList("RANDOM");
			} else {
					$out = telaalbums_showAlbumContents($album,"1",$tag,$overrides_array);
			}
                } else if (isset($_GET["album"])) {
                        $album = $_GET["album"];
						
				$out = telaalbums_showAlbumContents($album,"0",$tag,$overrides_array);
				
				} else if ($tag != "NULL") {
		
				$out = telaalbums_showAlbumContents($album,"0",$tag,$overrides_array);
				
				}
		
		return($out);
        }
		global $ALBUMS_TO_HIDE;
	$ALBUMS_TO_HIDE = ($overrides_array['hide_albums']) ? explode(",",$overrides_array['hide_albums']) : array(); //added due to is_array error
	

} // end shortcode
} // end if exists
?>