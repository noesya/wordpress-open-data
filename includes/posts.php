<?php

add_action( 'add_meta_boxes_post', 'oudopo_add_meta_boxes_post' );

function oudopo_add_meta_boxes_post( $post ) {
  add_meta_box( 
    'oudopo',
    'Oudopo',
    'oudopo_render_meta_box_content',
    'post',
    'side'
  );
}

function oudopo_render_meta_box_content( $post ) {
  $object = oudopo_export_post($post);
  $json = json_encode($object, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  $encoded = htmlentities($json);
  ?>
  <p>
    <a href="https://www.oudopo.org" target="_blank">oudopo.org</a>
  </p>
  <a href="javascript:oudopo_sync()" class="button button-primary">Synchroniser</a>
  <script>
    function oudopo_sync () {
      fetch('<?php echo OUDOPO_API ?>/content', {
        method: 'POST',
        mode: 'no-cors', 
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify( {
          access_key: "<?php echo get_option( 'oudopo_access_key' ) ?>",
          secret: "<?php echo get_option( 'oudopo_secret' ) ?>",
          data: <?php echo json_encode($object) ?>
        })
      })
      .then(response => response.json())
      .then(response => console.log(JSON.stringify(response)))
    }
  </script>
  <?php /*
  <p>JSON data</p>
  <pre style="overflow-x:scroll"><?php echo $encoded; ?></pre>
  */ ?>
  <?php
}

function oudopo_export_post( $post ) {
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
      'count' => count(get_attached_media('')),
      'data' => get_attached_media(''),
    )
  );
  return $object;
}


