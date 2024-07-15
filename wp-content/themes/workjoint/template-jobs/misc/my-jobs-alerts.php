<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
freeio_load_select2();
?>
<div class="box-dashboard-wrapper">
	<h3 class="widget-title"><?php echo esc_html__('Job Alerts','freeio') ?></h3>
	<div class="inner-list">		
		<div class="search-orderby-wrapper d-sm-flex align-items-center">
			<div class="search-jobs-alert-form search-applicants-form widget_search">
				<form action="" method="get">
					<input type="text" placeholder="<?php esc_attr_e( 'Search ...', 'freeio' ); ?>" class="form-control" name="search" value="<?php echo esc_attr(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
					<input type="hidden" name="paged" value="1" />
					<button class="search-submit btn btn-search" name="submit">
						<i class="flaticon-loupe"></i>
					</button>
				</form>
			</div>
			<div class="sort-jobs-alert-form sortby-form ms-auto">
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
		<?php if ( !empty($alerts) && !empty($alerts->posts) ) {
			$email_frequency_default = WP_Freeio_Job_Alert::get_email_frequency(); ?>
			<div class="table-responsive">
				<table class="job-table">
					<thead>
						<tr>
							<th class="job-title-td"><?php esc_html_e('Title', 'freeio'); ?></th>
							<th class="alert-query"><?php esc_html_e('Alert Query', 'freeio'); ?></th>
							<th class="job-number"><?php esc_html_e('Number Jobs', 'freeio'); ?></th>
							<th class="job-times"><?php esc_html_e('Times', 'freeio'); ?></th>
							<th class="job-actions"><?php esc_html_e('Actions', 'freeio'); ?></th>
						</tr>
					</thead>
					<?php foreach ($alerts->posts as $alert_id) {
						
						$email_frequency = get_post_meta($alert_id, WP_FREEIO_JOB_ALERT_PREFIX . 'email_frequency', true);
						if ( !empty($email_frequency_default[$email_frequency]['label']) ) {
							$email_frequency = $email_frequency_default[$email_frequency]['label'];
						}

						$alert_query = get_post_meta($alert_id, WP_FREEIO_JOB_ALERT_PREFIX . 'alert_query', true);
						$params = $alert_query;
						if ( !empty($alert_query) && !is_array($alert_query) ) {
							$params = json_decode($alert_query, true);
						}

						$query_args = array(
							'post_type' => 'job_listing',
						    'post_status' => 'publish',
						    'post_per_page' => 1,
						    'fields' => 'ids'
						);
						$jobs = WP_Freeio_Query::get_posts($query_args, $params);
						$count_jobs = $jobs->found_posts;

						$jobs_alert_url = WP_Freeio_Mixes::get_jobs_page_url();
						if ( !empty($params) ) {
							foreach ($params as $key => $value) {
								if ( is_array($value) ) {
									$jobs_alert_url = remove_query_arg( $key.'[]', $jobs_alert_url );
									foreach ($value as $val) {
										$jobs_alert_url = add_query_arg( $key.'[]', $val, $jobs_alert_url );
									}
								} else {
									$jobs_alert_url = add_query_arg( $key, $value, remove_query_arg( $key, $jobs_alert_url ) );
								}
							}
						}
						?>

						<?php do_action( 'wp_freeio_before_job_alert_content', $alert_id ); ?>
						<tr <?php post_class('job-alert-wrapper'); ?>>
							<td>
								<div class="job-table-info-content-title">
						        	<a href="<?php echo esc_url($jobs_alert_url); ?>" rel="bookmark"><?php echo get_the_title($alert_id); ?></a>
						        </div>
							</td>
							<td>
								<div class="alert-query">
						        	<?php if ( $params ) { ?>
						        		<ul class="list">
						        			<?php
						        				$params = WP_Freeio_Abstract_Filter::get_filters($params);
							        			foreach ($params as $key => $value) {
							        				WP_Freeio_Job_Filter::display_filter_value_simple($key, $value, $params);
							        			}
						        			?>
						        		</ul>
						        	<?php } ?>
						        </div>
							</td>
							<td>
								<div class="job-found">
						            <?php echo sprintf(esc_html__('Jobs found %d', 'freeio'), intval($count_jobs) ); ?>
						        </div>
							</td>
							<td>
								<div class="job-metas">
						            <?php echo trim($email_frequency); ?>
						        </div>
							</td>
							<td>
								<a href="javascript:void(0)" class="btn-remove-job-alert btn-action-icon deleted" data-alert_id="<?php echo esc_attr($alert_id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-freeio-remove-job-alert-nonce' )); ?>" data-toggle="tooltip" title="<?php esc_attr_e('Remove', 'freeio'); ?>"><i class="flaticon-delete"></i></a>
							</td>
						</tr>
						
						<?php do_action( 'wp_freeio_after_job_alert_content', $alert_id );
					}

					?>
				</table>
			</div>
			<?php WP_Freeio_Mixes::custom_pagination( array(
				'wp_query' => $alerts,
				'max_num_pages' => $alerts->max_num_pages,
				'prev_text'     => '<i class="ti-angle-left"></i>',
				'next_text'     => '<i class="ti-angle-right"></i>',
			));
		?>

		<?php } else { ?>
			<div class="not-found"><?php esc_html_e('No job alert found.', 'freeio'); ?></div>
		<?php } ?>
		</div>
</div>