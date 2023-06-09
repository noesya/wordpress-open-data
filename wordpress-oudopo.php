<?php
/**
* Plugin Name: Oudopo
* Plugin URI: https://github.com/noesya/oudopo-wordpress
* Description: Export de WordPress vers Oudopo, l'ouvroir de données potentielles.
* Author: noesya
* Author URI: https://www.noesya.coop
* Version: v1.0.2
**/

if (WP_DEBUG) {
  // Local ? Probablement...
  $oudopoApi = 'http://localhost:3000/api';
} else {
  $oudopoApi =  'https://www.oudopo.org/api';
}
defined( 'OUDOPO_API' ) or define( 'OUDOPO_API', $oudopoApi );

include_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/posts.php' );

