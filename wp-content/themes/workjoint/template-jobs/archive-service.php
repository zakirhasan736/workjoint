<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wp_query;

if ( get_query_var( 'paged' ) ) {
    $paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
    $paged = get_query_var( 'page' );
} else {
    $paged = 1;
}

$query_args = array(
	'post_type' => 'service',
    'post_status' => 'publish',
    'post_per_page' => wp_freeio_get_option('number_services_per_page', 10),
    'paged' => $paged,
);

$params = array();
$taxs = ['category', 'location', 'tag'];
foreach ($taxs as $tax) {
	if ( is_tax('service_'.$tax) ) {
		$term = $wp_query->queried_object;
		if ( isset( $term->term_id) ) {
			$params['filter-'.$tax] = $term->term_id;
		}
	}
}
if ( WP_Freeio_Service_Filter::has_filter() ) {
	$params = array_merge($params, $_GET);
}

$services = WP_Freeio_Query::get_posts($query_args, $params);

if ( isset( $_REQUEST['load_type'] ) && WP_Freeio_Mixes::is_ajax_request() ) {
	if ( 'items' !== $_REQUEST['load_type'] ) {
        echo WP_Freeio_Template_Loader::get_template_part('archive-service-ajax-full', array('services' => $services));
	} else {
		echo WP_Freeio_Template_Loader::get_template_part('archive-service-ajax-services', array('services' => $services));
	}

} else {
	get_header();

	$layout_type = freeio_get_services_layout_type();
	$services_display_mode = freeio_get_services_display_mode();
	$service_inner_style = freeio_get_services_inner_style();

	$args = array(
		'services' => $services,
		'service_inner_style' => $service_inner_style,
		'services_display_mode' => $services_display_mode,
	);

	$filter_sidebar = 'services-filter-sidebar';

	if ( $layout_type == 'half-map' ) {

	?>
		<section id="main-container" class="inner">
			<div class="mobile-groups-button d-block d-lg-none clearfix text-center">
				<button class=" btn btn-sm btn-theme btn-view-map" type="button"><i class="fa fa-map" aria-hidden="true"></i> <?php esc_html_e( 'Map View', 'freeio' ); ?></button>
				<button class=" btn btn-sm btn-theme btn-view-listing d-none d-lg-block" type="button"><i class="fa fa-list" aria-hidden="true"></i> <?php esc_html_e( 'Listing View', 'freeio' ); ?></button>
			</div>
			<div class="row m-0 layout-type-<?php echo esc_attr($layout_type); ?>">
				<div id="main-content" class="col-12 col-lg-6 col-xl-5 p-0">
					<div class="inner-left">
						<?php if ( is_active_sidebar( $filter_sidebar ) ): ?>
							<div class="filter-sidebar offcanvas-filter-sidebar">
								<div class="offcanvas-filter-sidebar-header d-flex align-items-center">
							        <div class="title"><?php echo esc_html__('All Filters','freeio'); ?></div>
							        <span class="close-filter-sidebar ms-auto d-flex align-items-center justify-content-center"><i class="ti-close"></i></span>
							    </div>
								<div class="filter-scroll">
						   			<?php dynamic_sidebar( $filter_sidebar ); ?>
						   		</div>
					   		</div>
				   			<div class="over-dark"></div>
					   	<?php endif; ?>
					   	<div class="content-listing">
							<?php
								echo WP_Freeio_Template_Loader::get_template_part('loop/service/archive-inner', $args);
								echo WP_Freeio_Template_Loader::get_template_part('loop/service/pagination', array('services' => $services));
							?>
						</div>
					</div>
				</div><!-- .content-area -->
				<div class="col-12 col-lg-6 col-xl-7 p-0">
					<div id="jobs-google-maps" class="d-none d-lg-block fix-map">
					</div>
				</div>
			</div>
		</section>
	<?php
	} else {
		$sidebar_configs = freeio_get_services_layout_configs();
		$layout_sidebar = freeio_get_services_layout_sidebar();

		$top_content = freeio_get_services_show_top_content();
	?>
		<section id="main-container" class="page-service-board inner layout-type-<?php echo esc_attr($layout_type); ?> <?php echo esc_attr($top_content ? 'has-filter-top':''); ?>">

			<?php freeio_render_breadcrumbs_simple(); ?>

			<?php if ( $top_content ) { ?>
				<div class="services-top-content-wrapper">
			   		<?php freeio_display_top_content( $top_content ); ?>
			   	</div>
			<?php } ?>

			<?php if ( $layout_sidebar == 'main' && is_active_sidebar( $filter_sidebar ) && freeio_get_services_show_offcanvas_filter() ) { ?>
			   	<div class="filter-sidebar offcanvas-filter-sidebar">
			   		<div class="offcanvas-filter-sidebar-header d-flex align-items-center">
				        <div class="title"><?php echo esc_html__('All Filters','freeio'); ?></div>
				        <span class="close-filter-sidebar ms-auto d-flex align-items-center justify-content-center"><i class="ti-close"></i></span>
				    </div>
					<div class="filter-scroll">
			   			<?php dynamic_sidebar( $filter_sidebar ); ?>
			   		</div>
		   		</div>
	   			<div class="over-dark"></div>
			<?php } ?>

			<div class="layout-service-sidebar main-content <?php echo apply_filters('freeio_service_content_class', 'container');?> inner">
				<?php freeio_before_content( $sidebar_configs ); ?>
				<div class="row">
					<?php freeio_display_sidebar_left( $sidebar_configs ); ?>

					<div id="main-content" class="col-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
						<main id="main" class="site-main layout-type-<?php echo esc_attr($layout_type); ?>" role="main">
							<?php
								echo WP_Freeio_Template_Loader::get_template_part('loop/service/archive-inner', $args);

								echo WP_Freeio_Template_Loader::get_template_part('loop/service/pagination', array('services' => $services));
							?>
						</main><!-- .site-main -->
					</div><!-- .content-area -->
					
					<?php freeio_display_sidebar_right( $sidebar_configs ); ?>
				</div>
			</div>
		</section>
	<?php
	}

	get_footer();
}