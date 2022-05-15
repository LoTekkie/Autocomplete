jQuery( function ( $ ) {
	var urlTextGeneration = '';

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

	function alertError(msg='') {
		return swal("AutoComplete Error", msg, "error");
	}

	function alertSuccess(msg ='') {
		return swal('AutoComplete Success', msg, 'success');
	}

	function showSpinner()
	{
		$('#autocomplete .inside').append('<div id="autocomplete-spinner"><i class="fa fa-refresh fa-spin"></i> Processing...</div>');
	}

	function hideSpinner()
	{
		$('#autocomplete-spinner').remove();
	}

	$('#autocomplete-submit').on('click', function ( e ) {
		e.preventDefault();
		let $paragraph = $('p.wp-block-paragraph').first();
		let tokens = $('#autocomplete-tokens').val();
		let temp = $('#autocomplete-temperature').val();
		let readability = $('#autocomplete-readability').is(':checked');

		if (tokens < 1 || tokens > 2048) {
			alertError('Tokens out of range. (1 - 2048)');
			return;
		}

		if (temp > 1.0 || temp < 0.1) {
			alertError('Temperature out of range. (0.1 - 1.0)');
			return;
		}

		if (!$paragraph.text()) {
			alertError('A Paragraph is required.');
			return;
		}

        $.ajax({
            type: 'POST',
            url: '',
            data: {
            },
            datatype: 'json',
            beforeSend: function() {
				showSpinner();
            },
			complete: function () {
				hideSpinner();
			},
            success: function (data) {

            }, error: function () {

            },
        });
	});
	$('#autocomplete .handlediv').html('<span aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="components-panel__arrow" role="img" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg></span>');
	$('#autocomplete .postbox').addClass('closed');


	$('.postbox-header').on('click', function ( e ) {
		let $path = $('#autocomplete button.handlediv svg path');
		if ($('#autocomplete .inside').is(':visible')) {
			$path.attr('d', 'M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z');
		} else {
			$path.attr('d', 'M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z');
		}
	});

});
