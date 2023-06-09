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
  // Used for debug
  // $json = json_encode($object, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  // $encoded = htmlentities($json);
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
