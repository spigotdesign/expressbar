+function($) {
	function exb_fix_height() { 
		var exb_height = $('#expressbar').outerHeight();
		var adminbar_height = $('.admin-bar #wpadminbar').outerHeight();
		var total_height = exb_height + adminbar_height;
		function dwpb_custom_style() {
			if ( ! $('#expressbar-custom-style').length ) {
				if($(window).width() <=600) {
					
					$('<style id="expressbar-custom-style">body.exb-push-page.expressbar-open, body.exb-push-page.admin-bar.expressbar-open #wpadminbar {top:'+exb_height+'px} body.exb-push-page.expressbar-open.admin-bar .app-header{top:'+total_height+'px}</style>').appendTo('body');
				
				} else {
					$('<style id="expressbar-custom-style">body.exb-push-page.expressbar-open, body.exb-push-page.admin-bar.expressbar-open #wpadminbar {top:'+exb_height+'px}.expressbar-open.exb-push-page .app-header{top:'+exb_height+'px} body.exb-push-page.expressbar-open.admin-bar .app-header{top:'+total_height+'px}</style>').appendTo('body');
				}
				
			} else {
				return false; 
			}
		}

		setTimeout(function(){
			dwpb_custom_style();

			$('.expressbar-open .exb-action').click(function(){
				dwpb_custom_style();
			});
		},1010);
	}

	$(document).ready(function(){
		// Countdown
		$('.exb-counter').countdown({
			timestamp : (new Date()).getTime() + (dwpb.timeleft * 1000),
			callback: function(d, h, m, s){
				if( d === 0 && h === 0 && m === 0 && s === 0 ) {
					$('.exb-message').removeClass('hide');
					$('.exb-countdown').addClass('hide');
				}
			}
		});

		exb_fix_height();

		// Animation
		$('.exb-action').click(function(){
			$('body').toggleClass('expressbar-open');
		});

		// Body class
		setTimeout(function(){
			$('body').addClass('expressbar-open');
		},1000);

		if ( ! $('body').hasClass('exb-allow-close') ) {
			var cookie = $.cookie('exb-hide');
			if (cookie === 'exb-hide') {
				setTimeout(function(){
					$('body').removeClass('expressbar-open');
				},1000);
			}

			//cookie // Not sure we need this any longer
			$('.exb-action').click(function(){
				if (cookie === undefined) {
					$.cookie('exb-hide', 'exb-hide', {path: '/'} );
				} else {
					$.removeCookie('exb-hide', {path: '/'});
				}
			});
		}

		// Close Promobar
		if ( $('body').hasClass('exb-allow-close') ) {
			function remove_promobar() {
				$('#expressbar, .exb-close').remove();
				$('body').removeClass('exb-cover-page dwpb-remain-top expressbar-open exb-push-page');
			}

			$('.exb-close').click(function(){
				remove_promobar();
			});

			$('.exb-close').click(function(){
				if (cookie === undefined) {
					$.cookie('exb-close', 'exb-close-' + dwpb.reset_cookie, {path: '/'} );
				} 
			});

			if ( $.cookie('exb-close') === 'exb-close-' + dwpb.reset_cookie ) {
				remove_promobar();
			}
		}
	});

	$(window).resize(function(){
		exb_fix_height();
	});
}(jQuery);