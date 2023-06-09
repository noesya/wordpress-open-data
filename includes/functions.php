<?php

function oudopo_export_post( $post ) {
  $media = get_attached_media( '', $post->ID );
  $object = array(
    'essentials' => array(
      'id' => $post->ID,
      'date' => $post->post_date_gmt,
      'title' => $post->post_title,
      'slug' => $post->post_name,
      'content' => $post->post_content,
      'content_filtered' => apply_filters( 'the_content', $post->post_content ),
      'excerpt' => $post->post_excerpt,
    ),
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
      'count' => count($media),
      'data' => $media,
    )
  );
  return $object;
}


