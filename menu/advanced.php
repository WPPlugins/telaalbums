<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//add_action( 'admin_menu', 'telaalbums_add_admin_menu' );
add_action( 'admin_init', 'telaalbums_advanced_settings_init' );


function telaalbums_advanced_settings_init(  ) { 

	register_setting( 'telaalbums_advanced_section', 'telaalbums_caption_length' );
	register_setting( 'telaalbums_advanced_section', 'telaalbums_check_for_updates' );
	register_setting( 'telaalbums_advanced_section', 'telaalbums_description_length' );
	register_setting( 'telaalbums_advanced_section', 'telaalbums_developer_mode' );
	register_setting( 'telaalbums_advanced_section', 'telaalbums_hide_video' );
	register_setting( 'telaalbums_advanced_section', 'telaalbums_require_filter' );
	register_setting( 'telaalbums_advanced_section', 'telaalbums_show_errors' );
	register_setting( 'telaalbums_advanced_section', 'telaalbums_truncate_names' );
	

	add_settings_section(
		'telaalbums_advanced_section', 
		__( 'Your section description', 'telaalbums' ), 
		'telaalbums_advanced_settings_section_callback', 
		'telaalbums_advanced_section'
	);

	add_settings_field( 
		'telaalbums_caption_length', 
		__( 'Caption Length', 'telaalbums' ), 
		'telaalbums_caption_length_render', 
		'telaalbums_advanced_section', 
		'telaalbums_advanced_section'
	);
	
	add_settings_field( 
		'telaalbums_check_for_updates', 
		__( 'Check for Updates', 'telaalbums' ), 
		'telaalbums_check_for_updates_render', 
		'telaalbums_advanced_section', 
		'telaalbums_advanced_section' 
	);
	
	add_settings_field( 
		'telaalbums_description_length', 
		__( 'Description Length', 'telaalbums' ), 
		'telaalbums_description_length_render', 
		'telaalbums_advanced_section', 
		'telaalbums_advanced_section' 
	);
	
	add_settings_field( 
		'telaalbums_developer_mode', 
		__( 'Enable Developer Mode', 'telaalbums' ), 
		'telaalbums_developer_mode_render', 
		'telaalbums_advanced_section', 
		'telaalbums_advanced_section' 
	);

	add_settings_field( 
		'telaalbums_hide_video', 
		__( 'Hide Videos', 'telaalbums' ), 
		'telaalbums_hide_video_render', 
		'telaalbums_advanced_section', 
		'telaalbums_advanced_section' 
	);
	
	add_settings_field( 
		'telaalbums_require_filter_render', 
		__( 'Require Filter', 'telaalbums' ), 
		'telaalbums_require_filter_render', 
		'telaalbums_advanced_section', 
		'telaalbums_advanced_section' 
	);
	
		add_settings_field( 
		'telaalbums_show_errors_render', 
		__( 'Show Errors', 'telaalbums' ), 
		'telaalbums_show_errors_render', 
		'telaalbums_advanced_section', 
		'telaalbums_advanced_section' 
	);
	
	add_settings_field( 
		'telaalbums_truncate_names', 
		__( 'Truncate Names', 'telaalbums' ), 
		'telaalbums_truncate_names_render', 
		'telaalbums_advanced_section', 
		'telaalbums_advanced_section' 
	);


}



function telaalbums_caption_length_render(  ) { 

	$options = get_option( 'telaalbums_caption_length' );
	?>
	<input type='text' name='telaalbums_caption_length' value='<?php echo $options; ?>'>
    <i>Trim display length of captions to specific number of characters</i>
	<?php

}

function telaalbums_check_for_updates_render(  ) { 

	$options = get_option( 'telaalbums_check_for_updates' );
	?>
		<select name='telaalbums_check_for_updates'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i>When TRUE, the script will check for updates.  Set to FALSE to completely disable update checks.  However I don't know why you would want to do this. ITS COMPLETELY FREE!!!</i>
	<?php

}

function telaalbums_description_length_render(  ) { 

	$options = get_option( 'telaalbums_description_length' );
	?>
	<input type='text' name='telaalbums_description_length' value='<?php echo $options; ?>'>
    <i>Trim display length of description to specific number of characters</i>
	<?php

}

function telaalbums_developer_mode_render(  ) { 

	$options = get_option( 'telaalbums_developer_mode' );
	?>
		<select name='telaalbums_developer_mode'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
    <i><s>When TRUE, the plugin will update to the testing version.  THIS IS HIGHLY UNRECOMMENDED!!!</s> This has been disabled until recode is complete.</i>
	<?php

}

function telaalbums_hide_video_render(  ) { 

	$options = get_option( 'telaalbums_hide_video' );
	?>
		<select name='telaalbums_hide_video'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
    <i>Determines whether your videos are displayed within albums</i>
	<?php

}

function telaalbums_require_filter_render(  ) { 

	$options = get_option( 'telaalbums_require_filter' );
	?>
		<select name='telaalbums_require_filter'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
    <i>Decide whether to show the album description below the title on the album page.</i>
	<?php

}

function telaalbums_show_errors_render(  ) { 

	$options = get_option( 'telaalbums_show_errors' );
	?>
		<select name='telaalbums_show_errors'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		</select>
    <i>Decide whether to show the album description below the title on the album page.</i>
	<?php

}

function telaalbums_truncate_names_render(  ) { 

	$options = get_option( 'telaalbums_truncate_names' );
	?>
		<select name='telaalbums_truncate_names'>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		</select>
    <i>Shorten album name to ensure proper display of fluid layout?</i>
	<?php

}

//==============================


function telaalbums_advanced_settings_section_callback(  ) { 

	echo __( 'This section description', 'telaalbums' );

}



function telaalbums_advanced_settings_page(  ) { 
$nonce = wp_create_nonce( 'telaalbums_' );
	?>
	<form action='options.php' method='post'>

		<h2>Telaaedifex's Albums</h2>

		<?php
		settings_fields( 'telaalbums_advanced_section' );
		do_settings_sections( 'telaalbums_advanced_section' );
		if($nonce = $nonce){
			submit_button();
		}else{echo"Please refresh this page";}
		?>

	</form>
	<?php
unset($nonce);
}

?>