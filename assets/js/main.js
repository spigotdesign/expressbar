+function($) {
	var exb_headers = $();

	function exb_find_fixed_headers() {
		var adminbar_height = $('.admin-bar #wpadminbar').outerHeight() || 0;
		var threshold = adminbar_height + 5;
		var skip = '#expressbar, #wpadminbar, .exb-action, .exb-close';
		var found = $();

		// Shallow DOM scan: fixed/sticky elements anchored near the top
		$('body > *, body > * > *').not(skip).each(function() {
			var pos = window.getComputedStyle(this).position;
			if (pos === 'fixed' || pos === 'sticky') {
				var top = parseFloat(window.getComputedStyle(this).top);
				if (!isNaN(top) && top <= threshold) {
					found = found.add(this);
				}
			}
		});

		// Any <header> element that is fixed or sticky, regardless of depth
		$('header').not(skip).each(function() {
			var pos = window.getComputedStyle(this).position;
			if (pos === 'fixed' || pos === 'sticky') {
				found = found.add(this);
			}
		});

		found.addClass('exb-tracked');
		return found;
	}

	function exb_push_headers(headers) {
		var exb_height = $('#expressbar').outerHeight();
		headers.each(function() {
			var original = parseFloat(window.getComputedStyle(this).top) || 0;
			$(this).data('exb-original-top', original).css('top', (original + exb_height) + 'px');
		});
	}

	function exb_restore_headers(headers, removeTracking) {
		headers.each(function() {
			var original = $(this).data('exb-original-top');
			if (original !== undefined) {
				$(this).css('top', original + 'px');
				if (removeTracking) {
					var el = this;
					setTimeout(function() {
						$(el).removeClass('exb-tracked').removeData('exb-original-top');
					}, 500);
				}
			}
		});
	}

	function exb_fix_height() {
		var exb_height = $('#expressbar').outerHeight();
		document.documentElement.style.setProperty('--exb-height', exb_height + 'px');
		var adminbar_height = $('.admin-bar #wpadminbar').outerHeight();
		var total_height = exb_height + adminbar_height;
		function exb_custom_style() {
			$('#expressbar-custom-style').remove();
			var css = '';
			if($(window).width() <=600) {
				css += 'body.exb-push-page.expressbar-open, body.exb-push-page.admin-bar.expressbar-open #wpadminbar {top:'+exb_height+'px}';
				css += 'body.exb-push-page.expressbar-open.admin-bar .app-header{top:'+total_height+'px}';
			} else {
				css += 'body.exb-push-page.expressbar-open, body.exb-push-page.admin-bar.expressbar-open #wpadminbar {top:'+exb_height+'px}';
				css += '.expressbar-open.exb-push-page .app-header{top:'+exb_height+'px}';
				css += 'body.exb-push-page.expressbar-open.admin-bar .app-header{top:'+total_height+'px}';
			}
			$('<style id="expressbar-custom-style">'+css+'</style>').appendTo('body');
		}

		setTimeout(function(){
			exb_custom_style();
			$('.expressbar-open .exb-action').click(function(){
				exb_custom_style();
			});
		},1010);
	}

	$(document).ready(function(){
		// Countdown
		$('.exb-counter').countdown({
			timestamp : (new Date()).getTime() + (exb.timeleft * 1000),
			callback: function(d, h, m, s){
				if( d === 0 && h === 0 && m === 0 && s === 0 ) {
					$('.exb-message').removeClass('hide');
					$('.exb-countdown').addClass('hide');
				}
			}
		});

		exb_fix_height();

		var cookie = $.cookie('exb-hide');

		// Initial open after page settles
		setTimeout(function(){
			exb_headers = exb_find_fixed_headers();
			if (cookie !== 'exb-hide') {
				$('body').addClass('expressbar-open');
				// rAF ensures .exb-tracked renders before top changes so transition fires
				requestAnimationFrame(function() {
					exb_push_headers(exb_headers);
				});
			}
		},1000);

		// Toggle
		$('.exb-action').click(function(){
			$('body').toggleClass('expressbar-open');
			if ($('body').hasClass('expressbar-open')) {
				exb_push_headers(exb_headers);
			} else {
				exb_restore_headers(exb_headers);
			}
		});

		if ( ! $('body').hasClass('exb-allow-close') ) {
			$('.exb-action').click(function(){
				if (cookie === undefined) {
					$.cookie('exb-hide', 'exb-hide', {path: '/'} );
				} else {
					$.removeCookie('exb-hide', {path: '/'});
				}
			});
		}

		// Close Expressbar
		if ( $('body').hasClass('exb-allow-close') ) {
			function remove_expressbar() {
				exb_restore_headers(exb_headers, true);
				$('#expressbar, .exb-close').remove();
				$('body').removeClass('exb-cover-page exb-remain-top expressbar-open exb-push-page');
			}

			$('.exb-close').click(function(){
				remove_expressbar();
			});

			$('.exb-close').click(function(){
				if (cookie === undefined) {
					$.cookie('exb-close', 'exb-close-' + exb.reset_cookie, {path: '/'} );
				}
			});

			if ( $.cookie('exb-close') === 'exb-close-' + exb.reset_cookie ) {
				remove_expressbar();
			}
		}
	});

	$(window).resize(function(){
		exb_fix_height();
		if ($('body').hasClass('expressbar-open')) {
			exb_restore_headers(exb_headers);
			exb_headers = exb_find_fixed_headers();
			requestAnimationFrame(function() {
				exb_push_headers(exb_headers);
			});
		}
	});
}(jQuery);
