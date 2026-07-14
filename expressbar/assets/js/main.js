/*
 * ExpressBar front-end.
 *
 * Hardened to survive performance optimizers (WP Rocket "Delay JavaScript
 * Execution", LiteSpeed, Cloudflare Rocket Loader, etc.). Rather than assume
 * jQuery is already present at parse time, we wait for it to appear, and we
 * guard the optional jquery.countdown / jquery.cookie plugins and the localized
 * `exb` data so a missing or late dependency degrades gracefully instead of
 * throwing "$ is not a function" and leaving the bar inert.
 *
 * Fixed-header offsets are pure CSS: each tracked header's natural top is
 * recorded once as --exb-original-top, and the .exb-tracked rule in main.css
 * derives the pushed position from it plus --exb-height. JS never re-measures
 * a header after tracking it — re-measuring an already-pushed or mid-transition
 * header is what used to corrupt the stored offset and stack pushes.
 */
(function() {

	function exb_init($) {
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

			// Record the natural top ONCE, before .exb-tracked is applied —
			// at this moment the offset rule cannot be affecting the element,
			// so the measurement is guaranteed clean.
			found.not('.exb-tracked').each(function() {
				var original = parseFloat(window.getComputedStyle(this).top) || 0;
				this.style.setProperty('--exb-original-top', original + 'px');
			});

			found.addClass('exb-tracked');
			return found;
		}

		// Briefly enable the top transition on tracked headers while the bar
		// opens or closes (see the .exb-animating rule in main.css). Outside
		// this window headers keep their theme's own transitions — e.g. the
		// Bricks sticky slide-up animates `transform`, which a permanent
		// `transition: top !important` would clobber into an instant snap.
		function exb_animate_tracked() {
			// Re-measure the bar height at the moment of open/close — the
			// document.ready measurement can be stale (webfont swap, late
			// layout) and a wrong --exb-height would offset everything until
			// the next resize.
			var exb_height = $('#expressbar').outerHeight();
			if (exb_height) {
				document.documentElement.style.setProperty('--exb-height', exb_height + 'px');
			}
			var headers = $('.exb-tracked');
			headers.addClass('exb-animating');
			setTimeout(function() {
				headers.removeClass('exb-animating');
			}, 600); // --exb-transition-speed (0.5s) + settle buffer
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
				exb_find_fixed_headers();
				if (cookie !== 'exb-hide') {
					// rAF ensures .exb-tracked renders before the body class
					// flips, so the push animates
					requestAnimationFrame(function() {
						exb_animate_tracked();
						$('body').addClass('expressbar-open');
					});
				}
			},1000);

			// Toggle — header offsets follow the body class via CSS
			$('.exb-action').click(function(){
				exb_animate_tracked();
				$('body').toggleClass('expressbar-open');
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
					exb_animate_tracked();
					$('#expressbar, .exb-close').remove();
					$('body').removeClass('exb-cover-page exb-remain-top expressbar-open exb-push-page');
					// Keep .exb-tracked briefly so the header animates back
					// to its natural top before the transition rule goes away
					var headers = $('.exb-tracked');
					setTimeout(function() {
						headers.removeClass('exb-tracked');
					}, 500);
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
			// Pick up elements that became fixed/sticky at this viewport size.
			// Already-tracked headers keep their recorded --exb-original-top;
			// the CSS rule re-derives their offset from --exb-height alone.
			if ($('body').hasClass('expressbar-open')) {
				exb_find_fixed_headers();
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
