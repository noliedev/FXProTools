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
  /*
* -------------------------------------------
* Data tables for ewallet summary
* -------------------------------------------
*/
if ($('.custom-ewallet-income-table').length) {
      var table; 
      table = $(".custom-ewallet-income-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_user_ewallet_income_data_table',
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
* Data tables for business transaction summary  
* ------------------------------------------- 
*/
if ($('.custom-business-summary-table').length) {
      var table; 
      table = $(".custom-business-summary-table").DataTable({
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
            action:'afl_admin_business_summary_data_table',
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
* Data tables for business All transaction
* -------------------------------------------
*/
if ($('.custom-business-all-trans-table').length) {
      var table; 
      table = $(".custom-business-all-trans-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_admin_business_all_transaction_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3,4,5,6], 
          "orderable": false, 
        }], 
      }); 
  }


  /*
* -------------------------------------------
* Data tables for business income report
* -------------------------------------------
*/
if ($('.custom-business-income-history-table').length) {
      var table; 
      table = $(".custom-business-income-history-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_admin_business_income_history_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3,4,5,6], 
          "orderable": false, 
        }], 
      }); 
  }
/*
* -------------------------------------------
* Data tables for business expense report
* -------------------------------------------
*/
if ($('.custom-business-expense-history-table').length) {
      var table; 
      table = $(".custom-business-expense-history-table").DataTable({
       "processing": true, 
       "serverSide": true, 
       "order": [], 
       "ajax": { 
          "url"   : ajax_object.ajaxurl,
          "type"  : "POST",
          "data"  :{
            action:'afl_admin_business_expense_history_data_table',
          }   
        }, 
        "columnDefs": [{ 
          "targets": [0,1,2,3,4,5,6], 
          "orderable": false, 
        }], 
      }); 
  }
/*
  * -------------------------------------------
  * On click Holding tank user
  * -------------------------------------------
*/
 $('.holding-tank-profiles li').click(function(){
  $('#holding-tank-change-model').modal('show');
 });

 $('#place-user').click(function() {
  if ($('#choose-parent').val() == '') {
    $('.notification').html('please choose the parent');
    $('.notification').css('color', 'red');
  } else {
    
  }
 });

});

/*
 * -------------------------------------------------------------
 * Expand genealogy tree on click  expense
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

/*
 * ----------------------------------------------------------------
 * Add error class
 * ----------------------------------------------------------------
*/

 function inform_error (id = '') {
  $('#'+id).addClass('required error');
  $('#'+id).parent('div').addClass('has-error');
 }