<?php
// Prevent file from being loaded directly
if ( ! defined( 'ABSPATH' ) ) {
	die('-1');
}

// Early nonce check
if ( ! isset( $_GET['et_pb_preview_nonce'] ) || ! wp_verify_nonce( $_GET['et_pb_preview_nonce'], 'et_pb_preview_nonce' ) ) {
	wp_die( __( 'Authentication failed. You cannot preview this item.', 'et_builder' ) );
}

// Logged in check
if ( ! is_user_logged_in() ) {
	wp_die( __( 'Authentication failed. You are not logged in.', 'et_builder' ) );
}

// Early permission check
if ( ! current_user_can( 'edit_posts' ) ) {
	wp_die( __( 'Authentication failed. You have no permission to preview this item.', 'et_builder' ) );
}

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />

		<title><?php wp_title(); ?></title>

		<?php do_action( 'et_head_meta' ); ?>

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

		<?php $template_directory_uri = get_template_directory_uri(); ?>
		<!--[if lt IE 9]>
		<script src="<?php echo esc_url( $template_directory_uri . '/js/html5.js"' ); ?>" type="text/javascript"></script>
		<![endif]-->

		<script type="text/javascript">
			document.documentElement.className = 'js';
		</script>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="page-container">
			<div id="main-content">
				<div class="container">
					<div id="<?php echo esc_attr( apply_filters( 'et_pb_preview_wrap_id', 'content' ) ); ?>">
					<div class="<?php echo esc_attr( apply_filters( 'et_pb_preview_wrap_class', 'entry-content post-content entry content' ) ); ?>">

					<?php
						if ( isset( $_POST['shortcode' ] ) ) {
							if( ! isset( $_POST['et_pb_preview_nonce'] ) || ! wp_verify_nonce( $_POST['et_pb_preview_nonce'], 'et_pb_preview_nonce' ) ) {
								// Auth nonce
								printf( '<p class="et-pb-preview-message">%1$s</p>', esc_html__( 'Authentication failed. You cannot preview this item.', 'et_builder' ) );
							} elseif( ! current_user_can( 'edit_posts' ) ) {
								// Auth user
								printf( '<p class="et-pb-preview-message">%1$s</p>', esc_html__( 'Authentication failed. You have no permission to preview this item.', 'et_builder' ) );
							} else {
								$content = apply_filters( 'the_content', wp_unslash( $_POST['shortcode'] ) );
								$content = str_replace( ']]>', ']]&gt;', $content );
								echo $content;
							}
						} else {
							printf( '<p class="et-pb-preview-loading"><span>%1$s</span></p>', esc_html__( 'Loading preview...', 'et_builder' ) );
						}
					?>

					</div> <!-- .entry-content.post-content.entry -->
					</div> <!-- #content -->
					<div class="et_pb_modal_overlay link-disabled">
						<div class="et_pb_prompt_modal">
							<h3><?php _e( 'Link Disabled', 'et_builder' ); ?></h3>
							<p><?php _e( 'During preview, link to different page is disabled', 'et_builder' ); ?></p>

							<div class="et_pb_prompt_buttons">
								<a href="#" class="et_pb_prompt_proceed"><?php _e( 'Close', 'et_builder' ); ?></a>
							</div>
						</div><!-- .et_pb_prompt_modal -->
					</div><!-- .et_pb_modal_overlay -->
				</div><!-- .container -->
			</div><!-- #main-content -->
		</div> <!-- #page-container -->
		<?php wp_footer(); ?>
	</body>
</html>