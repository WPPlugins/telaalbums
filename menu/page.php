<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//add_action( 'admin_menu', 'telaalbums_add_admin_menu' );
add_action( 'admin_init', 'telaalbums_page_settings_init' );


function telaalbums_page_settings_init(  ) { 

	register_setting( 'telaalbums_page_section', 'telaalbums_albpage_desc' );
	register_setting( 'telaalbums_page_section', 'telaalbums_show_n_albums' );
	register_setting( 'telaalbums_page_section', 'telaalbums_images_on_front' );
	register_setting( 'telaalbums_page_section', 'telaalbums_show_button' );
	register_setting( 'telaalbums_page_section', 'telaalbums_jq_pagination' );
	register_setting( 'telaalbums_page_section', 'telaalbums_allow_slideshow' );
	register_setting( 'telaalbums_page_section', 'telaalbums_which_jq' );
	register_setting( 'telaalbums_page_section', 'telaalbums_main_photo_page' );
	register_setting( 'telaalbums_page_section', 'telaalbums_add_widget' );
	

	add_settings_section(
		'telaalbums_page_section', 
		__( 'Your section description', 'telaalbums' ), 
		'telaalbums_page_settings_section_callback', 
		'telaalbums_page_section'
	);

	add_settings_field( 
		'telaalbums_albpage_desc', 
		__( 'Album Page Description', 'telaalbums' ), 
		'telaalbums_albpage_desc_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);

	add_settings_field( 
		'telaalbums_show_n_albums', 
		__( 'Albums To Show', 'telaalbums' ), 
		'telaalbums_show_n_albums_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);
	
	add_settings_field( 
		'telaalbums_images_on_front', 
		__( 'Blog View Photo Limit', 'telaalbums' ), 
		'telaalbums_images_on_front_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);
	
	add_settings_field( 
		'telaalbums_show_button', 
		__( 'Editor Button', 'telaalbums' ), 
		'telaalbums_show_button_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);
	
	add_settings_field( 
		'telaalbums_jq_pagination', 
		__( 'jQuery Page Transition', 'telaalbums' ), 
		'telaalbums_jq_pagination_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);

	add_settings_field( 
		'telaalbums_allow_slideshow', 
		__( 'jQuery Slideshow', 'telaalbums' ), 
		'telaalbums_allow_slideshow_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);
	
	
	add_settings_field( 
		'telaalbums_which_jq', 
		__( 'jQuery Version', 'telaalbums' ), 
		'telaalbums_which_jq_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);

	add_settings_field( 
		'telaalbums_main_photo_page', 
		__( 'Main Photo Page', 'telaalbums' ), 
		'telaalbums_main_photo_page_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);

		add_settings_field( 
		'telaalbums_add_widget', 
		__( 'Widgets', 'telaalbums' ), 
		'telaalbums_add_widget_render', 
		'telaalbums_page_section', 
		'telaalbums_page_section' 
	);


}



function telaalbums_albpage_desc_render(  ) { 

	$options = get_option( 'telaalbums_albpage_desc' );
	?>
		<select name='telaalbums_albpage_desc'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
    <i>Decide whether to show the album description below the title on the album page.</i>
	<?php

}

function telaalbums_show_n_albums_render(  ) { 

	$options = get_option( 'telaalbums_show_n_albums' );
	?>
	<input type='text' name='telaalbums_show_n_albums' value='<?php echo $options; ?>'>
	<?php

}

function telaalbums_images_on_front_render(  ) { 

	$options = get_option( 'telaalbums_images_on_front' );
	?>
	<input type='text' name='telaalbums_images_on_front' value='<?php echo $options; ?>'>
	<?php

}

function telaalbums_show_button_render(  ) { 

	$options = get_option( 'telaalbums_show_button' );
	?>
		<select name='telaalbums_show_button'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
        <i>Add text editor buttons to easily add the shortcode to pages and posts.</i>
	<?php

}

function telaalbums_jq_pagination_render(  ) { 

	$options = get_option( 'telaalbums_jq_pagination' );
	$available_styles = array("blindX","blindY","blindZ","cover","curtainX","curtainY","fade","fadeZoom","growX","growY","none","scrollUp","scrollDown","scrollLeft","scrollRight","scrollHorz","scrollVert","shuffle","slideX","slideY","toss","turnUp","turnDown","turnLeft","turnRight","uncover","wipe","zoom");
	echo "<select name='telaalbums_jq_pagination'>";
	     
		 foreach ($available_styles as $value) {

                if ($options != $value) {

                        echo "<option value='$value'>$value</option>";

                } else {

                        echo "<option value='$value' selected>$value</option>";

                }

        }
	
?></select><i>Set <a href='http://jquery.malsup.com/cycle/browser.html' target='_BLANK' alt='See Transition Demos' title='See Transition Demos'>page transition style</a>. Use None to disable.</i><?php
}

function telaalbums_allow_slideshow_render(  ) { 

	$options = get_option( 'telaalbums_allow_slideshow' );
	?>
		<select name='telaalbums_allow_slideshow'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
        <i>Choose whether to enable or disable the 'Slideshow' link on album pages.</i>
	<?php

}

function telaalbums_which_jq_render(  ) { 

	$options = get_option( 'telaalbums_which_jq' );
	?>
		<select name='telaalbums_which_jq'>
		<option value='Wordpress' <?php selected( $options, 'Wordpress' ); ?>>Wordpress</option>
		<option value="Telaaedifex's Albums" <?php selected( $options, "Telaaedifex's Albums" ); ?>>Telaaedifex's Albums</option>
		</select>
        <i>Telaaedifex's Albums uses its own copy of jQuery.  Switch to the WP version if you notice jQuery issues.</i>
	<?php

}


function telaalbums_main_photo_page_render(  ) { 

	$options = get_option( 'telaalbums_main_photo_page' );
    $args = array('selected' => $TELAALBUMS_MAIN_PHOTO_PAGE, 'show_option_none' => "None");
    wp_dropdown_pages($args);

}


function telaalbums_add_widget_render(  ) { 

	$options = get_option( 'telaalbums_add_widget' );
	?>
		<select name='telaalbums_add_widget'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
        <i>Add comments and random photos widgets to the appearance menu.</i>
	<?php

}


//==============================


function telaalbums_page_settings_section_callback(  ) { 

	echo __( 'This section description', 'telaalbums' );

}



function telaalbums_page_settings_page(  ) { 
$nonce = wp_create_nonce( 'telaalbums_' );
	?>
	<form action='options.php' method='post'>

		<h2>Telaaedifex's Albums</h2>

		<?php
		settings_fields( 'telaalbums_page_section' );
		do_settings_sections( 'telaalbums_page_section' );
		if($nonce = $nonce){
			submit_button();
		}else{echo"Please refresh this page";}
		?>

	</form>
	<?php
unset($nonce);
}

?>