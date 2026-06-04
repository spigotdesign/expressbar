<?php

// create custom plugin settings menu
add_action('admin_menu', 'exb_create_menu');

function exb_create_menu() {
    add_submenu_page('options-general.php', 'ExpressBar', 'ExpressBar', 'administrator', 'expressbar', 'exb_settings_page');
    //call register settings function
    add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	$exb_settings_array = array(
		//General Setting
		'exb_enable',
		'exb_start',
		'exb_end',
		'exb_close',
		'exb_remain_top',
		//'exp_push_page',
		//'exb_show_bottom',
		'exb_responsive_extra_small',
		'exb_responsive_small',
		'exb_responsive_medium',
		'exb_responsive_large',
		'exb_front_page',
		'exb_archives',
		'exb_tags',
		'exb_single_post',
		'exb_single_page',
		'expcd_use',

		//Configure ExpressBar coutdown
		'exbcd_time_left',
		//'exbcd_text',
		//'exbcd_link_text',
		//'exbcd_link_url',
		'exbcd_link_target',

		//Configure ExpressBar
		//'exb_bar_text' ,
		//'exb_link_text',
		//'exb_link_url',
		'exb_link_target',

		//Choose the Style
		//'exb_font_family',
		// 'exb_font_size',
		'exb_background_color',
		// 'exb_background_image',
		'exb_font_color',
		'exb_border_color',
		'exb_link_color',
		'exb_link_style',
		'exb_button_color',
		'exb_custom_style',

	);

	foreach ($exb_settings_array as $value) {
		register_setting( 'exb-settings-group', $value );
	}

	$args = array(
		'type' => 'string', 
		'sanitize_callback' => 'sanitize_text_field',
		'default' => NULL,
	);

	$exb_textfield_array = array(
		'exb_bar_text',
		'exb_link_text',
		'exb_link_url',
		'exbcd_text',
		'exbcd_link_text',
		'exbcd_link_url',
	);
	
	foreach ($exb_textfield_array as $value) {
		register_setting( 'exb-settings-group', $value, $args );
	}
}

/**
* Registers a text field setting for Wordpress 4.7 and higher.
**/
function register_my_setting() {
    $args = array(
            'type' => 'string', 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => NULL,
            );
    register_setting( 'exb-settings-group', 'exb_bar_text', $args ); 
} 
add_action( 'admin_init', 'register_my_setting' );

