<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $post;
?>

<?php do_action( 'wp_freeio_before_service_content', $post->ID ); ?>

<article <?php post_class('map-item service-item list position-relative'); ?> <?php freeio_service_item_map_meta($post); ?>>
    <div class="d-sm-flex align-items-center">
        <div class="position-relative service-top flex-shrink-0">
            <?php freeio_service_display_featured_icon($post, 'text'); ?>
            <?php freeio_service_display_image($post); ?>
        </div>
        <div class="service-information flex-grow-1">
            <?php freeio_service_display_first_category($post); ?>
            <?php the_title( sprintf( '<h2 class="service-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            <?php
                $post_content = get_the_content($post);
                if ( !empty($post_content) ) {
                    echo '<div class="excerpt">';
                    echo freeio_substring( $post_content, 15, '...' );
                    echo '</div>';
                }
            ?>
            <?php freeio_service_display_rating_reviews($post); ?>
            <div class="info-bottom d-flex align-items-center">
                <?php freeio_service_display_author($post); ?>
                <div class="ms-auto">
                    <?php freeio_service_display_price($post, 'text'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo freeio_service_display_favorite_btn($post->ID); ?>
</article><!-- #post-## -->
<?php do_action( 'wp_freeio_after_service_content', $post->ID ); ?>