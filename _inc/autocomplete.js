jQuery( function ( $ ) {
	/**
	 * Shows the Enter API key form
	 */
	$( '.autocomplete-enter-api-key-box a' ).on( 'click', function ( e ) {
		e.preventDefault();

		var div = $( '.enter-api-key' );
		div.show( 500 );
		div.find( 'input[name=key]' ).focus();

		$( this ).hide();
	} );
});
