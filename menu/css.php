<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//add_action( 'admin_menu', 'telaalbums_add_admin_menu' );
add_action( 'admin_init', 'telaalbums_css_settings_init' );


function telaalbums_css_settings_init(  ) { 

	register_setting( 'telaalbums_css_section', 'telaalbums_css_album_grayscale' );
	register_setting( 'telaalbums_css_section', 'telaalbums_css_album_shadow' );
	register_setting( 'telaalbums_css_section', 'telaalbums_css_album_rotate' );
	register_setting( 'telaalbums_css_section', 'telaalbums_css_photo_grayscale' );
	register_setting( 'telaalbums_css_section', 'telaalbums_css_photo_shadow' );
	register_setting( 'telaalbums_css_section', 'telaalbums_css_photo_rotate' );
	

	add_settings_section(
		'telaalbums_css_section', 
		__( 'Your section description', 'telaalbums' ), 
		'telaalbums_css_settings_section_callback', 
		'telaalbums_css_section'
	);

	add_settings_field( 
		'telaalbums_css_album_grayscale', 
		__( 'Album Grayscale', 'telaalbums' ), 
		'telaalbums_css_album_grayscale_render', 
		'telaalbums_css_section', 
		'telaalbums_css_section' 
	);

	add_settings_field( 
		'telaalbums_css_album_shadow', 
		__( 'Album Shadow', 'telaalbums' ), 
		'telaalbums_css_album_shadow_render', 
		'telaalbums_css_section', 
		'telaalbums_css_section' 
	);
	
	add_settings_field( 
		'telaalbums_css_album_rotate', 
		__( 'Album Rotate', 'telaalbums' ), 
		'telaalbums_css_album_rotate_render', 
		'telaalbums_css_section', 
		'telaalbums_css_section' 
	);
	
	add_settings_field( 
		'telaalbums_css_photo_grayscale', 
		__( 'Photo Grayscale', 'telaalbums' ), 
		'telaalbums_css_album_grayscale_render', 
		'telaalbums_css_section', 
		'telaalbums_css_section' 
	);

	add_settings_field( 
		'telaalbums_css_photo_shadow', 
		__( 'Photo Shadow', 'telaalbums' ), 
		'telaalbums_css_photo_shadow_render', 
		'telaalbums_css_section', 
		'telaalbums_css_section' 
	);
	
	add_settings_field( 
		'telaalbums_css_photo_rotate', 
		__( 'Photo Rotate', 'telaalbums' ), 
		'telaalbums_css_photo_rotate_render', 
		'telaalbums_css_section', 
		'telaalbums_css_section' 
	);

}



function telaalbums_css_album_grayscale_render(  ) { 

	$options = get_option( 'telaalbums_css_album_grayscale' );
	?>
		<select name='telaalbums_css_album_grayscale'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i>Decide whether to show the album description below the title on the album page.</i>
	<?php

}

function telaalbums_css_album_shadow_render(  ) { 

	$options = get_option( 'telaalbums_css_album_shadow' );
	?>
		<select name='telaalbums_css_album_shadow'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i>Decide whether to show the album description below the title on the album page.</i>
	<?php

}

function telaalbums_css_album_rotate_render(  ) { 

	$options = get_option( 'telaalbums_css_album_rotate' );
	?>
		<select name='telaalbums_css_album_rotate'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i>Decide whether to show the album description below the title on the album page.</i>
	<?php

}

function telaalbums_css_photo_grayscale_render(  ) { 

	$options = get_option( 'telaalbums_css_photo_grayscale' );
	?>
		<select name='telaalbums_css_photo_grayscale'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i>Decide whether to show the photo description below the title on the photo page.</i>
	<?php

}

function telaalbums_css_photo_shadow_render(  ) { 

	$options = get_option( 'telaalbums_css_photo_shadow' );
	?>
		<select name='telaalbums_css_photo_shadow'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i>Decide whether to show the photo description below the title on the photo page.</i>
	<?php

}

function telaalbums_css_photo_rotate_render(  ) { 

	$options = get_option( 'telaalbums_css_photo_rotate' );
	?>
		<select name='telaalbums_css_photo_rotate'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i>Decide whether to show the album description below the title on the album page.</i>
	<?php

}

//==============================


function telaalbums_css_settings_section_callback(  ) { 

	echo __( 'This section description', 'telaalbums' );

}



function telaalbums_css_settings_page(  ) { 
$nonce = wp_create_nonce( 'telaalbums_' );
	?>
	<form action='options.php' method='post'>

		<h2>Telaaedifex's Albums</h2>

		<?php
		settings_fields( 'telaalbums_css_section' );
		do_settings_sections( 'telaalbums_css_section' );
		if($nonce = $nonce){
			submit_button();
		}else{echo"Please refresh this page";}
		?>

	</form>
	<?php
unset($nonce);
}

?>