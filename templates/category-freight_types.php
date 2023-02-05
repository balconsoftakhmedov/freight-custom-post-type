<?php
/**
 * Template Name: Freight Types
 */

get_header();
$categories = get_terms( array(
		'taxonomy'   => 'freight_types',
		'hide_empty' => false,
) );
if ( ! empty( $categories ) ) : ?>

	<div class="freight-categories-grid">
		<?php
		foreach ( $categories as $category ) :
			$image_id = get_term_meta( $category->term_id, 'freight_types_image', true );
			$image_url = wp_get_attachment_url( $image_id );
			?>
			<div class="freight-category">
				<?php if ( $image_url ) : ?>
					<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
					</a>   <?php endif; ?>
				<h2 class="Category-label"><a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a></h2>
			</div>
		<?php endforeach; ?>
	</div>

<?php
endif;
get_footer();
