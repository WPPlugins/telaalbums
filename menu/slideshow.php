<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//add_action( 'admin_menu', 'telaalbums_add_admin_menu' );
add_action( 'admin_init', 'telaalbums_slideshow_settings_init' );


function telaalbums_slideshow_settings_init(  ) { 

	register_setting( 'telaalbums_slideshow_section', 'telaalbums_slideshow_transition' );

	add_settings_section(
		'telaalbums_slideshow_section', 
		__( 'Your section description', 'telaalbums' ), 
		'telaalbums_slideshow_settings_section_callback', 
		'telaalbums_slideshow_section'
	);

	add_settings_field( 
		'telaalbums_slideshow_album_grayscale', 
		__( 'Album Grayscale', 'telaalbums' ), 
		'telaalbums_slideshow_transition', 
		'telaalbums_slideshow_section', 
		'telaalbums_slideshow_section' 
	);
}

//==============================


function telaalbums_slideshow_type_render(  ) { 

	$options = get_option( 'telaalbums_slideshow_type' );
	$available_styles = array("1");
	echo "<select name='telaalbums_slideshow_type'>";
	     
		 foreach ($available_styles as $value) {

                if ($options != $value) {

                        echo "<option value='$value'>$value</option>";

                } else {

                        echo "<option value='$value' selected>$value</option>";

                }

        }
	
?></select><i>This selects the default type of slideshow. Different styles will be added later.</i><?php
}

function telaalbums_slideshow_transition(  ) {

	$options = get_option( 'telaalbums_slideshow_transition' );
	?>
		<select name='telaalbums_slideshow_transition'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i></i>
	<?php

}

//==============================


function telaalbums_slideshow_settings_section_callback(  ) { 

	echo __( 'This section description', 'telaalbums' );

}



function telaalbums_slideshow_settings_page(  ) { 
$nonce = wp_create_nonce( 'telaalbums_' );

?>
	<form action='options.php' method='post'>

		<h2>Telaaedifex's Albums</h2>

		<?php
		
		settings_fields( 'telaalbums_slideshow_section' );
		do_settings_sections( 'telaalbums_slideshow_section' );
		if($nonce = $nonce){
			submit_button();
		}else{echo"Please refresh this page";}
		?>

	</form>
	<?php
unset($nonce);
}

?>