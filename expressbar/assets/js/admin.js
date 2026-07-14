+function($) {
	function exb_fix_height() { 
		var exb_height = $('#expressbar').outerHeight();
		$('.settings_page_expressbar, .settings_page_expressbar #wpadminbar').css({
			'margin-top' : exb_height + 'px', 
		});
	}

	$(document).ready(function(){
		exb_fix_height(); 

		// Expressbar bar
		// --------------------------------

		// Text
		$('[name=exb_bar_text]').keyup(function(){
			var val = $(this).val(); 
			if (val !== '') {
				$('.exb-message .exb-content').html(val);
			}
		}).focusout(function(){ 
			exb_fix_height();
		});

		// link text
		$('[name=exb_link_text]').keyup(function(){
			var val = $(this).val();
			var link_style = $('[name=exb_link_style]:checked').val();
			var exb_link_url = $('[name=exb_link_url]').val();
			var exb_message_link = $('.exb-message a').length;

			if ( ! exb_message_link ) {
				$('.exb-message span').after(' <a href="'+exb_link_url+'">'+ val +'</a>');
			}

			if (val === '') {
				$('.exb-message a').removeClass();
			}

			if ( val !== '' && link_style === 'exb-button' ) {
				$('.exb-message a').addClass('exb-button');
			}

			$('.exb-message a').html(val); 
		}).focusout(function(){
			exb_fix_height();
		});


		// Expressbar Countdown
		// --------------------------------
		$('.exb-counter').countdown({
			timestamp : (new Date()).getTime() + (exb.timeleft * 1000),
		});

		// exbcd use
		$('[name=expcd_use]').change(function(){
			var expcd_use = $(this).val();
			if (expcd_use === 'yes' ) {
				$('.exbcd').removeClass('hide');
				$('.exb-message').addClass('hide');
				$('.exb-countdown').removeClass('hide');
			} else {
				$('.exbcd').addClass('hide');
				$('.exb-message').removeClass('hide');
				$('.exb-countdown').addClass('hide');
			}
		});

		// Text
		$('[name=exbcd_text]').keyup(function(){
			var val = $(this).val(); 
			if (val !== '') {
				$('.exb-countdown .exbcd-content').html(val);
			} 
		}).focusout(function(){
			exb_fix_height();
		});

		// link text
		$('[name=exbcd_link_text]').keyup(function(){
			var val = $(this).val();
			var link_style = $('[name=exb_link_style]:checked').val();
			var exbcd_link_url = $('[name=exbcd_link_url]').val();
			var exbcd_message_link = $('.exb-countdown a').length;

			if ( ! exbcd_message_link ) {
				$('.exbcd-content').after(' <a href="'+exbcd_link_url+'">'+ val +'</a>');
			}

			if (val === '') {
				$('.exb-countdown a').removeClass();
			}

			if ( val !== '' && link_style === 'exb-button' ) {
				$('.exb-countdown a').addClass('exb-button');
			}

			$('.exb-countdown a').html(val);
		}).focusout(function(){
			exb_fix_height();
		});

		// General Setting
		// --------------------------------
		
		$('.exb_time_picker').datetimepicker();

		// Expressbar font size 
		$('[name=exb_font_size]').keyup(function(){
			var val = $(this).val();
			$('#expressbar').css('font-size' , val + 'px');

			if (val > 20) {
				$('#expressbar').css('line-height' , '1.2');
			} else {
				$('#expressbar').css('line-height' , '30px');
			}
		}).focusout(function(){
			exb_fix_height();
		});

		// Expressbar font family — live preview.
		// Blank reverts to the inherited (theme) font; otherwise apply the stack.
		$('[name=exb_font_family]').on('keyup change', function(){
			var val = $.trim( $(this).val() );
			$('#expressbar').css('font-family', val === '' ? '' : val);
		}).focusout(function(){
			exb_fix_height();
		});

		// Allow to Close ExpressBar
		$('[name=exb_close]').change(function(){
			var exb_close = $(this).val();
			if (exb_close === 'yes' ) {
				$('.exb-action').addClass('exb-close');
			} else {
				$('.exb-action').removeClass('exb-close');
			}
		});

		// Expressbar remain top
		$('[name=exb_remain_top]').change(function(){
			var exb_remain_top = $(this).val();
			if (exb_remain_top === 'remain-top' ) {
				$('.no-push').attr('checked','checked');
			}
		});

		// Expressbar push page
		$('[name=exp_push_page]').change(function(){
			var exp_push_page = $(this).val();
			if (exp_push_page === 'push' ) {
				$('.fixtop').attr('checked','checked');
			}
		});

		// Color pickder
		// --------------------------------

		// Expressbar background color
		$('.color_picker.exb_background_color').wpColorPicker({
			defaultColor: '#3B3B4F',
			change: function(event, ui){
				$("#expressbar").css( 'background-color', ui.color.toString());
			},
		});
		
		/* exb background image removed
		$('.exb_background_image').change(function(){
			var exb_background_image = $(this).val();
			if ( exb_background_image == '' ) {
				$("#expressbar").css( 'background-image', 'none' );
			} else {
				console.log(exb_background_image);
				$("#expressbar").css( 'background-image', 'url(' + exb_background_image + ')' );
			}
		}); */

		// font color
		$('.color_picker.exb_font_color').wpColorPicker({
			defaultColor: '#fff',
			change: function(event, ui){
				$("#expressbar, .exb-action").css( 'color', ui.color.toString());	
			},
		});

		// border color
		$('.color_picker.exb_border_color').wpColorPicker({
			defaultColor: '',
			change: function(event, ui){
				$("#expressbar").css({ 'border-color': ui.color.toString(), 'border-width': '0 0 3px' });
				setTimeout(function(){
					exb_fix_height();
				},500);
			},
			clear: function() {
				$("#expressbar").css( 'border-width', '0');
				setTimeout(function(){
					exb_fix_height();
				},500);
			}
		});

		// Expressbar link color
		$('.color_picker.exb_link_color').wpColorPicker({
			defaultColor: '#fff',
			change: function(event, ui){
				$("#expressbar a").css( 'color', ui.color.toString());	
			},
		});

		// Expressbar button color
		var exb_button_color = '';
		$('.color_picker.exb_button_color').wpColorPicker({
			defaultColor: '#333',
			change: function(event, ui){
				$("#expressbar a").css( 'background', ui.color.toString());
				exb_button_color = ui.color.toString();
			},
		});

		// link style
		$('[name=exb_link_style]').change(function(){
			var exb_link_style = $(this).val();
			if (exb_link_style !== '' ) {
				$('#expressbar a').addClass(exb_link_style);
				$('tr.exb-button-color').show();
				$("#expressbar a").css( 'background', exb_button_color);
			} else {
				$('#expressbar a').removeClass().removeAttr('style');
				$('tr.exb-button-color').hide();
			}
		});
	});
	
	$(window).resize(function(){
		exb_fix_height();
	});

	$('#exb_reset_cookie').click(function(){
		$('.ajax-load img').fadeIn();
		var nonce = $(this).data('nonce');
		$.ajax({
      url: exb.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'exb-reset-cookie',
        nonce: nonce
      },
      timeout: 10000
    })
    .done(function(resp) {
      if (resp.success){
      	$('.ajax-load img').fadeOut();
      	setTimeout(function(){
      		$('.ajax-load span').fadeIn();
      		setTimeout(function(){
      			$('.ajax-load span').fadeOut();
      		},2000);
      	},500);
      } else {
      	$('.ajax-load img').fadeOut();
      	setTimeout(function(){
      		$('.ajax-load span').fadeIn().html(resp.data);
      		setTimeout(function(){
      			$('.ajax-load span').fadeOut();
      		},2000);
      	},500);
      }
    })
    .fail(function(jqXHR,textStatus){
      $('.ajax-load img').fadeOut();
    	setTimeout(function(){
    		$('.ajax-load span').fadeIn().html('Error');
    		setTimeout(function(){ 
      			$('.ajax-load span').fadeOut();
      		},2000);
    	},500);
    });
	});
}(jQuery);