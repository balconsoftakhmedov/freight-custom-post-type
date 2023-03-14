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
			'labels'      => array(
				'name'          => __( 'Freights' ),
				'singular_name' => __( 'Freight' ),
			),
			'public'      => true,
			'has_archive' => true,
			'taxonomies'  => array( 'freight_types' ),
			'supports'    => array( 'title', 'editor', 'custom-fields' ),
		)
	);
}

add_action( 'init', 'create_freight_post_type' );

add_action( 'wp_ajax_create_or_update_freight', 'create_or_update_freight' );
add_action( 'wp_ajax_nopriv_create_or_update_freight', 'create_or_update_freight' );


function create_or_update_freight() {
	$data = $_POST['data'];
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'User not logged in' );
	}
	if ( isset( $data['post_id'] ) && $data['post_id'] ) {
		$post_id = $data['post_id'];
		$post    = get_post( $post_id );
		if ( $post && $post->post_type == 'freight' ) {
			// Update post
			wp_update_post( array(
				'ID'           => $post_id,
				'post_title'   => $data['title'],
				'post_content' =>  $data['freight_info'],
				'post_status'  => 'publish'
			) );
		} else {
			wp_send_json_error( 'Invalid post ID' );
		}
	} else {
		// Create new post
		$post_id = wp_insert_post( array(
			'post_type'    => 'freight',
			'post_title'   => $data['title'],
			'post_content' => $data['freight_info'],
			'post_status'  => 'publish'
		) );
	}
	add_freight_meta( $post_id );
	wp_send_json_success( array( 'post_id' => $post_id ) );
}

function add_freight_meta( $post_id ) {
	$data = $_POST['data'];

	// Set category and subcategory terms
	if ( isset( $data['category_id'] ) && isset( $data['subcategory_id'] ) ) {
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];

		// Get the category and subcategory terms
		$category_term = get_term_by( 'id', $category_id, 'freight_category' );
		$subcategory_term = get_term_by( 'id', $subcategory_id, 'freight_subcategory' );

		// Assign terms to post
		if ( $category_term && $subcategory_term ) {
			wp_set_object_terms( $post_id, array( $category_term->term_id, $subcategory_term->term_id ), array( 'freight_category', 'freight_subcategory' ) );
		}
	}

	// Set other meta fields
	foreach ( $data as $key => $value ) {
		if ( $key !== 'category_id' && $key !== 'subcategory_id' ) {
			update_post_meta( $post_id, $key, $value );
		}
	}
}


