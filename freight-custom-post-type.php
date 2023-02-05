<?php
/*
Plugin Name: Freight Custom Post Type
Description: Adds a custom post type for Freights with categories, editing functionality, subcategories and image upload
Version: 1.0
Author: ChatGPT
*/

function freight_post_type_init() {
  require_once plugin_dir_path( __FILE__ ) . 'inc/freight-post-type.php';
  require_once plugin_dir_path( __FILE__ ) . 'inc/freight-taxonomy.php';
}
add_action( 'plugins_loaded', 'freight_post_type_init' );
