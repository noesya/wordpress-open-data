<?php

function oudopo_add_settings_page() {
  add_options_page(
    'Oudopo', 
    'Oudopo', 
    'manage_options', 
    'oudopo-settings', 
    'oudopo_render_settings_page' );
}

add_action( 'admin_menu', 'oudopo_add_settings_page' );

function oudopo_settings_init() {
	register_setting(
    'oudopo-settings', 
    'oudopo_access_key',
    'sanitize_text_field'
  );
  register_setting(
    'oudopo-settings', 
    'oudopo_secret',
    'sanitize_text_field'
  );
  add_settings_section(
    'oudopo_settings_section',
    '',
    '',
    'oudopo-settings'
  );
  add_settings_field( 
    'oudopo_settings_field_access_key',
    'Clé d\'accès',
    'oudopo_settings_field_access_key_callback',
    'oudopo-settings',
    'oudopo_settings_section'
  );
  add_settings_field( 
    'oudopo_settings_field_secret',
    'Code secret',
    'oudopo_settings_field_secret_callback',
    'oudopo-settings',
    'oudopo_settings_section'
  );
}

add_action('admin_init', 'oudopo_settings_init');

function oudopo_settings_field_access_key_callback( ) {
  $value = get_option( 'oudopo_access_key' );
  ?>
  <input class="regular-text" type="text" name="oudopo_access_key" value="<?php echo esc_attr( $value ); ?>">
  <?php
}

function oudopo_settings_field_secret_callback( ) {
  $value = get_option( 'oudopo_secret' );
  ?>
  <input class="regular-text" type="password" name="oudopo_secret" value="<?php echo esc_attr( $value ); ?>">
  <?php
}

function oudopo_render_settings_page() {
  ?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'oudopo-settings' );
			do_settings_sections( 'oudopo-settings' );
			submit_button( 'Enregistrer' );
			?>
		</form>
	</div>
  <?php
}
