/**
 * AERO DALLAS INDUSTRIAL — coming soon page: countdown to the launch date. The flight
 * path is started by main.js.
 * Vanilla JS, loaded with defer.
 */
( function () {
	'use strict';

	var calm = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* Countdown. ---------------------------------------------------------- */
	var count = document.querySelector( '.cs-count[data-launch]' );
	if ( ! count ) {
		return;
	}

	var launch = Number( count.getAttribute( 'data-launch' ) );
	// Follow the server clock, so a wrong clock on the device does not matter.
	var offset = Number( count.getAttribute( 'data-now' ) ) - Date.now();
	var reload = count.getAttribute( 'data-reload' ) === '1';
	var startedBefore = launch - ( Date.now() + offset ) > 0;
	var nums = {};
	count.querySelectorAll( '[data-unit]' ).forEach( function ( el ) {
		nums[ el.getAttribute( 'data-unit' ) ] = el;
	} );

	function set( unit, value ) {
		var el = nums[ unit ];
		var text = value < 10 ? '0' + value : String( value );
		if ( ! el || el.textContent === text ) {
			return;
		}
		el.textContent = text;
		if ( ! calm ) {
			el.classList.remove( 'is-tick' );
			void el.offsetWidth; // Restart the animation.
			el.classList.add( 'is-tick' );
		}
	}

	function tick() {
		var left = Math.max( 0, Math.floor( ( launch - ( Date.now() + offset ) ) / 1000 ) );
		set( 'days', Math.floor( left / 86400 ) );
		set( 'hours', Math.floor( ( left % 86400 ) / 3600 ) );
		set( 'minutes', Math.floor( ( left % 3600 ) / 60 ) );
		set( 'seconds', left % 60 );

		if ( left > 0 ) {
			window.setTimeout( tick, 1000 - ( ( Date.now() + offset ) % 1000 ) + 20 );
			return;
		}
		count.classList.add( 'is-done' );
		// The site opens itself at launch: reload, spread out so visitors don't all arrive at once.
		if ( reload && startedBefore ) {
			window.setTimeout( function () {
				window.location.reload();
			}, 1500 + Math.random() * 3000 );
		}
	}

	tick();
} )();
