<?php
/*
Plugin Name: ExpressBar
Plugin URI: https://spigotdesign.com/
Description:  Custom notification bar.
Version: 1.0.0
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
			$exb_sticky_header = exb_get_option('exb_sticky_header');
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
				
				if ( $exb_sticky_header== 'yes') {
					$classes[] = 'sticky-header'; 
				}

				/* Remove remain at top option - default yes
				if ( $exb_remain_top == 'remain-top' ) $classes[] = 'dwpb-remain-top';
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

	function dwpb() {
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

		$dwpbcd_link_text = exb_get_option('dwpbcd_link_text');
		$dwpbcd_link_url = exb_get_option('dwpbcd_link_url');
		$exbcd_link_target = exb_get_option('exbcd_link_target');

		$dwpb_link_text = exb_get_option('exb_link_text');
		$dwpb_link_url = exb_get_option('dwpb_link_url');
		$dwpb_link_target = exb_get_option('exb_link_target');

		//$exb_font_family = exb_get_option('exb_font_family');
		// $dwpb_font_size = exb_get_option('dwpb_font_size');
		$dwpb_background_color = exb_get_option('dwpb_background_color');
		$dwpb_background_image = exb_get_option('dwpb_background_image');
		$dwpb_font_color = exb_get_option('dwpb_font_color');
		$dwpb_border_color = exb_get_option('dwpb_border_color');
		$dwpb_link_color = exb_get_option('dwpb_link_color');
		$dwpb_link_style = exb_get_option('dwpb_link_style');
		$dwpb_button_color = exb_get_option('dwpb_button_color');
		$dwpb_custon_style = exb_get_option('dwpb_custon_style');

		$dwpb_link = '';
		if ( $dwpb_link_text != '' ) {
			$dwpb_link = ' <a class="'. $dwpb_link_style .'" href="'.$dwpb_link_url.' " target="'. $dwpb_link_target .'"" >'.$dwpb_link_text.'</a>';
		}

		$dwpbcd_link = '';
		if ( $dwpbcd_link_text != '' ) {
			$dwpbcd_link = ' <a class="'. $dwpb_link_style .'" href="'.$dwpbcd_link_url.' " target="'. $exbcd_link_target .'"" >'.$dwpbcd_link_text.'</a>';
		}
	?>
		<style>
			


			<?php if( $dwpb_background_color != '' ) : ?>
			#expressbar,
			.exb-action {
				background-color: <?php echo $dwpb_background_color; ?>;
			}
			<?php endif; ?>

			<?php if( $dwpb_background_image != '' ) : ?>
			#expressbar {
				background-image: url(<?php echo $dwpb_background_image; ?>);
				background-position: center;
				background-size: 100% auto;
			}
			<?php endif; ?>

			<?php if( $dwpb_font_color != '' ) : ?>
			#expressbar,
			.exb-action,
			body.exb-allow-close.expressbar-open .exb-close {
				color: <?php echo $dwpb_font_color; ?>;
			}
			<?php endif; ?>
			<?php /* Remove font based options from styles
			<?php if( $dwpb_font_size != '' ) : ?>
			#expressbar {
				font-size: <?php echo $dwpb_font_size; ?>px;
			}
			<?php endif; ?>

			<?php if( $dwpb_font_size > 20 ) : ?>
			#expressbar {
				line-height: 1.2;
			}
			<?php endif; ?>
			*/ ?>

			<?php if( $dwpb_border_color != '' ) : ?>
			#expressbar {
				border-color: <?php echo $dwpb_border_color; ?>;
			}
			<?php endif; ?>

			<?php if( $dwpb_border_color == '' ) : ?>
			#expressbar {
				border-width: 0;
			}
			<?php endif; ?>

			<?php if( $dwpb_link_color != '' ) : ?>
			#expressbar a {
				color: <?php echo $dwpb_link_color; ?>;
			}
			<?php endif; ?>

			<?php if( $dwpb_button_color != '' ) : ?>
			#expressbar .dwpb-button {
				background-color: <?php echo $dwpb_button_color; ?>;
			}
			<?php endif; ?>

			<?php if( $dwpb_custon_style != '' ) : ?>
				<?php echo $dwpb_custon_style ?>
			<?php endif; ?>
		</style>
		
		<div id="expressbar" class=" <?php echo $exb_remain_top; ?> ">
			<div class="exb-inner">
				<?php 
					$dwpbcd_hide = 'hide';
					$dwpb_hide = '';
					if ($expcd_use == 'yes') {
						$dwpbcd_hide = '';
						$dwpb_hide = 'hide';
					}

					$exb_bar_text = exb_get_option('exb_bar_text');
					if ( $exb_bar_text == '' ) {
						$exb_bar_text = __('Hello. Add your message here.','expressbar');
					}

					$dwpbcd_text = exb_get_option('dwpbcd_text');
					if ( $dwpbcd_text == '' ) {
						$dwpbcd_text = __('Hello. Add your message here.','expressbar');
					}
				?>

				<div class="exb-message <?php echo $dwpb_hide; ?>">
					<span class="exb-content"><?php echo $exb_bar_text; ?></span>
					<?php echo $dwpb_link; ?>
				</div>
					
				<div class="exb-countdown <?php echo $dwpbcd_hide; ?>">
					<div class="exb-counter"></div>
					<span class="dwpbcd-content"><?php echo $dwpbcd_text; ?></span>
					<?php echo $dwpbcd_link; ?>
				</div>
			</div>
		</div>
		<?php 
			$exb_close = exb_get_option('exb_close');
			$dwpb_action_class = 'exb-action';
			if ($exb_close == 'yes') {
				$dwpb_action_class = 'exb-close';
			}
		?>
		<span class="<?php echo $dwpb_action_class; ?>"></span>
	<?php

	endif; // Show on
	}

	$exb_enable = exb_get_option('exb_enable');
	$exb_start = strtotime(exb_get_option('exb_start'));
	$exb_end = strtotime(exb_get_option('exb_end'));	
	$dwpb_timezone = strtotime(date_i18n('Y-m-d G:i:s'));

	if ( ( $exb_start < $dwpb_timezone && ( $dwpb_timezone < $exb_end || $exb_end == '' ) ) && $exb_enable == 'yes' ) {
		add_action( 'wp_footer', 'dwpb', 100);
	}
	add_action( 'dwpb_previvew', 'dwpb');

	// Enqueue scripts
	function dwpb_scripts() {
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
		wp_enqueue_style( 'dwpb_style', EXB_PATH . 'assets/css/main.css');

		if ( ! wp_script_is( 'jquery', 'enqueued' )) {
			wp_enqueue_script( 'jquery');
		}

		if ( ! wp_script_is( 'jquery.countdown.js', 'enqueued' )) {
			wp_enqueue_script( 'dwpb_countdown', EXB_PATH . 'assets/js/vendor/jquery.countdown.js',true);
		}

		if ( ! wp_script_is( 'jquery.cookie.js', 'enqueued' )) {
			wp_enqueue_script( 'dwpb_cookie', EXB_PATH . 'assets/js/vendor/jquery.cookie.js',true);
		}

		if ( ! wp_style_is( 'dashicons', 'enqueued' ))  {
			wp_enqueue_style( 'dashicons' );
		}

		wp_enqueue_script( 
			'dwpb_script', 
			EXB_PATH . 'assets/js/main.js', 
			array(
				'jquery',
				'dwpb_countdown',
				'dwpb_cookie'
			),
			'1.0',
			true
		);

		$timeleft = '';
		if ( exb_get_option('exbcd_time_left') != '' ) {
			$timeleft = exb_get_option('exbcd_time_left');
		}

		$timezone_format = _x('Y-m-d G:i:s', 'timezone date format');
		$dwpb_reset_cookie_value = get_option( 'dwpb_reset_cookie', 2 );

		wp_localize_script( 'dwpb_countdown', 'dwpb', array(
			'timeleft'	=> strtotime($timeleft) - strtotime(date_i18n($timezone_format)),
			'reset_cookie' => $dwpb_reset_cookie_value
		));

		endif; // Show on
	}
	if ( ( $exb_start < $dwpb_timezone && ( $dwpb_timezone < $exb_end || $exb_end == '' ) ) && $exb_enable == 'yes' ) {
		add_action( 'wp_footer', 'dwpb_scripts');
	}

	// Enqueue admin scripts
	function dwpb_admin_scripts() {
		if ( ! wp_script_is( 'jquery', 'enqueued' )) {
			wp_enqueue_script( 'jquery');
		}

		// Front end
		wp_enqueue_style( 'dwpb_style', EXB_PATH . 'assets/css/main.css');
		
		if ( ! wp_script_is( 'jquery.countdown.js', 'enqueued' )) {
			wp_enqueue_script( 'dwpb_countdown', EXB_PATH . 'assets/js/vendor/jquery.countdown.js',true);
		}

		$timeleft = '';
		if ( exb_get_option('exbcd_time_left') != '' ) {
			$timeleft = exb_get_option('exbcd_time_left');
		}

		$timezone_format = _x('Y-m-d G:i:s', 'timezone date format');
		wp_localize_script( 'dwpb_countdown', 'dwpb', array(
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

		wp_enqueue_style( 'dwpb_admin_style', EXB_PATH . 'assets/css/admin.css');
		
		wp_enqueue_script( 
			'dwpb_admin_script', 
			EXB_PATH . 'assets/js/admin.js',
			array(
				'jquery',
				'datetimepicker_jquery',
				'wp-color-picker',
				'dwpb_countdown'
			),
			'1.0',
			true
		);
	}
	add_action('admin_enqueue_scripts','dwpb_admin_scripts');
}
?>