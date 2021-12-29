+function($) {
	function exb_fix_height() { 
		var exb_height = $('#dwpb').outerHeight();
		var adminbar_height = $('.admin-bar #wpadminbar').outerHeight();
		function dwpb_custom_style() {
			if ( ! $('#dwpb-custom-style').length ) {
				$('<style id="dwpb-custom-style">body.exb-push-page.exb-open, body.exb-push-page.admin-bar.exb-open #wpadminbar, .dwpb-twenty-fourteen.exb-open.exb-push-page .site-header{top:'+exb_height+'px}</style>').appendTo('body');
				
			} else {
				return false; 
			}
		}

		setTimeout(function(){
			dwpb_custom_style();

			$('.exb-open .exb-action').click(function(){
				dwpb_custom_style();
			});
		},1010);
	}

	$(document).ready(function(){
		// Countdown
		$('.dwpb-counter').countdown({
			timestamp : (new Date()).getTime() + (dwpb.timeleft * 1000),
			callback: function(d, h, m, s){
				if( d === 0 && h === 0 && m === 0 && s === 0 ) {
					$('.dwpb-message').removeClass('hide');
					$('.dwpb-countdown').addClass('hide');
				}
			}
		});

		exb_fix_height();

		// Animation
		$('.exb-action').click(function(){
			$('body').toggleClass('exb-open');
		});

		// Body class
		setTimeout(function(){
			$('body').addClass('exb-open');
		},1000);

		if ( ! $('body').hasClass('exb-allow-close') ) {
			var cookie = $.cookie('dwpb-hide');
			if (cookie === 'dwpb-hide') {
				setTimeout(function(){
					$('body').removeClass('exb-open');
				},1000);
			}

			//cookie
			$('.exb-action').click(function(){
				if (cookie === undefined) {
					$.cookie('dwpb-hide', 'dwpb-hide', {path: '/'} );
				} else {
					$.removeCookie('dwpb-hide', {path: '/'});
				}
			});
		}

		// Close Promobar
		if ( $('body').hasClass('exb-allow-close') ) {
			function remove_promobar() {
				$('#dwpb, .dwpb-close').remove();
				$('body').removeClass('exb-cover-page dwpb-ramain-top exb-open exb-push-page dwpb-twenty-fourteen');
			}

			$('.dwpb-close').click(function(){
				remove_promobar();
			});

			$('.dwpb-close').click(function(){
				if (cookie === undefined) {
					$.cookie('dwpb-close', 'dwpb-close-' + dwpb.reset_cookie, {path: '/'} );
				} 
			});

			if ( $.cookie('dwpb-close') === 'dwpb-close-' + dwpb.reset_cookie ) {
				remove_promobar();
			}
		}
	});

	$(window).resize(function(){
		exb_fix_height();
	});
}(jQuery);