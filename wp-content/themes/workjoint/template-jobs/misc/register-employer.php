<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="register-form-wrapper">
  	<div class="container-form">

  		<?php if ( sizeof($form_obj->errors) ) : ?>
			<ul class="alert alert-danger errors">
				<?php foreach ( $form_obj->errors as $message ) { ?>
					<div class="message_line danger">
						<?php echo wp_kses_post( $message ); ?>
					</div>
				<?php
				}
				?>
			</ul>
		<?php endif; ?>

		<?php if ( sizeof($form_obj->success_msg) ) : ?>
			<ul class="alert alert-info success">
				<?php foreach ( $form_obj->success_msg as $message ) { ?>
					<div class="message_line info">
						<?php echo wp_kses_post( $message ); ?>
					</div>
				<?php
				}
				?>
			</ul>
		<?php endif; ?>

  		<?php
  			$html_output = '';
  			if ( WP_Freeio_Recaptcha::is_recaptcha_enabled() ) {
            	$html_output .= '<div id="recaptcha-register-freelancer" class="ga-recaptcha margin-bottom-25" data-sitekey="'.esc_attr(wp_freeio_get_option( 'recaptcha_site_key' )).'"></div>';
      		}

      		$page_id = wp_freeio_get_option('terms_conditions_page_id');
      		$page_id = WP_Freeio_Mixes::get_lang_post_id($page_id);
			if ( !empty($page_id) ) {
				$page_url = get_permalink($page_id);
				$html_output .= '
				<div class="form-group">
					<label for="register-terms-and-conditions">
						<input type="checkbox" name="terms_and_conditions" value="on" id="register-terms-and-conditions" required>
						'.sprintf(__('You accept our <a href="%s">Terms and Conditions and Privacy Policy</a>', 'freeio'), esc_url($page_url)).'
					</label>
				</div>';
			}

			echo cmb2_get_metabox_form( $metaboxes_form, $post_id, array(
				'form_format' => '<form action="" class="cmb-form %1$s" method="post" id="%1$s_'.rand(0000,9999).'" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="'.$form_obj->get_form_name().'" value="'.$form_obj->get_form_name().'"><input type="hidden" name="object_id" value="%2$s">%3$s'.$html_output.'<button type="submit" name="submit-cmb-register-freelancer" class="btn btn-theme w-100">%4$s<i class="flaticon-right-up next"></i></button></form>',
				'save_button' => $submit_button_text,
			) );
		?>

    </div>

</div>
