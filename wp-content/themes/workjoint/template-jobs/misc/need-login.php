<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = wp_freeio_get_option('login_register_page_id');
$page_url = get_permalink($page_id);
?>
<div class="box-dashboard-wrapper">
	<div class="alert alert-warning not-allow-wrapper">
		<p class="account-sign-in"><?php esc_html_e( 'You need to be signed in to access this page.', 'freeio' ); ?> <a class="button" href="<?php echo esc_url( $page_url ); ?>"><?php esc_html_e( 'Sign in', 'freeio' ); ?></a></p>
	</div><!-- /.alert -->
</div>