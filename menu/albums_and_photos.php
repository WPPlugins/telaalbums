<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//add_action( 'admin_menu', 'telaalbums_add_admin_menu' );
add_action( 'admin_init', 'telaalbums_albums_and_photos_settings_init' );


function telaalbums_albums_and_photos_settings_init(  ) { 

	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_album_details' );
	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_albums_per_page' );
	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_album_thumbsize' );
	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_crop_thumbs' );
	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_show_caption' );
	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_images_per_page' );
	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_image_size' );
	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_permit_download' );
	register_setting( 'telaalbums_albums_and_photos_section', 'telaalbums_thumbnail_size' );
	

	add_settings_section(
		'telaalbums_albums_and_photos_section', 
		__( 'Your section description', 'telaalbums' ), 
		'telaalbums_albums_and_photos_settings_section_callback', 
		'telaalbums_albums_and_photos_section'
	);

	add_settings_field( 
		'telaalbums_album_details', 
		__( 'Album Details', 'telaalbums' ), 
		'telaalbums_album_details_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);

	add_settings_field( 
		'telaalbums_albums_per_page', 
		__( 'Albums Per Page', 'telaalbums' ), 
		'telaalbums_albums_per_page_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);
	
	add_settings_field( 
		'telaalbums_album_thumbsize', 
		__( 'Album Thumbnail Size', 'telaalbums' ), 
		'telaalbums_album_thumbsize_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);
	
	add_settings_field( 
		'telaalbums_crop_thumbs', 
		__( 'Crop Thumbnails', 'telaalbums' ), 
		'telaalbums_crop_thumbs_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);
	
	add_settings_field( 
		'telaalbums_show_caption', 
		__( 'Display Style', 'telaalbums' ), 
		'telaalbums_show_caption_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);

	add_settings_field( 
		'telaalbums_images_per_page', 
		__( 'Images Per Page', 'telaalbums' ), 
		'telaalbums_images_per_page_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);
	
	
	add_settings_field( 
		'telaalbums_image_size', 
		__( 'Image Size', 'telaalbums' ), 
		'telaalbums_image_size_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);

	add_settings_field( 
		'telaalbums_permit_download', 
		__( 'Permit Download?', 'telaalbums' ), 
		'telaalbums_permit_download_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);

		add_settings_field( 
		'telaalbums_thumbnail_size', 
		__( 'Photo Thumbnail Size', 'telaalbums' ), 
		'telaalbums_thumbnail_size_render', 
		'telaalbums_albums_and_photos_section', 
		'telaalbums_albums_and_photos_section' 
	);


}



function telaalbums_album_details_render(  ) { 

	$options = get_option( 'telaalbums_album_details' );
	?>
		<select name='telaalbums_album_details'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
    <i>Overlay album thumbnail with description on mouse hover?</i>
	<?php

}

function telaalbums_albums_per_page_render(  ) { 

	$options = get_option( 'telaalbums_albums_per_page' );
	?>
	<input type='text' name='telaalbums_albums_per_page' value='<?php echo $options; ?>'>
    <i>Albums per page. Zero means don't paginate.</i>
	<?php

}

function telaalbums_album_thumbsize_render(  ) { 

	$options = get_option( 'telaalbums_album_thumbsize' );
	$thumb_sizes = array("480","360","240","200","160","150","144","104","72","64","48","32");
			
			echo "<select name='telaalbums_album_thumbsize'>";
			foreach ($thumb_sizes as $value) {

                if ($options != $value) {

                        echo "<option value='$value'>$value</option>";

                } else {

                        echo "<option value='$value' selected>$value</option>";

                }

        }echo "</select>";

}

function telaalbums_crop_thumbs_render(  ) { 

	$options = get_option( 'telaalbums_crop_thumbs' );
	?>
		<select name='telaalbums_crop_thumbs'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
        <i>Crop image thumbnails to square size or use actual ratio.</i>
	<?php

}

function telaalbums_show_caption_render(  ) { 

	$options = get_option( 'telaalbums_show_caption' );
	$available_styles = array('selected' => 'ALWAYS','HOVER','OVERLAY','SLIDESHOW');
		
		echo "<select name='telaalbums_show_caption'>";
		foreach ($available_styles as $value) {

                if ($options != $value) {

                        echo "<option value='$value'>$value</option>";

                } else {

                        echo "<option value='$value' selected>$value</option>";

                }echo "</select>";?><i>Set display style and placement of captions.</i>
<?
        }


}

function telaalbums_images_per_page_render(  ) { 

	$options = get_option( 'telaalbums_images_per_page' );
	?>
	<input type='text' name='telaalbums_images_per_page' value='<?php echo $options; ?>'>
    <i>Images per page. Zero means don't paginate.</i>
	<?php

}

function telaalbums_image_size_render(  ) { 

	$options = get_option( 'telaalbums_image_size' );
	$image_sizes = array("1600","1440","1280","1152","1024","912","800","720","640","576","512","400","320","288","200");
	echo "<select name='telaalbums_image_size'>";
		foreach ($image_sizes as $value) {

                if ($options != $value) {

                        echo "<option value='$value'>$value</option>";

                } else {

                        echo "<option value='$value' selected>$value</option>";

                }

        }echo "</select>";

}


function telaalbums_permit_download_render(  ) { 

	$options = get_option( 'telaalbums_permit_download' );
	?>
		<select name='telaalbums_permit_download'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
        <i>Determines whether the user can download the original full-size image.</i>
	<?php

}


function telaalbums_thumbnail_size_render(  ) { 

		$options = get_option( 'telaalbums_thumbnail_size' );
		$thumb_sizes = array("480","360","240","200","160","150","144","104","72","64","48","32");
				echo "<select name='telaalbums_thumbnail_size'>";
			 foreach ($thumb_sizes as $value) {

                if ($options != $value) {

                        echo "<option value='$value'>$value</option>";

                } else {

                        echo "<option value='$value' selected>$value</option>";

                }

        }echo "</select>";
			
}


//==============================


function telaalbums_albums_and_photos_settings_section_callback(  ) { 

	echo __( 'This section description', 'telaalbums' );

}



function telaalbums_albums_and_photos_settings_page(  ) { 
$nonce = wp_create_nonce( 'telaalbums_' );
	?>
	<form action='options.php' method='post'>

		<h2>Telaaedifex's Albums</h2>

		<?php
		settings_fields( 'telaalbums_albums_and_photos_section' );
		do_settings_sections( 'telaalbums_albums_and_photos_section' );
		if($nonce = $nonce){
			submit_button();
		}else{echo"Please refresh this page";}
		?>

	</form>
	<?php
unset($nonce);
}

?>