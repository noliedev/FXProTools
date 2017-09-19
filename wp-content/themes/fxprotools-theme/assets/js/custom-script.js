jQuery(document).ready( function($) {
	$(".fx-course-navigation ul li a, .fx-table-lessons a").click(function(){
		var lesson_id = $(this).data('previous-lesson-id');
		var lesson_link = $(this).attr('href');

		if( lesson_id > 1){
			$.ajax({
				url: lms.ajax_url,
				type : 'post',
				data : {
					action : 'lms_lesson_complete',
					lesson_id : lesson_id
				},
				success : function( response ) {
					if(response == '1'){
						window.location = lesson_link;
					}
					else{
						popup_alert("Course Lesson","Please finish the previous lesson first.");
					}
				}
			});
			return false;
		}
	});

	function popup_alert($title, $message){
		$("#alert-modal .modal-title").html($title);
		$("#alert-modal .modal-body p").html($message);
		$('#alert-modal').modal('show');
	}
	
	$("#order_review_heading").appendTo(".col-1");
	$("#order_review").appendTo(".col-1");
	$(".checkout-sidebar").appendTo(".col-2");
	$(".woocommerce").addClass("checkout-holder");
	$(".woocommerce-billing-fields > h3").html("STEP 1: ENTER ACCOUNT DETAILS");
	$("#mobile").val("324234234324423").hide();
	$(".woocommerce-additional-fields").hide();
	
});
