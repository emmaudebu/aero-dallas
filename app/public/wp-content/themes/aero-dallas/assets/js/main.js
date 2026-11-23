/**
 * AERO DALLAS INDUSTRIAL — header, mobile menu, scroll reveal, hero flight path,
 * article copy link and policies TOC.
 * Vanilla JS, loaded with defer.
 */
( function () {
	'use strict';

	var root = document.documentElement;
	var hasIO = 'IntersectionObserver' in window;

	/* Light / dark mode toggle. The initial mode is set inline in <head>. --- */
	document.querySelectorAll( '[data-mode-toggle]' ).forEach( function ( button ) {
		var sync = function () {
			button.setAttribute( 'aria-pressed', root.getAttribute( 'data-theme' ) === 'dark' ? 'true' : 'false' );
		};
		sync();
		button.addEventListener( 'click', function () {
			var next = root.getAttribute( 'data-theme' ) === 'dark' ? 'light' : 'dark';
			root.setAttribute( 'data-theme', next );
			try {
				window.localStorage.setItem( 'aerodallas-mode', next );
			} catch ( e ) {}
			document.querySelectorAll( '[data-mode-toggle]' ).forEach( function ( b ) {
				b.setAttribute( 'aria-pressed', next === 'dark' ? 'true' : 'false' );
			} );
		} );
	} );

	/* Sticky header: condense once the page has scrolled. ------------------ */
	var header = document.getElementById( 'site-header' );
	var sentinel = document.querySelector( '.header-sentinel' );
	if ( header && sentinel && hasIO ) {
		new IntersectionObserver( function ( entries ) {
			header.classList.toggle( 'is-condensed', ! entries[ 0 ].isIntersecting );
		} ).observe( sentinel );
	}

	/* Mobile menu: dialog with focus trap. -------------------------------- */
	var menu = document.getElementById( 'mobile-menu' );
	var toggle = document.querySelector( '.menu-toggle' );
	var lastFocus = null;

	function focusables() {
		return Array.prototype.filter.call(
			menu.querySelectorAll( 'a[href], button:not([disabled])' ),
			function ( el ) {
				return el.offsetParent !== null;
			}
		);
	}

	function openMenu() {
		lastFocus = document.activeElement;
		menu.hidden = false;
		document.body.classList.add( 'menu-open' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		window.requestAnimationFrame( function () {
			menu.classList.add( 'is-open' );
			var items = focusables();
			if ( items.length ) {
				items[ items.length > 1 ? 1 : 0 ].focus();
			}
		} );
		document.addEventListener( 'keydown', onMenuKey );
	}

	function closeMenu( restoreFocus ) {
		menu.classList.remove( 'is-open' );
		menu.hidden = true;
		document.body.classList.remove( 'menu-open' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		document.removeEventListener( 'keydown', onMenuKey );
		if ( restoreFocus !== false && lastFocus ) {
			lastFocus.focus();
		}
	}

	function onMenuKey( event ) {
		if ( event.key === 'Escape' ) {
			closeMenu();
			return;
		}
		if ( event.key !== 'Tab' ) {
			return;
		}
		var items = focusables();
		var first = items[ 0 ];
		var last = items[ items.length - 1 ];
		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	}

	if ( menu && toggle ) {
		toggle.addEventListener( 'click', openMenu );
		menu.querySelector( '.mobile-menu__close' ).addEventListener( 'click', function () {
			closeMenu();
		} );
		menu.addEventListener( 'click', function ( event ) {
			var link = event.target.closest( 'a[href]' );
			if ( link ) {
				closeMenu( false );
			}
		} );
		window.matchMedia( '(min-width: 1100px)' ).addEventListener( 'change', function ( mq ) {
			if ( mq.matches && ! menu.hidden ) {
				closeMenu( false );
			}
		} );
	}

	/* Scroll reveal. ------------------------------------------------------ */
	var reveals = document.querySelectorAll( '[data-reveal]' );
	if ( ! hasIO ) {
		reveals.forEach( function ( el ) {
			el.classList.add( 'is-visible' );
		} );
	} else {
		var revealObserver = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-visible' );
						revealObserver.unobserve( entry.target );
					}
				} );
			},
			{ rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
		);
		reveals.forEach( function ( el ) {
			revealObserver.observe( el );
		} );
	}
	root.classList.add( 'reveal-ready' );

	/* Home hero showcase: rotate the equipment photos. --------------------- */
	document.querySelectorAll( '[data-showcase]' ).forEach( function ( showcase ) {
		var slides = showcase.querySelectorAll( '[data-showcase-slide]' );
		var thumbs = showcase.querySelectorAll( '[data-showcase-thumb]' );
		var controls = showcase.querySelector( '[data-showcase-controls]' );
		var pause = showcase.querySelector( '[data-showcase-pause]' );
		var calm = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		var time = Number( showcase.getAttribute( 'data-interval' ) ) || 5000;
		var autoplay = showcase.getAttribute( 'data-autoplay' ) !== '0';
		var timer = 0;
		var now = 0;
		var paused = calm || ! autoplay;
		var held = false;
		var seen = true;
		if ( slides.length < 2 || ! controls ) {
			return;
		}

		var show = function ( index ) {
			var before = now;
			now = ( index + slides.length ) % slides.length;
			slides.forEach( function ( slide, i ) {
				slide.classList.toggle( 'is-prev', i === before && i !== now );
				slide.classList.toggle( 'is-active', i === now );
			} );
			thumbs.forEach( function ( thumb, i ) {
				thumb.setAttribute( 'aria-current', i === now ? 'true' : 'false' );
			} );
		};

		var run = function () {
			window.clearTimeout( timer );
			showcase.removeAttribute( 'data-showcase-running' );
			if ( paused || held || ! seen || document.hidden ) {
				return;
			}
			void showcase.offsetWidth; // Restart the progress bar.
			showcase.setAttribute( 'data-showcase-running', '' );
			timer = window.setTimeout( function () {
				show( now + 1 );
				run();
			}, time );
		};

		showcase.style.setProperty( '--showcase-time', time + 'ms' );
		controls.hidden = false;

		thumbs.forEach( function ( thumb, i ) {
			thumb.addEventListener( 'click', function () {
				show( i );
				run();
			} );
		} );

		// Hold the current photo while the pointer is over it or a link in it has focus.
		var stage = showcase.querySelector( '.showcase__stage' );
		var hold = function () {
			held = true;
			run();
		};
		var release = function ( event ) {
			if ( event.type === 'focusout' && stage.contains( event.relatedTarget ) ) {
				return;
			}
			held = false;
			run();
		};
		stage.addEventListener( 'mouseenter', hold );
		stage.addEventListener( 'mouseleave', release );
		stage.addEventListener( 'focusin', hold );
		stage.addEventListener( 'focusout', release );

		if ( pause && ! autoplay ) {
			pause.hidden = true;
		} else if ( pause ) {
			pause.setAttribute( 'aria-pressed', paused ? 'true' : 'false' );
			pause.addEventListener( 'click', function () {
				paused = ! paused;
				pause.setAttribute( 'aria-pressed', paused ? 'true' : 'false' );
				run();
			} );
		}

		if ( hasIO ) {
			new IntersectionObserver( function ( entries ) {
				seen = entries[ 0 ].isIntersecting;
				run();
			} ).observe( showcase );
		}
		document.addEventListener( 'visibilitychange', run );
		run();
	} );

	/* Equipment catalogue: All / Aviation / Industrial filters. ------------ */
	document.querySelectorAll( '[data-equipment-filters]' ).forEach( function ( group ) {
		var section = group.closest( '.equipment' );
		var items = section.querySelectorAll( '.equipment__item' );
		var buttons = group.querySelectorAll( '[data-filter]' );
		var status = group.querySelector( '[data-equipment-status]' );
		group.hidden = false;
		buttons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var filter = button.getAttribute( 'data-filter' );
				var shown = 0;
				buttons.forEach( function ( b ) {
					b.setAttribute( 'aria-pressed', b === button ? 'true' : 'false' );
				} );
				items.forEach( function ( item ) {
					var match = filter === 'all' || item.getAttribute( 'data-category' ) === filter;
					item.hidden = ! match;
					if ( match ) {
						shown++;
						item.classList.add( 'is-visible' );
					}
				} );
				status.textContent = shown + ' ' + ( shown === 1 ? 'item' : 'items' ) + ' shown';
			} );
		} );
	} );

	/* Hero flight path: run the aircraft and truck only while on screen. --- */
	if ( ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		document.querySelectorAll( '.flight-path' ).forEach( function ( svg ) {
			var motions = svg.querySelectorAll( 'animateMotion' );
			if ( ! motions.length || typeof motions[ 0 ].beginElement !== 'function' ) {
				return;
			}
			svg.classList.add( 'is-flying' );
			motions.forEach( function ( motion ) {
				motion.beginElement();
			} );
			if ( hasIO ) {
				new IntersectionObserver( function ( entries ) {
					if ( entries[ 0 ].isIntersecting ) {
						svg.unpauseAnimations();
					} else {
						svg.pauseAnimations();
					}
				} ).observe( svg );
			}
		} );
	}

	/* Articles: copy link button (shown only where the clipboard works). --- */
	document.querySelectorAll( '[data-copy-link]' ).forEach( function ( button ) {
		if ( ! navigator.clipboard || ! window.isSecureContext ) {
			return;
		}
		var status = button.closest( '.article-share' ).querySelector( '.article-share__status' );
		button.hidden = false;
		button.addEventListener( 'click', function () {
			navigator.clipboard.writeText( button.getAttribute( 'data-copy-link' ) ).then( function () {
				button.classList.add( 'is-copied' );
				if ( status ) {
					status.textContent = button.getAttribute( 'data-copied' );
				}
				window.setTimeout( function () {
					button.classList.remove( 'is-copied' );
					if ( status ) {
						status.textContent = '';
					}
				}, 2500 );
			} );
		} );
	} );

	/* Policies: highlight the section currently in view. ------------------ */
	var tocLinks = document.querySelectorAll( '.toc__list a' );
	if ( tocLinks.length && hasIO ) {
		var byId = {};
		tocLinks.forEach( function ( link ) {
			byId[ decodeURIComponent( link.hash.slice( 1 ) ) ] = link;
		} );
		var headings = document.querySelectorAll( '.policies__body h2[id]' );
		var setActive = function ( id ) {
			tocLinks.forEach( function ( link ) {
				link.classList.remove( 'is-active' );
				link.removeAttribute( 'aria-current' );
			} );
			if ( byId[ id ] ) {
				byId[ id ].classList.add( 'is-active' );
				byId[ id ].setAttribute( 'aria-current', 'true' );
			}
		};
		var tocObserver = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						setActive( entry.target.id );
					}
				} );
			},
			{ rootMargin: '-15% 0px -70% 0px' }
		);
		headings.forEach( function ( h ) {
			tocObserver.observe( h );
		} );
		if ( window.location.hash ) {
			setActive( decodeURIComponent( window.location.hash.slice( 1 ) ) );
		}
	}
} )();
