<?php
/**
* Plugin Name: Oudopo
* Plugin URI: https://github.com/noesya/oudopo-wordpress
* Description: Export de WordPress vers Oudopo, l'ouvroir de données potentielles.
* Author: noesya
* Author URI: https://www.noesya.coop
* Version: v0.0.5
**/

if (WP_DEBUG) {
  // Local ? Probablement...
  defined( 'OUDOPO_API' ) or define( 'OUDOPO_API', 'http://localhost:3000/api' );
} else {
  defined( 'OUDOPO_API' ) or define( 'OUDOPO_API', 'https://www.oudopo.org/api' );
}

include_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/posts.php' );

