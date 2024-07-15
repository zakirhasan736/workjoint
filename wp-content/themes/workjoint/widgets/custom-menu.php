<?php
extract( $args );

extract( $args );
extract( $instance );

$output = '';

$atts['title'] = $title;
if ($nav_menu) {
	$term = get_term_by( 'slug', $nav_menu, 'nav_menu' );
	if ( !empty($term) ) {
		$atts['nav_menu'] = $term->term_id;
	}
}
if ( empty($atts['nav_menu']) ) {
	return;
}
echo trim($before_widget);

?>
<div class="apus_custom_menu <?php echo esc_attr(!empty($style) ? $style : ''); ?>">
	<?php
	    $args = array(
	        'menu'        => $atts['nav_menu'],
	        'container_class' => 'collapse navbar-collapse no-padding',
	        'menu_class' => 'custom-menu',
	        'fallback_cb' => '',
	        'walker' => new Freeio_Nav_Menu()
	    );
	    wp_nav_menu($args);
	?>
</div>
<?php echo trim($after_widget);