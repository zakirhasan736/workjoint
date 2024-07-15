<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( empty($freelancers) ) {
	return;
}

$pagination_type = !empty($settings['pagination_type']) ? $settings['pagination_type'] : 'default';

?>
<div class="freelancers-pagination-wrapper main-pagination-wrapper" data-settings="<?php echo esc_attr(json_encode($settings)); ?>">
	<?php
		if ( $pagination_type == 'loadmore' || $pagination_type == 'infinite' ) {
			$next_link = get_next_posts_link( '&nbsp;', $freelancers->max_num_pages );
			if ( $next_link ) {
		?>
				<div class="ajax-pagination <?php echo trim($pagination_type == 'loadmore' ? 'loadmore-action' : 'infinite-action'); ?>">
					<div class="apus-pagination-next-link hidden"><?php echo trim($next_link); ?></div>
					<a href="#" class="apus-loadmore-btn"><?php esc_html_e( 'Load more', 'freeio' ); ?></a>
					<span href="#" class="apus-allproducts"><?php esc_html_e( 'All freelancers loaded.', 'freeio' ); ?></span>
				</div>
		<?php
			}
		} else {
			WP_Freeio_Mixes::custom_pagination( array(
				'wp_query' => $freelancers,
				'max_num_pages' => $freelancers->max_num_pages,
				'prev_text'     => '<i class="ti-angle-left"></i>',
				'next_text'     => '<i class="ti-angle-right"></i>',
			));
		}
	?>
</div>
