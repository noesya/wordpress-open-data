<?php
/**
* Plugin Name: Oudopo
* Plugin URI: https://github.com/noesya/oudopo-wordpress
* Description: Export de WordPress vers Oudopo, l'ouvroir de données potentielles.
* Author: noesya
* Author URI: https://www.noesya.coop
* Version: v0.0.2
**/
/*
Based on https://gist.github.com/jsnelders/fd22ebc26530468125ffed2d5d1eb279 by Jason Snelders
*/

function oudopo_add_meta_boxes_post( $post ) {
  add_meta_box( 
      'oudopo',
      __( 'Oudopo' ),
      'oudopo_meta_box_content',
      'post'
  );
}
add_action( 'add_meta_boxes_post', 'oudopo_add_meta_boxes_post' );

function oudopo_meta_box_content( $post ) {
  $object = oudopo_export_post($post);
  echo '<pre class="CodeMirror">';
  $json = json_encode($object, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  echo htmlentities($json);
  echo '</pre>';
}

function oudopo_export_post( $post ) {
  $object = array(
    'id' => $post->ID,
    'date' => $post->post_date_gmt,
    'title' => $post->post_title,
    'slug' => $post->post_name,
    'content' => $post->post_content,
    'content_filtered' => apply_filters( 'the_content', $post->post_content ),
    'excerpt' => $post->post_excerpt,
    'categories' => array(
      'count' => count(wp_get_post_categories($post->ID)),
      'data' => wp_get_post_categories($post->ID, array( 'fields' => 'all'))
    ),
    'meta' => array(
      'count' => count(get_post_meta($post->ID)),
      'data' => get_post_meta($post->ID)
    ),
    'comments' => array(
      'count' => get_comments(array('post_id' => $post->ID, 'count' => true)),
      'data' => get_comments(array('post_id' => $post->ID, 'fields' => 'all'))
    ),
    'media' => array(
      'count' => count(get_attached_media('')),
      'data' => get_attached_media(''),
    )
  );
  return $object;
}
