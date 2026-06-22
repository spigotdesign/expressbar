/*
 * ExpressBar front-end.
 *
 * Hardened to survive performance optimizers (WP Rocket "Delay JavaScript
 * Execution", LiteSpeed, Cloudflare Rocket Loader, etc.). Rather than assume
 * jQuery is already present at parse time, we wait for it to appear, and we
 * guard the optional jquery.countdown / jquery.cookie plugins and the localized
 * `exb` data so a missing or late dependency degrades gracefully instead of
 * throwing "$ is not a function" and leaving the bar inert.
 */
(function() {

	function exb_init($) {
		var exb_headers = $();
		var exb_data = window.exb || { timeleft: 0, reset_cookie: '2' };
		var exb_has_cookie = typeof $.cookie === 'function';

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
			// Countdown — skip if the countdown plugin did not load
			if (typeof $.fn.countdown === 'function') {
				$('.exb-counter').countdown({
					timestamp : (new Date()).getTime() + (exb_data.timeleft * 1000),
					callback: function(d, h, m, s){
						if( d === 0 && h === 0 && m === 0 && s === 0 ) {
							$('.exb-message').removeClass('hide');
							$('.exb-countdown').addClass('hide');
						}
					}
				});
			}

			exb_fix_height();

			var cookie = exb_has_cookie ? $.cookie('exb-hide') : undefined;

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

			// Persist open/closed state — only when the cookie plugin is available
			if ( exb_has_cookie && ! $('body').hasClass('exb-allow-close') ) {
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

				// Remember dismissal across page loads — only with the cookie plugin
				if ( exb_has_cookie ) {
					$('.exb-close').click(function(){
						if (cookie === undefined) {
							$.cookie('exb-close', 'exb-close-' + exb_data.reset_cookie, {path: '/'} );
						}
					});

					if ( $.cookie('exb-close') === 'exb-close-' + exb_data.reset_cookie ) {
						remove_expressbar();
					}
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
	}

	// Run as soon as jQuery is available. If an optimizer has deferred jQuery,
	// poll briefly until it appears (it loads on first interaction) instead of
	// crashing because $ is undefined at parse time. Gives up after ~20s.
	function exb_boot() {
		if (window.jQuery) {
			exb_init(window.jQuery);
			return;
		}
		var tries = 0;
		var timer = setInterval(function() {
			if (window.jQuery) {
				clearInterval(timer);
				exb_init(window.jQuery);
			} else if (++tries >= 200) {
				clearInterval(timer);
			}
		}, 100);
	}

	exb_boot();

})();
