/**
 * AERO DALLAS INDUSTRIAL — Request a Quote form.
 * Client-side checks, fetch() submission with inline messages, file picker.
 * Without JavaScript the form posts normally and the server renders results.
 */
( function () {
	'use strict';

	var form = document.getElementById( 'quote-form' );
	if ( ! form || ! window.fetch || ! window.FormData ) {
		return;
	}

	var settings = window.aerodallasQuote;
	if ( ! settings || ! settings.i18n ) {
		return;
	}
	var i18n = settings.i18n;
	var success = document.getElementById( 'quote-success' );
	var summary = document.getElementById( 'quote-errors' );
	var submit = form.querySelector( '[type="submit"]' );
	var fileInput = document.getElementById( 'aq-specs' );
	var fileText = form.querySelector( '.file-drop__text' );
	var dropZone = form.querySelector( '.file-drop' );
	var allowed = /\.(pdf|docx?|xlsx?|jpe?g|png)$/i;

	/* Rules mirror the server-side validation in inc/quote-form.php. */
	var rules = {
		name: function ( v ) {
			return v.trim() ? '' : i18n.name;
		},
		email: function ( v ) {
			if ( ! v.trim() ) {
				return i18n.email;
			}
			return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test( v.trim() ) ? '' : i18n.emailInvalid;
		},
		phone: function ( v ) {
			if ( ! v.trim() ) {
				return i18n.phone;
			}
			var digits = ( v.match( /\d/g ) || [] ).length;
			return /^[\d\s()+.\-\/x]{6,30}$/i.test( v.trim() ) && digits >= 6 ? '' : i18n.phoneInvalid;
		},
		service: function ( v ) {
			return v ? '' : i18n.service;
		},
		description: function ( v ) {
			return v.trim() ? '' : i18n.description;
		},
	};

	function fieldEl( key ) {
		return form.querySelector( '[name="aq_' + key + '"]' );
	}

	function setError( key, message ) {
		var input = fieldEl( key );
		var box = document.getElementById( 'aq-' + key + '-error' );
		if ( ! input || ! box ) {
			return;
		}
		var wrap = input.closest( '.field' );
		var hint = input.getAttribute( 'aria-describedby' ) || '';
		var ids = hint.split( ' ' ).filter( function ( id ) {
			return id && id !== box.id;
		} );
		if ( message ) {
			box.innerHTML = '';
			box.insertAdjacentHTML( 'afterbegin', '<svg class="icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.5M12 16.5h.01"/></svg>' );
			box.appendChild( document.createTextNode( message ) );
			box.hidden = false;
			ids.push( box.id );
			input.setAttribute( 'aria-invalid', 'true' );
			wrap.classList.add( 'has-error' );
		} else {
			box.textContent = '';
			box.hidden = true;
			input.removeAttribute( 'aria-invalid' );
			wrap.classList.remove( 'has-error' );
		}
		if ( ids.length ) {
			input.setAttribute( 'aria-describedby', ids.join( ' ' ) );
		} else {
			input.removeAttribute( 'aria-describedby' );
		}
	}

	function checkFile() {
		var file = fileInput && fileInput.files && fileInput.files[ 0 ];
		if ( ! file ) {
			return '';
		}
		if ( file.size > settings.maxBytes ) {
			return i18n.tooLarge;
		}
		return allowed.test( file.name ) ? '' : i18n.fileType;
	}

	function validate() {
		var errors = {};
		Object.keys( rules ).forEach( function ( key ) {
			var input = fieldEl( key );
			var message = input ? rules[ key ]( input.value ) : '';
			if ( message ) {
				errors[ key ] = message;
			}
		} );
		var fileError = checkFile();
		if ( fileError ) {
			errors.specs = fileError;
		}
		return errors;
	}

	function showSummary( message ) {
		summary.querySelector( '.form-status__message' ).textContent = message;
		summary.hidden = ! message;
	}

	function applyErrors( errors ) {
		[ 'name', 'email', 'phone', 'service', 'description', 'specs' ].forEach( function ( key ) {
			setError( key, errors[ key ] || '' );
		} );
		var keys = Object.keys( errors ).filter( function ( k ) {
			return k !== 'form';
		} );
		if ( keys.length ) {
			showSummary( ( keys.length === 1 ? i18n.oneField : i18n.manyFields ).replace( '%d', keys.length ) );
			var first = form.querySelector( '[aria-invalid="true"]' );
			if ( first ) {
				first.focus();
			}
		} else if ( errors.form ) {
			showSummary( errors.form );
			summary.focus();
		}
	}

	function busy( on ) {
		submit.disabled = on;
		submit.setAttribute( 'aria-busy', on ? 'true' : 'false' );
		submit.textContent = on ? i18n.sending : i18n.submit;
	}

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();
		showSummary( '' );

		var errors = validate();
		if ( Object.keys( errors ).length ) {
			applyErrors( errors );
			return;
		}

		var data = new FormData( form );
		data.append( 'aq_ajax', '1' );
		busy( true );

		// getAttribute: the hidden input named "action" shadows form.action.
		fetch( form.getAttribute( 'action' ), { method: 'POST', body: data, credentials: 'same-origin' } )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( json ) {
				busy( false );
				if ( json && json.success ) {
					form.hidden = true;
					summary.hidden = true;
					success.querySelector( '.form-status__message' ).textContent = json.message;
					success.hidden = false;
					success.focus();
					return;
				}
				var serverErrors = ( json && json.errors ) || {};
				if ( ! Object.keys( serverErrors ).length ) {
					serverErrors.form = ( json && json.message ) || i18n.network;
				}
				applyErrors( serverErrors );
			} )
			.catch( function () {
				busy( false );
				applyErrors( { form: i18n.network } );
			} );
	} );

	/* Clear a field's error as soon as it is corrected. */
	form.addEventListener( 'input', function ( event ) {
		var name = ( event.target.name || '' ).replace( /^aq_/, '' );
		if ( rules[ name ] && event.target.getAttribute( 'aria-invalid' ) === 'true' && ! rules[ name ]( event.target.value ) ) {
			setError( name, '' );
		}
	} );

	/* File picker: show the chosen name; support drag and drop. */
	function updateFileLabel() {
		var file = fileInput.files && fileInput.files[ 0 ];
		fileText.textContent = file ? file.name : fileText.getAttribute( 'data-empty' );
		setError( 'specs', checkFile() );
	}

	if ( fileInput && dropZone ) {
		fileInput.addEventListener( 'change', updateFileLabel );
		[ 'dragenter', 'dragover' ].forEach( function ( type ) {
			dropZone.addEventListener( type, function ( event ) {
				event.preventDefault();
				dropZone.classList.add( 'is-dragover' );
			} );
		} );
		[ 'dragleave', 'drop' ].forEach( function ( type ) {
			dropZone.addEventListener( type, function () {
				dropZone.classList.remove( 'is-dragover' );
			} );
		} );
		dropZone.addEventListener( 'drop', function ( event ) {
			event.preventDefault();
			if ( event.dataTransfer && event.dataTransfer.files.length ) {
				try {
					fileInput.files = event.dataTransfer.files;
					updateFileLabel();
				} catch ( e ) {}
			}
		} );
	}

	/* After a no-JS round trip, move focus to the result. */
	if ( ! summary.hidden ) {
		var firstInvalid = form.querySelector( '[aria-invalid="true"]' );
		( firstInvalid || summary ).focus();
	} else if ( ! success.hidden ) {
		success.focus();
	}
} )();
