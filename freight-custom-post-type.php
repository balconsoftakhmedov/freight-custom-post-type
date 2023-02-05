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
add_filter( 'template_include', 'freight_types_template' );

function freight_types_template( $template ) {
    if ( is_tax( 'freight_types' ) ) {
        $template = plugin_dir_path( __FILE__ ) . 'templates/category-freight_types.php';
    }
    return $template;
}

function add_freight_categories_css() {
    wp_enqueue_style( 'freight-categories', plugin_dir_url( __FILE__ ) . 'assets/freight-categories.css' );
}
add_action( 'wp_enqueue_scripts', 'add_freight_categories_css' );
