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
/*
 * -------------------------------------------
* Data tables for user downlines
 * -------------------------------------------
*/
  if ($('.custom-data-tables').length) {

      var table; 
      table = $(".custom-data-tables").DataTable({
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_downlines_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3], 
          "orderable": false, 
        }], 
      }); 
  }
/*
* -------------------------------------------
* Data tables for ewallet summary
* -------------------------------------------
*/
if ($('.custom-ewallet-summary-table').length) {
      var table; 
      table = $(".custom-ewallet-summary-table").DataTable({
      "bFilter" : false, 
      "bInfo": false,
      "searching": false,
      "paging": false,
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_ewallet_summary_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2], 
          "orderable": false, 
        }], 
      }); 
  }

  /*
* -------------------------------------------
* Data tables for ewallet summary
* -------------------------------------------
*/
if ($('.custom-ewallet-all-trans-table').length) {
      var table; 
      table = $(".custom-ewallet-all-trans-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_ewallet_all_transaction_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2], 
          "orderable": false, 
        }], 
      }); 
  }

});

/*
 * -------------------------------------------------------------
 * Expand genealogy tree on click
 * -------------------------------------------------------------
*/
function expandTree(obj) {
  $(obj).find('i').toggleClass('fa-times-circle fa-plus-circle');
    var $uid = $(obj).attr('data-user-id');

    if($(obj).find('i').hasClass('fa-plus-circle')){
      $('.append-child-'+$uid).html('');
      $(obj).parent().parent().removeClass('hv-item-parent');

    } else{
      $(obj).parent().parent().addClass('hv-item-parent');

      if ($uid != undefined) {
        $.ajax({
          type :'POST',
          data : {
            action:'afl_user_expand_genealogy',
            uid:$uid,
          },
          url:ajax_object.ajaxurl,
          success: function(data){
            if (data.length) {
              $(data).hide().appendTo('.append-child-'+$uid).fadeIn(1000);
              // $('.append-child-'+$uid).append(data).fadeIn('slow');
            }
          }
        });
      }
    }
}
