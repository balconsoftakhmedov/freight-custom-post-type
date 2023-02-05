<?php
/**
 *
 * @author    balconet.co
 * @package   Tigon
 * @version   1.0.0
 * @since     1.0.0
 */

function create_freight_post_type() {
  register_post_type( 'freight',
    array(
      'labels' => array(
        'name' => __( 'Freights' ),
        'singular_name' => __( 'Freight' ),
      ),
      'public' => true,
      'has_archive' => true,
      'taxonomies'  => array( 'freight_types' ),
      'supports' => array( 'title', 'editor', 'custom-fields' ),
    )
  );
}
add_action( 'init', 'create_freight_post_type' );
