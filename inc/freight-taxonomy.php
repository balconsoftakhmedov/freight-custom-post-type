<?php
/**
 *
 * @author    balconet.co
 * @package   Tigon
 * @version   1.0.0
 * @since     1.0.0
 */

function create_freight_taxonomy() {
	register_taxonomy(
		'freight_types',
		'freight',
		array(
			'labels'        => array(
				'name'          => 'Freight Types',
				'add_new_item'  => 'Add New Freight Type',
				'new_item_name' => "New Freight Type"
			),
			'show_ui'       => true,
			'show_tagcloud' => false,
			'hierarchical'  => true,
		)
	);
}

add_action( 'init', 'create_freight_taxonomy' );
function add_freight_types_image_field() {
	echo '<div class="form-field term-group">
    <label for="freight_types_image">Image</label>
    <input type="text" id="freight_types_image" name="freight_types_image" value="">
    <p><a href="#" class="upload_image_button button">Upload/Add image</a></p>
  </div>';
}

add_action( 'freight_types_add_form_fields', 'add_freight_types_image_field', 10, 2 );
function edit_freight_types_image_field( $term, $taxonomy ) {
	$image_id  = get_term_meta( $term->term_id, 'freight_types_image', true );
	$image_url = wp_get_attachment_url( $image_id );
	echo '<tr class="form-field term-group-wrap">
    <th scope="row"><label for="freight_types_image">Image</label></th>
    <td><input type="text" id="freight_types_image" name="freight_types_image" value="' . esc_url( $image_url ) . '">
    <p><a href="#" class="upload_image_button button">Upload/Add image</a></p>
    </td>
  </tr>';
}

add_action( 'freight_types_edit_form_fields', 'edit_freight_types_image_field', 10, 2 );
function save_freight_types_image( $term_id, $tt_id ) {
	if ( isset( $_POST['freight_types_image'] ) && '' !== $_POST['freight_types_image'] ) {
		$image_url = $_POST['freight_types_image'];
		$image_id  = attachment_url_to_postid( $image_url );
		update_term_meta( $term_id, 'freight_types_image', $image_id );
	}
}

add_action( 'created_freight_types', 'save_freight_types_image', 10, 2 );
add_action( 'edited_freight_types', 'save_freight_types_image', 10, 2 );
function load_wp_media_files() {
	wp_enqueue_media();
}

add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
function add_script_to_admin_footer() {
	echo '<script>
jQuery(document).ready(function($){
$(".upload_image_button").click(function(e){
e.preventDefault();
var custom_uploader = wp.media({
title: "Insert Image",
button: {
text: "Use this image"
},
multiple: false
}).on("select", function() {
var attachment = custom_uploader.state().get("selection").first().toJSON();
$("#freight_types_image").val(attachment.url);
}).open();
});
});
</script>';
}

add_action( 'admin_footer', 'add_script_to_admin_footer' );
function show_freight_types_image( $term, $taxonomy ) {
	$image_id  = get_term_meta( $term->term_id, 'freight_types_image', true );
	$image_url = wp_get_attachment_url( $image_id );
	if ( $image_url ) {
		echo '<img src="' . esc_url( $image_url ) . '" alt="">';
	}
}

add_action( 'freight_types_edit_form_fields', 'show_freight_types_image', 10, 2 );
function create_freight_categories() {
	$categories = array(
		'Домашние вещи',
		'Переезд',
		'Автомобили',
		'Попутные грузы',
		'Заказать отдельную машину',
		'Строительные грузы',
		'Коммерческие грузы',
		'Перевозка животных',
		'Пассажирские перевозки',
		'Продукты питания',
		'Мототехника',
		'Водный транспорт',
		'Транспорт и запчасти',
		'Сыпучие грузы',
	);
	foreach ( $categories as $category ) {
		if ( ! term_exists( $category, 'freight_types' ) ) {
			wp_insert_term(
				$category,
				'freight_types',
				array(
					'description' => '',
					'slug'        => sanitize_title( $category )
				)
			);
		}
	}
}

add_action( 'init', 'create_freight_categories' );
