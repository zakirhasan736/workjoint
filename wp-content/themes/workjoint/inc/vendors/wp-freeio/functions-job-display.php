<?php

function freeio_job_display_employer_logo($post, $link = true, $link_employer = false) {
	$author_id = freeio_get_post_author($post->ID);
	$employer_id = WP_Freeio_User::get_employer_by_user_id($author_id);
	if ( $link ) {
		if ( $link_employer ) {
			$url = get_permalink($employer_id);
		} else {
			$url = get_permalink($post);
		}
	}
	$meta_obj = WP_Freeio_Job_Listing_Meta::get_instance($post->ID);
	?>
    <div class="employer-logo">
    	<?php if ( $link ) { ?>
        	<a href="<?php echo esc_url( $url ); ?>">
        <?php } ?>
        		<?php if ( $meta_obj->check_post_meta_exist('logo') && ($logo_url = $meta_obj->get_post_meta( 'logo' )) ) {
        			$logo_id = WP_Freeio_Job_Listing::get_post_meta($post->ID, 'logo_id', true);
        			if ( $logo_id ) {
	        			echo wp_get_attachment_image( $logo_id, 'thumbnail' );
	        		} else {
	        			?>
	        			<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_the_title($employer_id)); ?>">
	        			<?php
	        		}
    			} else {
    				
    				if ( has_post_thumbnail($employer_id) ) {
    					echo get_the_post_thumbnail( $employer_id, 'thumbnail' );
    				} else { ?>
	            		<img src="<?php echo esc_url(freeio_placeholder_img_src()); ?>" alt="<?php echo esc_attr(get_the_title($employer_id)); ?>">
	            	<?php } ?>
            	<?php } ?>
        <?php if ( $link ) { ?>
        	</a>
        <?php } ?>

        <?php
        $meta_obj = WP_Freeio_Employer_Meta::get_instance($employer_id);
        $verified = $meta_obj->get_post_meta( 'verified' );
        if ( $verified ) {
            ?>
            <span class="verified"><i class="flaticon-tick"></i></span>
            <?php
        }
        ?>
    </div>
    <?php
}

function freeio_job_display_employer_title($post, $display_type = 'no-icon') {
	$author_id = freeio_get_post_author($post->ID);
	$employer_id = WP_Freeio_User::get_employer_by_user_id($author_id);
	if ( $employer_id ) {
		$employer_obj = get_post($employer_id);
		?>
	        <h3 class="employer-title">
	            <a href="<?php echo esc_url( get_permalink($employer_id) ); ?>">
	            	<?php if ($display_type == 'icon') { ?>
							<i class="ti-home"></i>
					<?php } ?>
	                <?php echo freeio_employer_name($employer_obj); ?>
	            </a>
	        </h3>
	    <?php
	}
}

function freeio_job_display_job_category($post, $display_category = 'no-title') {
	$categories = get_the_terms( $post->ID, 'job_listing_category' );
	if ( $categories ) { $i = 1;
		?>
		<div class="category-job">
			<?php
			if ( $display_category == 'title' ) {
				?>
				<div class="job-category with-title">
					<strong><?php esc_html_e('Job Category:', 'freeio'); ?></strong>
				<?php
			} elseif ($display_category == 'icon') {
				?>
				<div class="job-category with-icon">
					<i class="flaticon-category"></i>
			<?php
			} else {
				?>
				<div class="job-category">
				<?php
			}
				foreach ($categories as $term) {
					$color = get_term_meta( $term->term_id, '_color', true );
					$style = '';
					if ( $color ) {
						$style = 'color: '.$color;
					}
					?>
						<?php if( $i < count($categories) ) { ?>
							<a href="<?php echo get_term_link($term); ?>" style="<?php echo esc_attr($style); ?>"><?php echo esc_html($term->name); ?></a>, 
						<?php }else{ ?>
							<a href="<?php echo get_term_link($term); ?>" style="<?php echo esc_attr($style); ?>"><?php echo esc_html($term->name); ?></a>
						<?php } ?>	
		        	<?php $i++;
		    	}
	    	?>
	    	</div>
	    </div>
    	<?php
    }
}