function exb_settings_page() {
?>
<div class="wrap">
<h2>ExpressBar Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'exb-settings-group' ); ?>
    <?php do_settings_sections( 'exb-settings-group' ); ?>

	<?php do_action( 'exb_preview' ); ?>
    <div id="exb-steps">
		<h3><?php _e('General Settings','expressbar') ?></h3>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Enable ExpressBar?','expressbar') ?></th>
				<td>
					<?php
						$exb_enable = get_option('exb_enable');
						$exb_enable_select = '';
						if ( $exb_enable == 'yes' ) {
							$exb_enable_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input type="radio" name="exb_enable" value="no" checked><?php _e('No','expressbar') ?></label>
					<label style="margin-right: 50px;"><input type="radio" name="exb_enable" value="yes" <?php echo $exb_enable_select; ?> ><?php _e('Yes','expressbar') ?></label>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e('Start on','expressbar') ?></th>
				<td>
					<input class="regular-text exb_time_picker" type="text" name="exb_start" value="<?php echo get_option('exb_start'); ?>" />
					<span class="description"><?php _e('Leave blank if you want to start the bar immediately!') ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php _e('Stop on','expressbar') ?></th>
				<td>
					<input class="regular-text exb_time_picker" type="text" name="exb_end" value="<?php echo get_option('exb_end'); ?>" />
					<span class="description"><?php _e('Leave blank if you do not want to close the bar!') ?></span>
				</td>
			</tr>
			<?php /* Allow to close removed 
			<tr>
				<th scope="row"><?php _e('Allow to Close ExpressBar ?','expressbar') ?></th>
				<td>
					<?php
						$exb_close = get_option('exb_close');
						$exb_close_select = '';
						if ( $exb_close == 'yes' ) {
							$exb_close_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input type="radio" name="exb_close" value="no" checked><?php _e('No','expressbar') ?></label>
					<label style="margin-right: 50px;"><input type="radio" name="exb_close" value="yes" <?php echo $exb_close_select; ?> ><?php _e('Yes','expressbar') ?></label>
				</td>
			</tr> */ ?>
			<?php /* Removed remain at top and Push page down. These should be defaults
			<tr>
				<th scope="row"><?php _e('remain at top of page?','expressbar') ?></th>
				<td>
					<?php
						$exb_remain_top = get_option('exb_remain_top');
						$exb_remain_top_select = '';
						if ( $exb_remain_top == 'fixtop' ) {
							$exb_remain_top_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input class="remain-top" type="radio" name="exb_remain_top" value="remain-top" checked><?php _e('No','expressbar') ?></label>
					<label style="margin-right: 50px;"><input class="fixtop" type="radio" name="exb_remain_top" value="fixtop" <?php echo $exb_remain_top_select; ?> ><?php _e('Yes','expressbar') ?></label>
				</td>
			</tr>
			
			<tr>
				<th scope="row"><?php _e('Push page down?','expressbar') ?></th>
				<td>
					<?php
						$exp_push_page = get_option('exp_push_page');
						$exp_push_page_select = '';
						if ( $exp_push_page == 'push' ) {
							$exp_push_page_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input class="no-push" type="radio" name="exp_push_page" value="no-push" checked > <?php _e('No','expressbar') ?> </label>

					<label style="margin-right: 50px;"><input class="push-page" type="radio" name="exp_push_page" value="push" <?php echo $exp_push_page_select; ?> ><?php _e('Yes','expressbar') ?></label>
				</td>
			</tr> */ ?>
			

			<?php /* Remove abiltity to put at bottom
			<tr>
				<th scope="row"><?php _e('Show Expressbar at bottom','expressbar') ?></th>
				<td>
					<?php
						$exb_show_bottom = get_option('exb_show_bottom');
						$exb_show_bottom_select = '';
						if ( $exb_show_bottom == 'yes' ) {
							$exb_show_bottom_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input class="no-push" type="radio" name="exb_show_bottom" value="no" checked > <?php _e('No','expressbar') ?> </label>

					<label style="margin-right: 50px;"><input class="push-page" type="radio" name="exb_show_bottom" value="yes" <?php echo $exb_show_bottom_select; ?> ><?php _e('Yes','expressbar') ?></label>
				</td>
			</tr> */ ?>

			<tr>
				<th scope="row"><?php _e('Hide ExpressBar','expressbar') ?></th>
				<td>
					<?php
						$exb_responsive_extra_small = get_option('exb_responsive_extra_small');
						$exb_responsive_extra_small_select = '';
						if ( $exb_responsive_extra_small ) {
							$exb_responsive_extra_small_select = 'checked';
						}

						$exb_responsive_small = get_option('exb_responsive_small');
						$exb_responsive_small_select = '';
						if ( $exb_responsive_small ) {
							$exb_responsive_small_select = 'checked';
						}

						$exb_responsive_medium = get_option('exb_responsive_medium');
						$exb_responsive_medium_select = '';
						if ( $exb_responsive_medium ) {
							$exb_responsive_medium_select = 'checked';
						}

						$exb_responsive_large = get_option('exb_responsive_large');
						$exb_responsive_large_select = '';
						if ( $exb_responsive_large ) {
							$exb_responsive_large_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input type="checkbox" name="exb_responsive_extra_small" value="true" <?php echo $exb_responsive_extra_small_select; ?> > <?php _e('Phones < 768px','expressbar') ?> </label>

					<label style="margin-right: 50px;"><input type="checkbox" name="exb_responsive_small" value="true" <?php echo $exb_responsive_small_select; ?> ><?php _e('768px &le; Tablets < 992px ','expressbar') ?></label>

					<label style="margin-right: 50px;"><input type="checkbox" name="exb_responsive_medium" value="true" <?php echo $exb_responsive_medium_select; ?> ><?php _e('992 &le; Desktops < 1200 ','expressbar') ?></label>

					<label style="margin-right: 50px;"><input type="checkbox" name="exb_responsive_large" value="true" <?php echo $exb_responsive_large_select; ?> ><?php _e('Desktops (≥1200px)','expressbar') ?></label>
				</td>
			</tr>
			<tr>
				<td style="padding-top: 0; padding-bottom: 0; "></td>
				<td style="padding-top: 0; padding-bottom: 0; "><span class="description"><?php _e('Don\'t select any if you want to display the bar on all devices') ?></span>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php _e('Show ExpressBar on','expressbar') ?></th>
				<td>
					<?php
						$exb_front_page = get_option('exb_front_page');
						$exb_front_page_select = '';
						if ( $exb_front_page ) {
							$exb_front_page_select = 'checked';
						}

						$exb_archives = get_option('exb_archives');
						$exb_archives_select = '';
						if ( $exb_archives ) {
							$exb_archives_select = 'checked';
						}

						$exb_tags = get_option('exb_tags');
						$exb_tags_select = '';
						if ( $exb_tags ) {
							$exb_tags_select = 'checked';
						}

						$exb_single_post = get_option('exb_single_post');
						$exb_single_post_select = '';
						if ( $exb_single_post ) {
							$exb_single_post_select = 'checked';
						}

						$exb_single_page = get_option('exb_single_page');
						$exb_single_page_select = '';
						if ( $exb_single_page ) {
							$exb_single_page_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input type="checkbox" name="exb_front_page" value="true" <?php echo $exb_front_page_select; ?> > <?php _e('Front Page','expressbar') ?> </label>

					<label style="margin-right: 50px;"><input type="checkbox" name="exb_archives" value="true" <?php echo $exb_archives_select; ?> ><?php _e('Archives','expressbar') ?></label>

					<label style="margin-right: 50px;"><input type="checkbox" name="exb_tags" value="true" <?php echo $exb_tags_select; ?> ><?php _e('Tags','expressbar') ?></label>

					<label style="margin-right: 50px;"><input type="checkbox" name="exb_single_post" value="true" <?php echo $exb_single_post_select; ?> ><?php _e(' Single Posts','expressbar') ?></label>

					<label style="margin-right: 50px;"><input type="checkbox" name="exb_single_page" value="true" <?php echo $exb_single_page_select; ?> ><?php _e('Single Pages','expressbar') ?></label>
				</td>
			</tr>
			<tr>
				<td style="padding-top: 0; padding-bottom: 0; "></td>
				<td style="padding-top: 0; padding-bottom: 0; "><span class="description"><?php _e('Don\'t select any if you want to display the bar on all pages') ?></span>
				</td>
			</tr>
		</table>

		<h3><?php _e('Text Message Settings','expressbar') ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Text Message','expressbar') ?></th>
				<td>
					<input class="regular-text" type="text" name="exb_bar_text" placeholder="<?php _e('Hello. Add your message here.','expressbar'); ?>" value="<?php echo get_option('exb_bar_text'); ?>" />
				</td>
			</tr>

			<tr>
				<th scope="row"><?php _e('Link Text','expressbar') ?></th>
				<td>
					<input class="regular-text" type="text" name="exb_link_text" placeholder="<?php _e('Add your link text here.','expressbar'); ?>" value="<?php echo get_option('exb_link_text');  ?>" />
				</td>
			</tr>

			<tr class="exb-link-url">
				<th scope="row"><?php _e('Link URL','expressbar') ?></th>
				<td>
					<input class="regular-text" type="text" name="exb_link_url" placeholder="<?php _e('http://yoursite.com','expressbar'); ?>" value="<?php echo get_option('exb_link_url'); ?>" />
				</td>
			</tr>

			<tr class="exb-link-target">
				<th scope="row"><?php _e('Open link in a new tab?','expressbar') ?></th>
				<td>
					<?php
						$exb_link_target = get_option('exb_link_target');
						$exb_link_target_select = '';
						if ( $exb_link_target == '_blank' ) {
							$exb_link_target_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input type="radio" name="exb_link_target" value="_self" checked> <?php _e('No','expressbar'); ?> </label>

					<label style="margin-right: 50px;"><input type="radio" name="exb_link_target" value="_blank" <?php echo $exb_link_target_select; ?>> <?php _e('Yes','expressbar'); ?> </label>
				</td>
			</tr>
		</table>

		<h3><?php _e('Countdown Settings','expressbar') ?></h3>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Use Countdown?','expressbar') ?></th>
				<td>
					<?php
						$expcd_use = get_option('expcd_use');
						$expcd_use_select = '';
						if ( $expcd_use == 'yes' ) {
							$expcd_use_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input type="radio" name="expcd_use" value="no" checked> <?php _e('No','expressbar'); ?> </label>

					<label style="margin-right: 50px;"><input type="radio" name="expcd_use" value="yes" <?php echo $expcd_use_select; ?> > <?php _e('Yes','expressbar'); ?> </label>
				</td>
			</tr>


			<?php
				$exb_hide = 'hide';
				if (get_option('expcd_use') == 'yes') {
					$exb_hide = '';
				}
			?>
			<tr valign="top" class="exbcd <?php echo $exb_hide ?>">
				<th scope="row"><?php _e('Countdown Time to','expressbar') ?></th>
				<td>
					<input class="regular-text exb_time_picker" type="text" name="exbcd_time_left" value="<?php echo get_option('exbcd_time_left'); ?>" />
					<span class="description"><?php _e('This time is based on the server time of your site!') ?></span>
				</td>
			</tr>

			<tr valign="top" class="exbcd <?php echo $exb_hide ?>">
				<th scope="row"><?php _e('Countdown Text','expressbar') ?></th>
				<td>
					<input class="regular-text" type="text" name="exbcd_text" placeholder="<?php _e('Hello. Add your message here.','expressbar'); ?>" value="<?php echo get_option('exbcd_text'); ?>" />
				</td>
			</tr>

			<tr valign="top" class="exbcd <?php echo $exb_hide ?>">
				<th scope="row"><?php _e('Countdown Link Text','expressbar') ?></th>
				<td>
					<input class="regular-text" type="text" name="exbcd_link_text" placeholder="<?php _e('Add your link text here.','expressbar'); ?>" value="<?php echo get_option('exbcd_link_text'); ?>" />
				</td>
			</tr>

			<tr valign="top" class="exbcd <?php echo $exb_hide ?>">
				<th scope="row"><?php _e('Countdown Link URL','expressbar') ?></th>
				<td>
					<input class="regular-text" type="text" name="exbcd_link_url" placeholder="<?php _e('http://yoursite.com','expressbar'); ?>" value="<?php echo get_option('exbcd_link_url'); ?>" />
				</td>
			</tr>

			<tr class="exb-link-target exbcd <?php echo $exb_hide ?>">
				<th scope="row"><?php _e('Open link in a new tab?','expressbar') ?></th>
				<td>
					<?php
						$exbcd_link_target = get_option('exbcd_link_target');
						$exbcd_link_target_select = '';
						if ( $exbcd_link_target == '_blank' ) {
							$exbcd_link_target_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input type="radio" name="exbcd_link_target" value="_self" checked> <?php _e('No','expressbar'); ?> </label>
					<label style="margin-right: 50px;"><input type="radio" name="exbcd_link_target" value="_blank" <?php echo $exbcd_link_target_select; ?>> <?php _e('Yes','expressbar'); ?> </label>
				</td>
			</tr>
		</table>

		<h3><?php _e('Style settings','expressbar') ?></h3>
		<table class="form-table">

			<tr valign="top">
				<?php
					$exb_background_color = get_option('exb_background_color');
					if ( $exb_background_color == '' ) {
						$exb_background_color = '#3B3B4F';
					}
				?>
				<th scope="row"><?php _e('Background Color','expressbar') ?></th>
				<td><input class="regular-text color_picker exb_background_color" type="text" name="exb_background_color" value="<?php echo $exb_background_color; ?>" /></td>
			</tr>
			<?php /* Remove background image
			<tr valign="top">
				<?php
					$exb_background_image = get_option('exb_background_image');
					if ( $exb_background_image == '' ) {
						$exb_background_image = '';
					}
				?>
				<th scope="row"><?php _e('Background Image','expressbar') ?></th>
				<td>
					<input class="regular-text exb_background_image" type="text" name="exb_background_image" value="<?php echo $exb_background_image; ?>" placeholder="<?php _e('http://www.yoursite.com/image.jpg','expressbar'); ?>" />
					<span class="description"><?php _e('Support image formats:: jpg, png, gif') ?></span>
				</td>
			</tr>
			*/ ?>
			<tr valign="top">
				<?php
					$exb_font_color = get_option('exb_font_color');
					if ( $exb_font_color == '' ) {
						$exb_font_color = '#fff';
					}
				?>
				<th scope="row"><?php _e('Text Color','expressbar') ?></th>
				<td><input class="regular-text color_picker exb_font_color" type="text" name="exb_font_color" value="<?php echo $exb_font_color; ?>" /></td>
			</tr>

			<tr valign="top">
				<?php
					$exb_border_color = get_option('exb_border_color');
					if ( $exb_border_color == '' ) {
						$exb_border_color = '';
					}
				?>
				<th scope="row"><?php _e('Bar Border Color','expressbar') ?></th>
				<td><input class="regular-text color_picker exb_border_color" type="text" name="exb_border_color" value="<?php echo $exb_border_color; ?>" /></td>
			</tr>

			<tr valign="top" class="exb-link-color">
				<?php
					$exb_link_color = get_option('exb_link_color');
					if ( $exb_link_color == '' ) {
						$exb_link_color = '#fff';
					}
				?>
				<th scope="row"><?php _e('Link Color','expressbar') ?></th>
				<td><input class="regular-text color_picker exb_link_color" type="text" name="exb_link_color" value="<?php echo $exb_link_color; ?>" /></td>
			</tr>

			<tr class="exb-link-style">
				<th scope="row"><?php _e('Link style','expressbar') ?></th>
				<td>
					<?php
						$exb_link_style = get_option('exb_link_style');
						$exb_link_style_select = '';
						if ( $exb_link_style == '' ) {
							$exb_link_style_select = 'checked';
						}
					?>
					<label style="margin-right: 50px;"><input type="radio" name="exb_link_style" value="exb-button" checked ><?php _e('Button','expressbar') ?></label>
					<label style="margin-right: 50px;"><input type="radio" name="exb_link_style" value="" <?php echo $exb_link_style_select; ?> ><?php _e('Hyperlink','expressbar') ?></label>
				</td>
			</tr>

			<?php
				$exb_button_color_hide = 'hide';
				if (get_option('exb_link_style') != '') {
					$exb_button_color_hide = '';
				}
			?>

			<tr valign="top" class="exb-button-color <?php echo $exb_button_color_hide; ?>">
				<?php
					$exb_button_color = get_option('exb_button_color');
					if ( $exb_button_color == '' ) {
						$exb_button_color = '#333';
					}
				?>
				<th scope="row"><?php _e('Button Color','expressbar') ?></th>
				<td><input class="regular-text color_picker exb_button_color" type="text" name="exb_button_color" value="<?php echo $exb_button_color; ?>" /></td>
			</tr>

			
		</table>
	</div>
    <?php submit_button(); ?>
    <p class="submit">
    	<input type="button" id="exb_reset_cookie" class="button"  value="<?php _e('Reset Cookie') ?>" data-nonce="<?php echo wp_create_nonce( '_exb_reset_cookie' ); ?>">
    	<span class="ajax-load">
    		<span><?php _e('Success') ?></span>
    		<img src="<?php echo EXB_PATH . 'assets/img/ajax-loader.gif' ?>">
    	</span>
    </p>
</form>
</div>
<?php }

// Ajax
if( ! function_exists('exb_reset_cookie') ) {
	function exb_reset_cookie() {
		$ajax_referer = check_ajax_referer( '_exb_reset_cookie', 'nonce', false );
		if( ! wp_verify_nonce( $_POST['nonce'], '_exb_reset_cookie' ) || ! $ajax_referer ) {
			wp_send_json_error( __('Are you cheating huh?','expressbar') );
		}

		$exb_reset_cookie_value = get_option( 'exb_reset_cookie', 2 );
		if ( $exb_reset_cookie_value >= 2 ) {
			$exb_reset_cookie_value = intval($exb_reset_cookie_value) + 1;
			update_option( 'exb_reset_cookie', $exb_reset_cookie_value );
		}

		wp_send_json_success( $exb_reset_cookie_value );
	}
	add_action( 'wp_ajax_exb-reset-cookie', 'exb_reset_cookie' );
}

?>