$(document).ready(function() {

	$('.netlAsyncLoading').each(function() {

		var myself = $(this);

		$.ajax({
				method: 'get',
				dataType: 'html',
				url: myself.data('uri'),
				cache: false,
				
				beforeSend : function( jqXHR, textStatus ) {
					myself.addClass('preloader');
				},
				complete : function( jqXHR, textStatus ) {
					myself.removeClass('preloader');
				},
				error : function( jqXHR, textStatus, errorThrown ) {
					myself.remove();
				},
				success : function( result ) {
					myself.append(result);
					
					// TODO: Individual Callbacks
					if (typeof netlAsyncLoadingCallback === 'function') {
						netlAsyncLoadingCallback();
					}
				}
		});
	});

});
