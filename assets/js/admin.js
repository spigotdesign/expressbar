+function($) {
	function exb_fix_height() { 
		var exb_height = $('#expressbar').outerHeight();
		$('.settings_page_expressbar, .settings_page_expressbar #wpadminbar').css({
			'margin-top' : exb_height + 'px', 
		});
	}

	$(document).ready(function(){
		exb_fix_height(); 

		// dwpb bar
		// --------------------------------

		// Text
		$('[name=exb_bar_text]').keyup(function(){
			var val = $(this).val(); 
			if (val !== '') {
				$('.dwpb-message .dwpb-content').html(val);
			}
		}).focusout(function(){ 
			exb_fix_height();
		});

		// link text
		$('[name=exb_link_text]').keyup(function(){
			var val = $(this).val();
			var link_style = $('[name=dwpb_link_style]:checked').val();
			var dwpb_link_url = $('[name=dwpb_link_url]').val();
			var dwpb_message_link = $('.dwpb-message a').length;

			if ( ! dwpb_message_link ) {
				$('.dwpb-message span').after(' <a href="'+dwpb_link_url+'">'+ dwpb_message_link +'</a>');
			}

			if (val === '') {
				$('.dwpb-message a').removeClass();
			}

			if ( val !== '' && link_style === 'dwpb-button' ) {
				$('.dwpb-message a').addClass('dwpb-button');
			}

			$('.dwpb-message a').html(val); 
		}).focusout(function(){
			exb_fix_height();
		});


		// dwpb Countdown
		// --------------------------------
		$('.dwpb-counter').countdown({
			timestamp : (new Date()).getTime() + (dwpb.timeleft * 1000),
		});

		// dwpbcd use
		$('[name=dwpbcd_use]').change(function(){
			var dwpbcd_use = $(this).val();
			if (dwpbcd_use === 'yes' ) {
				$('.dwpbcd').removeClass('hide');
				$('.dwpb-message').addClass('hide');
				$('.dwpb-countdown').removeClass('hide');
			} else {
				$('.dwpbcd').addClass('hide');
				$('.dwpb-message').removeClass('hide');
				$('.dwpb-countdown').addClass('hide');
			}
		});

		// Text
		$('[name=dwpbcd_text]').keyup(function(){
			var val = $(this).val(); 
			if (val !== '') {
				$('.dwpb-countdown .dwpbcd-content').html(val);
			} 
		}).focusout(function(){
			exb_fix_height();
		});

		// link text
		$('[name=dwpbcd_link_text]').keyup(function(){
			var val = $(this).val();
			var link_style = $('[name=dwpb_link_style]:checked').val();
			var dwpbcd_link_url = $('[name=dwpbcd_link_url]').val();
			var dwpbcd_message_link = $('.dwpb-countdown a').length;

			if ( ! dwpbcd_message_link ) {
				$('.dwpbcd-content').after(' <a href="'+dwpbcd_link_url+'">'+ dwpbcd_message_link +'</a>');
			}

			if (val === '') {
				$('.dwpb-countdown a').removeClass();
			}

			if ( val !== '' && link_style === 'dwpb-button' ) {
				$('.dwpb-countdown a').addClass('dwpb-button');
			}

			$('.dwpb-countdown a').html(val);
		}).focusout(function(){
			exb_fix_height();
		});

		// General Setting
		// --------------------------------
		
		$('.dwpb_time_picker').datetimepicker();

		// dwpb font size 
		$('[name=dwpb_font_size]').keyup(function(){
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

		// dwpb font family
		$('[name=dwpb_font_family]').change(function(){
			var val = $(this).val();
			if (val !== '0') {
				var val_array = val.split(':dw:');
				var style = '<style>';
					style += '@font-face { font-family: "'+ val_array[0] +'"; src: url('+val_array[1]+');}';
					style += '#expressbar {font-family:' + val_array[0] +'}'; 
					style += '</style>';
			} else {
				var style = '<style>';
					style += '#expressbar {font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;}'; 
					style += '</style>';
			}
			$('#expressbar').before( style );
			console.log(val);
		}).focusout(function(){
			exb_fix_height();
		});

		// Allow to Close ExpressBar
		$('[name=dwpb_close]').change(function(){
			var dwpb_close = $(this).val();
			if (dwpb_close === 'yes' ) {
				$('.exb-action').addClass('dwpb-close');
			} else {
				$('.exb-action').removeClass('dwpb-close');
			}
		});

		// dwpb ramain top
		$('[name=dwpb_ramain_top]').change(function(){
			var dwpb_ramain_top = $(this).val();
			if (dwpb_ramain_top === 'ramain-top' ) {
				$('.no-push').attr('checked','checked');
			}
		});

		// dwpb push page
		$('[name=exp_push_page]').change(function(){
			var exp_push_page = $(this).val();
			if (exp_push_page === 'push' ) {
				$('.fixtop').attr('checked','checked');
			}
		});

		// Color pickder
		// --------------------------------

		// dwpb background color
		$('.color_picker.dwpb_background_color').wpColorPicker({
			defaultColor: '#3B3B4F',
			change: function(event, ui){
				$("#expressbar").css( 'background-color', ui.color.toString());
			},
		});
		
		// dwpb background image
		$('.dwpb_background_image').change(function(){
			var dwpb_background_image = $(this).val();
			if ( dwpb_background_image == '' ) {
				$("#expressbar").css( 'background-image', 'none' );
			} else {
				console.log(dwpb_background_image);
				$("#expressbar").css( 'background-image', 'url(' + dwpb_background_image + ')' );
			}
		});

		// dwpb font color
		$('.color_picker.dwpb_font_color').wpColorPicker({
			defaultColor: '#fff',
			change: function(event, ui){
				$("#expressbar, .exb-action").css( 'color', ui.color.toString());	
			},
		});

		// dwpb border color
		$('.color_picker.dwpb_border_color').wpColorPicker({
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

		// dwpb link color
		$('.color_picker.dwpb_link_color').wpColorPicker({
			defaultColor: '#fff',
			change: function(event, ui){
				$("#expressbar a").css( 'color', ui.color.toString());	
			},
		});

		// dwpb button color
		var dwpb_button_color = '';
		$('.color_picker.dwpb_button_color').wpColorPicker({
			defaultColor: '#333',
			change: function(event, ui){
				$("#expressbar a").css( 'background', ui.color.toString());
				dwpb_button_color = ui.color.toString();
			},
		});

		// link style
		$('[name=dwpb_link_style]').change(function(){
			var dwpb_link_style = $(this).val();
			if (dwpb_link_style !== '' ) {
				$('#expressbar a').addClass(dwpb_link_style);
				$('tr.dwpb-button-color').show();
				$("#expressbar a").css( 'background', dwpb_button_color);
			} else {
				$('#expressbar a').removeClass().removeAttr('style');
				$('tr.dwpb-button-color').hide();
			}
		});
	});
	
	$(window).resize(function(){
		exb_fix_height();
	});

	$('#expressbar_reset_cookie').click(function(){
		$('.ajax-load img').fadeIn();
		var nonce = $(this).data('nonce');
		$.ajax({
      url: dwpb.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'dwpb-reset-cookie',
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