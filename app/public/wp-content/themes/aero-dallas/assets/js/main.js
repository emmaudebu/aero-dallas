/**
 * AERO DALLAS — header, mobile menu, scroll reveal, hero flight path,
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

	/* Hero flight path: fly the aircraft only while the hero is on screen. - */
	if ( ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		document.querySelectorAll( '.flight-path' ).forEach( function ( svg ) {
			var motion = svg.querySelector( 'animateMotion' );
			if ( ! motion || typeof motion.beginElement !== 'function' ) {
				return;
			}
			svg.classList.add( 'is-flying' );
			motion.beginElement();
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
