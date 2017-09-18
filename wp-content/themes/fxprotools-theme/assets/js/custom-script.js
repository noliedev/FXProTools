jQuery(document).ready( function($) {
	$.ajax({
		url: ajax_url,
		type : 'post',
		data : {
			action : 'post_love_add_love',
			post_id : post_id
		},
		success : function( response ) {
			jQuery('#love-count').html( response );
		}
	})

})