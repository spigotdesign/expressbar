<?php
/*
Plugin Name: ExpressBar
Plugin URI: https://spigotdesign.com/
Description:  Custom notification bar.
Version: 1.0.1
Author: Spigot Design
Author URI: https://spigotdesign.com
*/

if ( ! function_exists('exb')) {

	define( 'EXB_FOLDER', plugin_dir_path(__FILE__) );
	define( 'EXB_PATH', plugin_dir_url(__FILE__) );

	include_once EXB_FOLDER . 'expressbar-option.php';

	if ( ! function_exists( 'exb_get_option' )) {
		function exb_get_option( $option_name, $default = '' ) {
			$options = get_option( $option_name );
			if( $options != '' ) {
				return $options;
			}
			return $default; 
		}	
	}

	if ( ! function_exists( 'exb_body_class' )) {
		function exb_body_class($classes) {
			$exb_enabled = exb_get_option('exb_enable');
			$exb_push_page = exb_get_option('exp_push_page');
			$exb_remain_top = exb_get_option('exb_remain_top');
			// $exb_show_bottom = exb_get_option('exb_show_bottom');
			$exb_close = exb_get_option('exb_close');
			$exb_responsive_extra_small = exb_get_option('exb_responsive_extra_small');
			$exb_responsive_small = exb_get_option('exb_responsive_small');
			$exb_responsive_medium = exb_get_option('exb_responsive_medium');
			$exb_responsive_large = exb_get_option('exb_responsive_large');

			$current_theme = wp_get_theme();
			if($exb_enabled == 'yes') {
				$classes[] = 'expressbar-enabled';
				if ( $exb_push_page == 'push') {
					//$classes[] = 'exb-push-page';
				} else {
					//$classes[] = 'exb-cover-page';
				}
				/* remove allow-close
				if ( $exb_close == 'yes' ) {
					$classes[] = 'exb-allow-close';
				} */
				
				/* Remove remain at top option - default yes
				if ( $exb_remain_top == 'remain-top' ) $classes[] = 'exb-remain-top';
				*/
				if ($exb_responsive_extra_small) $classes[] = 'exb_responsive_extra_small';
				if ($exb_responsive_small) $classes[] = 'exb_responsive_small';
				if ($exb_responsive_medium) $classes[] = 'exb_responsive_medium';
				if ($exb_responsive_large) $classes[] = 'exb_responsive_large';
		};


			return $classes;
		}
		add_filter('body_class','exb_body_class');
	}

	// Mirror the front-end body class onto the settings page so the live
	// preview is driven by the same body.expressbar-enabled scoped styles.
	if ( ! function_exists( 'exb_admin_body_class' )) {
		function exb_admin_body_class( $classes ) {
			$screen = get_current_screen();
			if ( $screen && $screen->id === 'settings_page_expressbar' ) {
				$classes .= ' expressbar-enabled';
			}
			return $classes;
		}
		add_filter('admin_body_class','exb_admin_body_class');
	}

	function expressbar() {
		$current_theme = wp_get_theme();
		$is_front_page = exb_get_option('exb_front_page', false);
		$is_archives = exb_get_option('exb_archives', false);
		$is_tags = exb_get_option('exb_tags', false);
		$is_single_post = exb_get_option('exb_single_post', false);
		$is_single_page = exb_get_option('exb_single_page', false);
		if ( 
			( $is_front_page && is_front_page() ) || 
			( $is_archives && is_archive() ) || 
			( $is_tags && is_tag() ) ||
			( $is_single_post && is_single() ) ||
			( $is_single_page && is_page() ) ||
			( ! $is_front_page && ! $is_archives && ! $is_tags && ! $is_single_post && ! $is_single_page ) || 
			is_admin()
		) :

		$exb_remain_top = exb_get_option('exb_remain_top');
		$expcd_use = exb_get_option('expcd_use');

		$exbcd_link_text = exb_get_option('exbcd_link_text');
		$exbcd_link_url = exb_get_option('exbcd_link_url');
		$exbcd_link_target = exb_get_option('exbcd_link_target');

		$exb_link_text = exb_get_option('exb_link_text');
		$exb_link_url = exb_get_option('exb_link_url');
		$exb_link_target = exb_get_option('exb_link_target');

		//$exb_font_family = exb_get_option('exb_font_family');
		// $exb_font_size = exb_get_option('exb_font_size');
		$exb_background_color = exb_get_option('exb_background_color');
		// $exb_background_image = exb_get_option('exb_background_image');
		$exb_font_color = exb_get_option('exb_font_color');
		$exb_border_color = exb_get_option('exb_border_color');
		$exb_link_color = exb_get_option('exb_link_color');
		$exb_link_style = exb_get_option('exb_link_style');
		$exb_button_color = exb_get_option('exb_button_color');
		$exb_custom_style = exb_get_option('exb_custom_style');

		$exb_link = '';
		if ( $exb_link_text != '' ) {
			$exb_link = ' <a class="'. esc_attr( $exb_link_style ) .'" href="'. esc_url( $exb_link_url ) .'" target="'. esc_attr( $exb_link_target ) .'">' . esc_html( $exb_link_text ) . '</a>';
		}

		$exbcd_link = '';
		if ( $exbcd_link_text != '' ) {
			$exbcd_link = ' <a class="'. esc_attr( $exb_link_style ) .'" href="'. esc_url( $exbcd_link_url ) .'" target="'. esc_attr( $exbcd_link_target ) .'">' . esc_html( $exbcd_link_text ) . '</a>';
		}
	?>
		<style>
			:root {
				<?php if( $exb_background_color != '' ) : ?>
				--exb-bg: <?php echo esc_attr( $exb_background_color ); ?>;
				<?php endif; ?>
				<?php if( $exb_font_color != '' ) : ?>
				--exb-text-color: <?php echo esc_attr( $exb_font_color ); ?>;
				<?php endif; ?>
				<?php if( $exb_border_color != '' ) : ?>
				--exb-border-color: <?php echo esc_attr( $exb_border_color ); ?>;
				<?php else : ?>
				--exb-border-width: 0;
				<?php endif; ?>
				<?php if( $exb_link_color != '' ) : ?>
				--exb-link-color: <?php echo esc_attr( $exb_link_color ); ?>;
				<?php endif; ?>
				<?php if( $exb_button_color != '' ) : ?>
				--exb-button-bg: <?php echo esc_attr( $exb_button_color ); ?>;
				<?php endif; ?>
			}
			<?php if( $exb_custom_style != '' ) : ?>
			<?php echo wp_strip_all_tags( $exb_custom_style ); ?>
			<?php endif; ?>
		</style>
		
		<div id="expressbar" class="exb-bar <?php echo esc_attr( $exb_remain_top ); ?> ">
			<div class="exb-inner">
				<?php 
					$exbcd_hide = 'hide';
					$exb_hide = '';
					if ($expcd_use == 'yes') {
						$exbcd_hide = '';
						$exb_hide = 'hide';
					}

					$exb_bar_text = exb_get_option('exb_bar_text');
					if ( $exb_bar_text == '' ) {
						$exb_bar_text = __('Hello. Add your message here.','expressbar');
					}

					$exbcd_text = exb_get_option('exbcd_text');
					if ( $exbcd_text == '' ) {
						$exbcd_text = __('Hello. Add your message here.','expressbar');
					}
				?>

				<div class="exb-message <?php echo esc_attr( $exb_hide ); ?>">
					<span class="exb-content"><?php echo esc_html( $exb_bar_text ); ?></span>
					<?php echo $exb_link; ?>
				</div>

				<div class="exb-countdown <?php echo esc_attr( $exbcd_hide ); ?>">
					<div class="exb-counter"></div>
					<span class="exbcd-content"><?php echo esc_html( $exbcd_text ); ?></span>
					<?php echo $exbcd_link; ?>
				</div>
			</div>
		</div>
		<?php 
			$exb_close = exb_get_option('exb_close');
			$exb_action_class = 'exb-action';
			if ($exb_close == 'yes') {
				$exb_action_class = 'exb-close';
			}
		?>
		<span class="<?php echo esc_attr( $exb_action_class ); ?>"></span>
	<?php

	endif; // Show on
	}

	$exb_enable = exb_get_option('exb_enable');
	$exb_start = strtotime(exb_get_option('exb_start'));
	$exb_end = strtotime(exb_get_option('exb_end'));	
	$exb_timezone = strtotime(date_i18n('Y-m-d G:i:s'));

	if ( ( $exb_start < $exb_timezone && ( $exb_timezone < $exb_end || $exb_end === false ) ) && $exb_enable == 'yes' ) {
		add_action( 'wp_footer', 'expressbar', 100);
	}
	add_action( 'exb_preview', 'expressbar');

	// Enqueue scripts
	function exb_scripts() {
		$is_front_page = exb_get_option('exb_front_page', false);
		$is_archives = exb_get_option('exb_archives', false);
		$is_tags = exb_get_option('exb_tags', false);
		$is_single_post = exb_get_option('exb_single_post', false);
		$is_single_page = exb_get_option('exb_single_page', false);
		if ( 
			( $is_front_page && is_front_page() ) || 
			( $is_archives && is_archive() ) || 
			( $is_tags && is_tag() ) ||
			( $is_single_post && is_single() ) ||
			( $is_single_page && is_page() ) ||
			( ! $is_front_page && ! $is_archives && ! $is_tags && ! $is_single_post && ! $is_single_page )
		) :

		// Front end
		wp_enqueue_style( 'exb_style', EXB_PATH . 'assets/css/main.css');

		if ( ! wp_script_is( 'jquery', 'enqueued' )) {
			wp_enqueue_script( 'jquery');
		}

		if ( ! wp_script_is( 'jquery.countdown.js', 'enqueued' )) {
			wp_enqueue_script( 'exb_countdown', EXB_PATH . 'assets/js/vendor/jquery.countdown.js',true);
		}

		if ( ! wp_script_is( 'jquery.cookie.js', 'enqueued' )) {
			wp_enqueue_script( 'exb_cookie', EXB_PATH . 'assets/js/vendor/jquery.cookie.js',true);
		}

		if ( ! wp_style_is( 'dashicons', 'enqueued' ))  {
			wp_enqueue_style( 'dashicons' );
		}

		wp_enqueue_script( 
			'exb_script', 
			EXB_PATH . 'assets/js/main.js', 
			array(
				'jquery',
				'exb_countdown',
				'exb_cookie'
			),
			'1.0',
			true
		);

		$timeleft = '';
		if ( exb_get_option('exbcd_time_left') != '' ) {
			$timeleft = exb_get_option('exbcd_time_left');
		}

		$timezone_format = _x('Y-m-d G:i:s', 'timezone date format');
		$exb_reset_cookie_value = get_option( 'exb_reset_cookie', 2 );

		wp_localize_script( 'exb_countdown', 'exb', array(
			'timeleft'	   => strtotime($timeleft) - strtotime(date_i18n($timezone_format)),
			'reset_cookie' => $exb_reset_cookie_value,
		));

		endif; // Show on
	}
	if ( ( $exb_start < $exb_timezone && ( $exb_timezone < $exb_end || $exb_end === false ) ) && $exb_enable == 'yes' ) {
		add_action( 'wp_footer', 'exb_scripts');
	}

	// Enqueue admin scripts
	function exb_admin_scripts() {
		if ( ! wp_script_is( 'jquery', 'enqueued' )) {
			wp_enqueue_script( 'jquery');
		}

		// Front end
		wp_enqueue_style( 'exb_style', EXB_PATH . 'assets/css/main.css');
		
		if ( ! wp_script_is( 'jquery.countdown.js', 'enqueued' )) {
			wp_enqueue_script( 'exb_countdown', EXB_PATH . 'assets/js/vendor/jquery.countdown.js',true);
		}

		$timeleft = '';
		if ( exb_get_option('exbcd_time_left') != '' ) {
			$timeleft = exb_get_option('exbcd_time_left');
		}

		$timezone_format = _x('Y-m-d G:i:s', 'timezone date format');
		wp_localize_script( 'exb_countdown', 'exb', array(
			'timeleft'	=> strtotime($timeleft) - strtotime(date_i18n($timezone_format)),
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
		));

		// Back end
		if ( ! wp_style_is( 'wp-color-picker', 'enqueued' )) {
			wp_enqueue_style( 'wp-color-picker' );
		}

		if ( ! wp_script_is( 'jquery.datetimepicker.css', 'enqueued' )) {
			wp_enqueue_script( 'datetimepicker_jquery', EXB_PATH . 'assets/js/vendor/datetimepicker/jquery.datetimepicker.js',true);
		}

		if ( ! wp_style_is( 'jquery.datetimepicker.css', 'enqueued' )) {
			wp_enqueue_style( 'datetimepicker_style', EXB_PATH . 'assets/js/vendor/datetimepicker/jquery.datetimepicker.css',true);
		}

		wp_enqueue_style( 'exb_admin_style', EXB_PATH . 'assets/css/admin.css');
		
		wp_enqueue_script( 
			'exb_admin_script', 
			EXB_PATH . 'assets/js/admin.js',
			array(
				'jquery',
				'datetimepicker_jquery',
				'wp-color-picker',
				'exb_countdown'
			),
			'1.0',
			true
		);
	}
	add_action('admin_enqueue_scripts','exb_admin_scripts');
}
?>