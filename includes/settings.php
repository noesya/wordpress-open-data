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
    <p>
      <a id="oudopo-sync-everything" class="button button-primary">
        Tout synchroniser
        (<?php echo wp_count_posts()->publish; ?> publications)
      </a>
    </p>
    <div id="oudoup-logs"></div>
    <script>
      jQuery(document).ready(function($) {
        $button = $("#oudopo-sync-everything");
        $logs = $("#oudoup-logs");
        index = 0;
        function oudopo_sync_everything () {
          $.post(ajaxurl, 
            {
              "action": "oudopo_sync_post",
              "index": index
            }, 
            function(response) {
              status = response['status'];
              html = '';
              if (status === 'ok') {
                html += '<span class="dashicons dashicons-yes"></span> ';
              } else {
                html += '<span class="dashicons dashicons-no"></span> ';
              }
              index = response['index'];
              message = response['message'];
              html += message;
              html += '<br>';
              $logs.append(html);
              oudopo_sync_everything();
            }
          );
        };
        $button.on('click', function() {
          $button.hide();
          oudopo_sync_everything();
        });
      });
    </script>
	</div>
  <?php
}

add_action( 'wp_ajax_oudopo_sync_post', 'oudopo_sync_post_handler' );

function oudopo_sync_post_handler () {
  $index = $_POST['index'];
  $args = array(
    'post_type' => array('post','page'),
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'offset' => $index,
    'ignore_sticky_posts' => true,
  );
  $query = new WP_Query($args);
  $post = $query->posts[0];
  $data = oudopo_export_post( $post );
  $url = OUDOPO_API . '/content';
  $response = wp_remote_post($url, array(
    'method' => 'POST',
    'headers' => array(
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ),
    'mode' => 'no-cors', 
    'body' => json_encode(
      array(
        'access_key' => get_option( 'oudopo_access_key' ),
        'secret' => get_option( 'oudopo_secret' ),
        'data' => $data
      )
    )
  ));
  $next = $index + 1;
  $total = wp_count_posts()->publish;
  $array = array(
    'index' => $next,
    'message' => $post->post_title  . ' - ' . $post->ID . ' (' . $next . '/' . $total . ')'
  );
  if (is_wp_error( $response )) {
    $array['status'] = 'error';
  } else {
    $array['status'] = 'ok';
  }
  wp_send_json($array);
  wp_die();
}
