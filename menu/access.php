<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//add_action( 'admin_menu', 'telaalbums_add_admin_menu' );
add_action( 'admin_init', 'telaalbums_access_settings_init' );


function telaalbums_access_settings_init(  ) { 

	register_setting( 'telaalbums_access_section', 'telaalbums_date_format' );
	register_setting( 'telaalbums_access_section', 'telaalbums_google_username' );
	register_setting( 'telaalbums_access_section', 'telaalbums_public_albums_only' );
	register_setting( 'telaalbums_access_section', 'telaalbums_language' );
	

	add_settings_section(
		'telaalbums_access_section', 
		__( 'Your section description', 'telaalbums' ), 
		'telaalbums_access_settings_section_callback', 
		'telaalbums_access_section'
	);

	add_settings_field( 
		'telaalbums_date_format', 
		__( 'Date Format', 'telaalbums' ), 
		'telaalbums_date_format_render', 
		'telaalbums_access_section', 
		'telaalbums_access_section' 
	);

	add_settings_field( 
		'telaalbums_google_username', 
		__( 'Google Username', 'telaalbums' ), 
		'telaalbums_google_username_render', 
		'telaalbums_access_section', 
		'telaalbums_access_section' 
	);

	add_settings_field( 
		'telaalbums_public_albums_only', 
		__( 'Public Albums Only?', 'telaalbums' ), 
		'telaalbums_public_albums_only_render', 
		'telaalbums_access_section', 
		'telaalbums_access_section' 
	);

	add_settings_field( 
		'telaalbums_language', 
		__( 'Site Language', 'telaalbums' ), 
		'telaalbums_language_render', 
		'telaalbums_access_section', 
		'telaalbums_access_section' 
	);


}


function telaalbums_date_format_render(  ) { 

	$options = get_option( 'telaalbums_date_format' );
	?>
	<input type='text' name='telaalbums_date_format' value='<?php echo $options; ?>'>
	<?php

}


function telaalbums_google_username_render(  ) { 

	$options = get_option( 'telaalbums_google_username' );
	?>
	<input type='text' name='telaalbums_google_username' value='<?php echo $options; ?>'>
	<?php

}


function telaalbums_public_albums_only_render(  ) { 

	$options = get_option( 'telaalbums_public_albums_only' );
	?>
	<select name='telaalbums_public_albums_only'>
		<option value='0' <?php selected( $options, 0 ); ?>>False</option>
		<option value='1' <?php selected( $options, 1 ); ?>>True</option>
	</select>

<?php

}


function telaalbums_language_render(  ) { 

	$options = get_option( 'telaalbums_language' );
	wp_dropdown_languages( );
}


function telaalbums_access_settings_section_callback(  ) { 

	echo __( 'This section description', 'telaalbums' );

}


function telaalbums_access_settings_page(  ) { 
$TELAALBUMS_OAUTH_TOKEN = get_option("telaalbums_oauth_token");
$nonce = wp_create_nonce( 'telaalbums_' );

	?>
	<form action='options.php' method='post'>

		<h2>Telaaedifex's Albums</h2>

		<?php
		settings_fields( 'telaalbums_access_section' );
		do_settings_sections( 'telaalbums_access_section' );
		echo "<table class='widefat' cellspacing=5 width=700>";
		echo "<tr><td valign=top style='padding-top: 5px; max-width: 300px;'><strong>OAuth2 Token</strong></td>";
		echo "<td valign=top style='padding-top: 5px;'><i>Allows access to unlisted Picasa albums. <a href='andmin.php?page=telaalbums&loc=reset'>Reset Token</a></i></td></tr>";
		echo "<tr><td valign=top style='padding-top: 5px; max-width: 300px;'>$TELAALBUMS_OAUTH_TOKEN</td>";
		echo "<tfoot><tr><th valign=top colspan=3></th></tr></tfoot></table>\n";
		if($nonce = $nonce){
			submit_button();
		}else{echo"Please refresh this page";}
		?>

	</form>
	<?php

unset($nonce);
}

?>