function freeio_job_display_job_first_category($post, $display_category = 'no-title') {
	$categories = get_the_terms( $post->ID, 'job_listing_category' );
	if ( $categories ) { 
		?>
		<div class="category-job">
			<?php
			if ( $display_category == 'title' ) {
				?>
				<div class="job-category with-title">
					<strong><?php esc_html_e('Job Category:', 'freeio'); ?></strong>
				<?php
			} elseif ($display_category == 'icon') {
				?>
				<div class="job-category with-icon">
					<i class="flaticon-category"></i>
			<?php
			} else {
				?>
				<div class="job-category">
				<?php
			}
				foreach ($categories as $term) {
					$color = get_term_meta( $term->term_id, '_color', true );
					$style = '';
					if ( $color ) {
						$style = 'color: '.$color;
					}
					?>
						<a href="<?php echo get_term_link($term); ?>" style="<?php echo esc_attr($style); ?>"><?php echo esc_html($term->name); ?></a>
		        	<?php break;
		    	}
	    	?>
	    	</div>
	    </div>
    	<?php
    }
}

function freeio_job_display_job_type($post, $display_type = 'no-title', $color = true, $echo = true) {
	$types = get_the_terms( $post->ID, 'job_listing_type' );
	ob_start();
	if ( $types ) { $i = 1;
		?>
			<?php
			if ( $display_type == 'title' ) {
				?>
				<div class="job-type with-title">
					<strong><?php esc_html_e('Job Type:', 'freeio'); ?></strong>
				<?php
			} elseif ($display_type == 'icon') {
				?>
				<div class="job-type with-icon">
					<i class="ti-calendar"></i>
			<?php
			} else {
				?>
				<div class="job-type with-title">
				<?php
			}
				foreach ($types as $term) {
					$style = '';
					if ( $color ) {
						$bg_color = get_term_meta( $term->term_id, 'bg_color', true );
						$text_color = get_term_meta( $term->term_id, 'text_color', true );
						
						if ( !empty($bg_color) ) {
							$style .= 'background-color: '.$bg_color.';';
						}
						if ( !empty($text_color) ) {
							$style .= 'color: '.$text_color.';';
						}
					}
					?>
		            	<a class="type-job" href="<?php echo get_term_link($term); ?>" style="<?php echo esc_attr($style); ?>"><?php echo esc_html($term->name); ?></a><?php echo trim( ($i == count($types))?'':', ' ); ?>
		        	<?php $i++;
		    	}
	    	?>
	    	</div>
    	<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function freeio_job_display_tags($post, $display_type = 'no-title', $echo = true) {
	$tags = get_the_terms( $post->ID, 'job_listing_tag' );
	ob_start();
	if ( $tags ) {
		?>
			<?php
			if ( $display_type == 'title' ) {
				?>
				<div class="job-tags">
				<strong><?php esc_html_e('Tagged as:', 'freeio'); ?></strong>
				<?php
			} else {
				?>
				<div class="job-tags">
				<?php
			}
				foreach ($tags as $term) {
					?>
		            	<a class="tag-job" href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?></a>
		        	<?php
		    	}
	    	?>
	    	</div>
    	<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function freeio_job_display_tags_version2($post, $display_type = 'no-title',$echo = true, $limit = 3) {
	$tags = get_the_terms( $post->ID, 'job_listing_tag' );
	ob_start();
	$i = 1;
	if ( $tags ) {
		?>
			<?php
			if ( $display_type == 'title' ) {
				?>
				<div class="job-tags">
				<strong><?php esc_html_e('Tagged as:', 'freeio'); ?></strong>
				<?php
			} else {
				?>
				<div class="job-tags">
				<?php
			}
				foreach ($tags as $term) {
					if ( $limit > 0 && $i > $limit ) {
	                    break;
	                }
					?>
		            	<a class="tag-job" href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?></a>
		        	<?php $i++;
		    	} 
	    	?>

	    	<?php if ( $limit > 0 && count($tags) > $limit ) { ?>
	    		<span class="count-more-tags"><?php echo sprintf(esc_html__('+%d', 'freeio'), (count($tags) - $limit) ); ?></span>
	    	<?php } ?>

	    	</div>
    	<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function freeio_job_display_short_location($post, $display_type = 'no-icon', $echo = true) {
	$locations = get_the_terms( $post->ID, 'location' );
	ob_start();
	if ( $locations ) {
		$terms = array();
        freeio_locations_walk($locations, 0, $terms);
        if ( empty($terms) ) {
        	$terms = $locations;
        } else {
        	$terms = array_reverse($terms, true);
        }
		?>
		<div class="job-location">
			<?php if ($display_type == 'icon') { ?>
	            <i class="flaticon-place"></i>
	        <?php } ?>
            <?php $i=1; foreach ($terms as $term) { ?>
                <?php echo trim($term->name); ?><?php echo esc_html( $i < count($terms) ? ', ' : '' ); ?>
            <?php $i++; } ?>
        </div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function freeio_job_display_full_location($post, $display_type = 'no-icon-title') {
	$meta_obj = WP_Freeio_Job_Listing_Meta::get_instance($post->ID);

	$location = $meta_obj->get_post_meta( 'address' );
	if ( empty($location) ) {
		$location = $meta_obj->get_post_meta( 'map_location_address' );
	}
	if ( $location ) {
		if ( $display_type == 'icon' ) {
			?>
			<div class="job-location with-icon"><i class="flaticon-place"></i> <?php echo trim($location); ?></div>
			<?php
		} elseif ( $display_type == 'title' ) {
			?>
			<div class="job-location with-title">
				<strong><?php esc_html_e('Location:', 'freeio'); ?></strong> <?php echo trim($location); ?>
			</div>
			<?php
		} else {
			?>
			<div class="job-location"><?php echo trim($location); ?></div>
			<?php
		}
    }
}

function freeio_job_display_salary($post, $display_type = 'no-icon-title', $echo = true) {
	$salary = WP_Freeio_Job_Listing::get_salary_html($post->ID);
	ob_start();
	if ( $salary ) {
		if ( $display_type == 'icon' ) {
			?>
			<div class="job-salary with-icon"><i class="flaticon-money-1"></i> <?php echo trim($salary); ?></div>
			<?php
		} elseif ( $display_type == 'title' ) {
			?>
			<div class="job-salary with-title">
				<strong><?php esc_html_e('Salary:', 'freeio'); ?></strong> <span><?php echo trim($salary); ?></span>
			</div>
			<?php
		} else {
			?>
			<div class="job-salary"><?php echo trim($salary); ?></div>
			<?php
		}
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function freeio_job_display_favorite_btn($post_id) {
	
	$return = WP_Freeio_Favorite::display_job_favorite_btn($post_id, array(
		'show_text' => true,
		'tooltip' => true,
		'added_text' => esc_html__('Saved', 'freeio'),
		'add_text' => esc_html__('Save', 'freeio'),
		'added_tooltip_title' => esc_html__('Remove Favorite', 'freeio'),
		'add_tooltip_title' => esc_html__('Add Favorite', 'freeio'),
		'echo' => false,
	));
	
	return $return;
}


function freeio_job_display_deadline($post, $display_type = 'no-icon-title', $echo = true) {
	$meta_obj = WP_Freeio_Job_Listing_Meta::get_instance($post->ID);

	$application_deadline_date = $meta_obj->get_post_meta( 'application_deadline_date' );
	ob_start();
	if ( empty($application_deadline_date) || date('Y-m-d', strtotime($application_deadline_date)) >= date('Y-m-d', strtotime('now')) ) {
		if ( $application_deadline_date ) {
			$deadline_date = strtotime($application_deadline_date);
			?>
			<div class="deadline-time"><?php echo date_i18n(get_option('date_format'), $deadline_date); ?></div>
			<?php
		}
	} else {
		?>
		<div class="deadline-closed"><?php esc_html_e('Application deadline closed.', 'freeio'); ?></div>
		<?php
	}
	$ouput = ob_get_clean();

	ob_start();
	if ( $display_type == 'icon' ) {
		?>
		<div class="job-deadline with-icon"><i class="flaticon-30-days"></i> <?php echo trim($ouput); ?></div>
		<?php
	} elseif ( $display_type == 'title' ) {
		?>
		<div class="job-deadline with-title">
			<strong><?php esc_html_e('Deadline date:', 'freeio'); ?></strong> <?php echo trim($ouput); ?>
		</div>
		<?php
	} else {
		?>
		<div class="job-deadline"><?php echo trim($ouput); ?></div>
		<?php
	}
	$output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function freeio_job_display_postdate($post, $display_type = 'no-icon-title', $echo = true) {

	ob_start();
	if ( $display_type == 'icon' ) {
		?>
		<div class="job-deadline with-icon"><i class="flaticon-30-days"></i> <?php the_time(get_option('date_format')); ?></div>
		<?php
	} elseif ( $display_type == 'title' ) {
		?>
		<div class="job-deadline with-title">
			<strong><?php esc_html_e('Posted date:', 'freeio'); ?></strong> <?php the_time(get_option('date_format')); ?>
		</div>
		<?php
	} else {
		?>
		<div class="job-deadline"><?php the_time(get_option('date_format')); ?></div>
		<?php
	}
	$output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function freeio_job_display_featured_icon($post, $display = 'icon') {
	$meta_obj = WP_Freeio_Job_Listing_Meta::get_instance($post->ID);

	$featured = $meta_obj->get_post_meta( 'featured' );
	if ( $featured ) { ?>
		<?php if($display == 'icon') {?>
        	<span class="featured" data-toggle="tooltip" title="<?php esc_attr_e('featured', 'freeio'); ?>"><i class="flaticon-tick"></i></span>
        <?php } else { ?>
        	<span class="featured-text"><?php esc_html_e('Featured', 'freeio'); ?></span>
        <?php } ?>
    <?php }
}

function freeio_job_display_urgent_icon($post, $display = 'text') {
	$meta_obj = WP_Freeio_Job_Listing_Meta::get_instance($post->ID);

	$urgent = $meta_obj->get_post_meta( 'urgent' );
	if ( $urgent ) { ?>
		<?php if($display == 'text') { ?>
        	<span class="urgent"><?php esc_html_e('Urgent', 'freeio'); ?></span>
        <?php } else { ?>
    		<span class="urgent urgent-icon" data-toggle="tooltip" title="<?php esc_attr_e('Urgent', 'freeio'); ?>"><i class="flaticon-waiting"></i></span>
        <?php } ?>
    <?php }
}

function freeio_job_display_filled_label($post, $echo = true) {
	if ( WP_Freeio_Job_Listing::is_filled($post->ID) ) {
		?>
		<span class="filled-text"><?php esc_html_e('Filled', 'freeio'); ?></span>
		<?php
	}
}

function freeio_job_item_map_meta($post) {
	$meta_obj = WP_Freeio_Job_Listing_Meta::get_instance($post->ID);

	$latitude = $meta_obj->get_post_meta( 'map_location_latitude' );
	$longitude = $meta_obj->get_post_meta( 'map_location_longitude' );

	$author_id = freeio_get_post_author($post->ID);
	$employer_id = WP_Freeio_User::get_employer_by_user_id($author_id);

	$url = '';
	if ( $meta_obj->check_post_meta_exist('logo') && ($logo_url = $meta_obj->get_post_meta( 'logo' )) ) {
		$logo_id = WP_Freeio_Job_Listing::get_post_meta($post->ID, 'logo_id', true);
		if ( $logo_id ) {
			$url = wp_get_attachment_image_url($logo_id, 'thumbnail');
		} else {
			$url = $logo_url;
		}
	} elseif ( has_post_thumbnail($employer_id) ) {
		$url = get_the_post_thumbnail_url($employer_id, 'thumbnail');
	}

	echo 'data-latitude="'.esc_attr($latitude).'" data-longitude="'.esc_attr($longitude).'" data-img="'.esc_url($url).'" data-logo="'.esc_url($url).'"';
}

function freeio_job_display_meta($post, $meta_key, $icon = '', $show_title = false, $suffix = '', $echo = false) {
	$meta_obj = WP_Freeio_Job_Listing_Meta::get_instance($post->ID);

	ob_start();
	if ( $meta_obj->check_post_meta_exist($meta_key) && ($value = $meta_obj->get_post_meta( $meta_key )) ) {
		?>
		<div class="job-meta with-<?php echo esc_attr($show_title ? 'icon-title' : 'icon'); ?>">

			<div class="job-meta">

				<?php if ( !empty($show_title) ) {
					$title = $meta_obj->get_post_meta_title($meta_key);
				?>
					<span class="title-meta">
						<?php echo esc_html($title); ?>
					</span>
				<?php } ?>

				<?php if ( !empty($icon) ) { ?>
					<i class="<?php echo esc_attr($icon); ?>"></i>
				<?php } ?>
				<?php
					if ( is_array($value) ) {
						echo implode(', ', $value);
					} else {
						echo esc_html($value);
					}
				?>
				<?php echo trim($suffix); ?>
			</div>

		</div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function freeio_job_display_custom_field_meta($post, $meta_key, $icon = '', $show_title = false, $suffix = '', $echo = false) {
	$meta_obj = WP_Freeio_Job_Listing_Meta::get_instance($post->ID);

	ob_start();
	if ( $meta_obj->check_custom_post_meta_exist($meta_key) && ($value = $meta_obj->get_custom_post_meta( $meta_key )) ) {
		?>
		<div class="job-meta with-<?php echo esc_attr($show_title ? 'icon-title' : 'icon'); ?>">

			<div class="job-meta">

				<?php if ( !empty($show_title) ) {
					$title = $meta_obj->get_custom_post_meta_title($meta_key);
				?>
					<span class="title-meta">
						<?php echo esc_html($title); ?>
					</span>
				<?php } ?>

				<?php if ( !empty($icon) ) { ?>
					<i class="<?php echo esc_attr($icon); ?>"></i>
				<?php } ?>
				<?php
					if ( is_array($value) ) {
						echo implode(', ', $value);
					} else {
						echo esc_html($value);
					}
				?>
				<?php echo trim($suffix); ?>
			</div>

		</div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}



// Job Archive hooks
function freeio_job_display_filter_btn() {
	$layout_type = freeio_get_jobs_layout_type();
	$layout_sidebar = freeio_get_jobs_layout_sidebar();
	$filter_sidebar = 'jobs-filter-sidebar';
	if ( ($layout_type == 'half-map' || ($layout_type == 'default' && $layout_sidebar == 'main' && freeio_get_jobs_show_offcanvas_filter() ) || ($layout_type == 'top-map' && $layout_sidebar == 'main' && freeio_get_jobs_show_offcanvas_filter())) && is_active_sidebar( $filter_sidebar ) ) {
		?>
		<div class="filter-in-sidebar-wrapper">
			<span class="filter-in-sidebar"><svg width="14" height="12" viewBox="0 0 14 12" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<path d="M3.06254 0.9375L3.06248 9.7035L3.78979 8.97725L4.58529 9.77275L2.50004 11.858L0.414795 9.77275L1.21029 8.97725L1.93748 9.7035L1.93754 0.9375H3.06254ZM8.12504 9.375V10.5H5.31254V9.375H8.12504ZM9.81254 6.5625V7.6875H5.31254V6.5625H9.81254ZM11.5 3.75V4.875H5.31254V3.75H11.5ZM13.1875 0.9375V2.0625H5.31254V0.9375H13.1875Z" fill="currentColor"/>
</svg><span class="text"><?php esc_html_e('Filter', 'freeio'); ?></span></span>
		</div>
		<?php
	}
}


function freeio_job_display_report_icon($post) {
	?>
	<a data-toggle="tooltip" href="#job-report-wrapper-<?php echo esc_attr($post->ID); ?>" class="btn-show-popup btn-view-job-report" title="<?php echo esc_attr_e('Report this job', 'freeio'); ?>"><i class="fas fa-exclamation-triangle fa-angle-right"></i></a>

	<div id="job-report-wrapper-<?php echo esc_attr($post->ID); ?>" class="job-report-wrapper mfp-hide">
		<div class="inner">
			<a href="javascript:void(0);" class="close-magnific-popup ali-right"><i class="ti-close"></i></a>

			<h2 class="widget-title"><span><?php esc_html_e('Report this job', 'freeio'); ?></span></h2>
			<div class="content">
				<form method="post" action="?" class="report-form-wrapper">
                    <div class="form-group">
                        <input type="text" class="form-control" name="subject" placeholder="<?php esc_attr_e( 'Subject', 'freeio' ); ?>" required="required">
                    </div><!-- /.form-group -->

		            <div class="form-group">
		                <textarea class="form-control" name="message" placeholder="<?php esc_attr_e( 'Message', 'freeio' ); ?>" required="required"></textarea>
		            </div><!-- /.form-group -->

		            <?php if ( WP_Freeio_Recaptcha::is_recaptcha_enabled() ) { ?>
		                <div id="recaptcha-report-job-form" class="ga-recaptcha" data-sitekey="<?php echo esc_attr(wp_freeio_get_option( 'recaptcha_site_key' )); ?>"></div>
		            <?php } ?>

		            <input type="hidden" name="post_id" value="<?php echo esc_attr($post->ID); ?>">
		            <button class="button btn btn-theme btn-outline w-100" name="submit-report"><?php echo esc_html__( 'Send Report', 'freeio' ); ?><i class="flaticon-right-up next"></i></button>
		        </form>
			</div>
		</div>
	</div>
	<?php
}