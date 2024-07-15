<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
freeio_load_select2();
?>
<div class="box-dashboard-wrapper">
	<h3 class="widget-title"><?php echo esc_html__('All Applicants','freeio') ?></h3>
	<div class="inner-list">
		<div class="search-orderby-wrapper d-sm-flex align-items-center">
			<div class="search-applicants-form widget_search">
				<form action="" method="get" class="my-jobs my-jobs-ordering">
						<div class="d-sm-flex align-items-center">
							<input type="text" placeholder="<?php esc_attr_e( 'Search ...', 'freeio' ); ?>" class="form-control" name="search" value="<?php echo esc_attr(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
							<div class="middle-search">
								<select name="job_id" class="job_id">
									<option value=""><?php esc_html_e('Filter by job', 'freeio'); ?></option>
									<?php if ( !empty($job_ids) ) {
										$selected = !empty($_GET['job_id']) ? $_GET['job_id'] : '';
									?>
										<?php foreach ($job_ids as $job_id) { ?>
											<option value="<?php echo esc_attr($job_id); ?>" <?php selected($selected, $job_id); ?>><?php echo get_the_title($job_id); ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<button class="search-submit btn btn-theme btn-inverse ms-auto" name="submit">
								<i class="flaticon-loupe"></i>
							</button>
						</div>
					<input type="hidden" name="paged" value="1" />
				</form>
			</div>
			<div class="sort-applicants-form sortby-form ms-auto">
				<?php
					$orderby_options = apply_filters( 'wp_freeio_my_jobs_orderby', array(
						'menu_order'	=> esc_html__( 'Default', 'freeio' ),
						'newest' 		=> esc_html__( 'Newest', 'freeio' ),
						'oldest'     	=> esc_html__( 'Oldest', 'freeio' ),
					) );

					$orderby = isset( $_GET['orderby'] ) ? wp_unslash( $_GET['orderby'] ) : 'newest'; 
				?>

				<div class="orderby-wrapper d-flex align-items-center">
					<span class="text-sort">
						<?php echo esc_html__('Sort by: ','freeio'); ?>
					</span>
					<form class="my-jobs-ordering" method="get">
						<select name="orderby" class="orderby">
							<?php foreach ( $orderby_options as $id => $name ) : ?>
								<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
							<?php endforeach; ?>
						</select>
						<input type="hidden" name="paged" value="1" />
						<?php WP_Freeio_Mixes::query_string_form_fields( null, array( 'orderby', 'submit', 'paged' ) ); ?>
					</form>
				</div>
			</div>
		</div>

		<?php
		if ( !empty($job_ids) && is_array($job_ids) ) {
			
			if ( !empty($_GET['job_id']) ) {
				$job_ids = array($_GET['job_id']);
			}

			foreach ($job_ids as $job_id) {
				$jids = apply_filters( 'wp-freeio-translations-post-ids', $job_id );
				if ( !is_array($jids) ) {
					$jids = array($jids);
				}
				$current_page = 1;
				$query_vars = array(
					'post_type'         => 'job_applicant',
					'posts_per_page'    => get_option('posts_per_page'),
					'paged'    			=> $current_page,
					'post_status'       => 'publish',
					'meta_query'       => array(
						array(
							'key' => WP_FREEIO_APPLICANT_PREFIX.'job_id',
							'value'     => $jids,
							'compare'   => 'IN',
						)
					)
				);

				if ( isset($_GET['search']) ) {
					$query_vars['s'] = $_GET['search'];
				}
				if ( isset($_GET['orderby']) ) {
					switch ($_GET['orderby']) {
						case 'menu_order':
							$query_vars['orderby'] = array(
								'menu_order' => 'ASC',
								'date'       => 'DESC',
								'ID'         => 'DESC',
							);
							break;
						case 'newest':
							$query_vars['orderby'] = 'date';
							$query_vars['order'] = 'DESC';
							break;
						case 'oldest':
							$query_vars['orderby'] = 'date';
							$query_vars['order'] = 'ASC';
							break;
					}
				}

				$applicants = new WP_Query($query_vars);
				
				$query_vars = array(
					'post_type'         => 'job_applicant',
					'posts_per_page'    => 1,
					'paged'    			=> 1,
					'post_status'       => 'publish',
					'meta_query'       => array(
						array(
							'key' => WP_FREEIO_APPLICANT_PREFIX.'job_id',
							'value'     => $jids,
							'compare'   => 'IN',
						),
						array(
							'key' => WP_FREEIO_APPLICANT_PREFIX.'app_status',
							'value'     => 'rejected',
							'compare'   => '=',
						)
					)
				);
				$rejected = WP_Freeio_Query::get_posts($query_vars);

				$query_vars = array(
					'post_type'         => 'job_applicant',
					'posts_per_page'    => 1,
					'paged'    			=> 1,
					'post_status'       => 'publish',
					'meta_query'       => array(
						array(
							'key' => WP_FREEIO_APPLICANT_PREFIX.'job_id',
							'value'     => $jids,
							'compare'   => 'IN',
						),
						array(
							'key' => WP_FREEIO_APPLICANT_PREFIX.'app_status',
							'value'     => 'approved',
							'compare'   => '=',
						)
					)
				);
				$approved = WP_Freeio_Query::get_posts($query_vars);
				?>
				<div class="job-applicants">
					<div class="heading row d-sm-flex align-items-center">
						<div class="col-md-8 col-12">
							<h3 class="job-title"><?php echo get_the_title($job_id); ?></h3>
						</div>
						<div class="col-md-4 col-12">
							<div class="inner-result d-flex align-items-center justify-content-md-end">
								<div class="total-applicants show-total-applicants active" data-job_id="<?php echo esc_attr($job_id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-freeio-show-applicants-nonce' )); ?>">
									<?php
										echo sprintf(esc_html__('Total(s): %s', 'freeio'), '<span class="number">'.$applicants->found_posts.'</span>');
									?>
								</div>
								<div class="approved-applicants show-approved-applicants" data-job_id="<?php echo esc_attr($job_id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-freeio-show-approved-applicants-nonce' )); ?>">
									<?php echo sprintf(esc_html__('Approved: %s', 'freeio'), '<span class="number">'.$approved->found_posts.'</span>'); ?>
								</div>
								<div class="rejected-applicants show-rejected-applicants" data-job_id="<?php echo esc_attr($job_id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-freeio-show-rejected-applicants-nonce' )); ?>">
									<?php
										echo sprintf(esc_html__('Rejected(s): %s', 'freeio'), '<span class="number">'.$rejected->found_posts.'</span>');
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="applicants-wrapper">
						<?php if ( $applicants->have_posts() ) {
								?>
								<div class="applicants-inner">
									<?php
									while ( $applicants->have_posts() ) : $applicants->the_post();
										global $post;
										
										$app_status = WP_Freeio_Applicant::get_post_meta($post->ID, 'app_status', true);
					                    if ( $app_status == 'rejected' ) {
											echo WP_Freeio_Template_Loader::get_template_part( 'content-rejected-applicant' );
										} elseif ( $app_status == 'approved' ) {
											echo WP_Freeio_Template_Loader::get_template_part( 'content-approved-applicant' );
										} else {
											echo WP_Freeio_Template_Loader::get_template_part( 'content-applicant' );
										}
										
									endwhile;
									?>
								</div>
								<?php if ( $applicants->max_num_pages > $current_page ) { ?>
									<form class="applicants-pagination-form">
										<button class="apus-loadmore-btn"><?php esc_html_e( 'Load more', 'freeio' ); ?></button>
										<input type="hidden" name="paged" value="<?php echo esc_attr($current_page + 1); ?>">
										<input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">
										<?php WP_Freeio_Mixes::query_string_form_fields( null, array( 'job_id', 'submit', 'paged' ) ); ?>
									</form>
								<?php } ?>
								
								<?php wp_reset_postdata();
							} else {
								?>
								<div class="no-found"><?php esc_html_e('No applicants found.', 'freeio'); ?></div>
								<?php
							}
						?>

					</div>
				</div>
				<?php
			}
			
		} else { ?>
			<div class="no-found"><?php esc_html_e('No applicants found.', 'freeio'); ?></div>
		<?php } ?>
		</div>
</div>