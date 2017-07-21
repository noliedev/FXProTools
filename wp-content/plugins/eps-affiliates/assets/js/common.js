$(document).ready(function(){
	$( ".date_time_picker" ).datepicker();
});
$(function () {
    $('.navbar-toggle').click(function () {
        $('.navbar-nav').toggleClass('slide-in');
        $('.side-body').toggleClass('body-slide-in');
        $('#search').removeClass('in').addClass('collapse').slideUp(200);

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').toggleClass('slide-in');
        
    });
<<<<<<< HEAD
     $('.auto_complete').on('keyup click',function(){
  		var autoArray = [];
    	var path 			 = $(this).attr('data-path');
    	var search_key = $(this).val();
    	if (path != '#' && search_key!=undefined) {
    		$.ajax({
			   	type :'POST',
			   	data : {
			   		action:path,
			   	},
			   	url:ajax_object.ajaxurl,
			   	success: function(data){
							var arr = JSON.parse(data);
							var i 	= 0;
							var data_array = [];
							$('.auto_complete').typeahead({
                source: arr,
              });
			   	}
			  });
    	}
    });
=======
    // var ajax_url = '<?php echo "test"; ?>';
    $('.auto_complete').keyup(function(){
    	var path = $(this).attr('data-path');
    	if (path != '#') {
    		$.ajax({
			   	type :'POST',
			   	data : {
			   		action:'users_auto_complete',
			   	},
			   	url:ajax_object.ajaxurl,
			   	success: function(data){
			   		var a = [
				   		{ value: 'Afghan afghani', data: 'AFN' },
					    { value: 'Albanian lek', data: 'ALL' },
					    { value: 'Algerian dinar', data: 'DZD' },
					    { value: 'European euro', data: 'EUR' },
					    { value: 'Angolan kwanza', data: 'AOA' },
					    { value: 'East Caribbean dollar', data: 'XCD' },
					    { value: 'Argentine peso', data: 'ARS' },
					    { value: 'Armenian dram', data: 'AMD' },
					    { value: 'Aruban florin', data: 'AWG' },
					    { value: 'Australian dollar', data: 'AUD' },
					    { value: 'Azerbaijani manat', data: 'AZN' },
					    { value: 'Bahamian dollar', data: 'BSD' },
					    { value: 'Bahraini dinar', data: 'BHD' },
					    { value: 'Bangladeshi taka', data: 'BDT' },
					    { value: 'Barbadian dollar', data: 'BBD' },
			   		];
			   		$('.auto_complete ').autocomplete({
					    lookup: a
					  });
			   	}
			   });
    	}
    });
   
   
>>>>>>> a3eb117dca110ed02010bf0895e5c78cdae5e735
});