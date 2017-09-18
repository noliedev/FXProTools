jQuery(document).ready( function($) {
	$.ajax({
		url: lms.ajax_url,
		type : 'post',
		data : {
			action : 'lms_previous_lesson_complete',
			lesson_id : lesson_id
		},
		success : function( response ) {
			console.log(response);
		}
	})

})