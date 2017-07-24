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
* Data tables 
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
 * -------------------------------------------------------------
 * Expand genealogy tree on click
 * -------------------------------------------------------------
*/
 if ($('.genealogy-hierarchy').length) {
  $('.load-downlines').click(function(){
    $(this).html('');
    $(this).addClass('load-downlines-expanded');
    $uid = $('.load-downlines').attr('data-user-id');
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
            $('.expanded-genealogy').html(data);
          }
        }
      });
    }
  });
 }

$(".toggleable").on("click", function() {
    if (this.nextElementSibling) {
        var next_branch = $(this).nextAll(".branch");
        next_branch.toggle();
        $(this).toggleClass("hasMore");
        if ($(this).attr('id') == 'root') {
          if ($(this).css("margin-top") == "25px") {
            $(this).css("margin-top", "-7px");
          } else {
            $(this).css("margin-top", "25px");
          };
        };
      }
    });
  
$(".top-button").on("click", function() {
      if (this.nextElementSibling) {
        if ($(this.nextElementSibling).css('display') == "none") {
          $("<div class='blank'><span></span></div>").insertBefore($(this).parent());
          $('.top-button').parent().removeClass('special');       
        } else {
          $(this).parent().siblings('.blank').slideUp(600);
          $('.top-button').parent().addClass('special');        
        }
        $(this.nextElementSibling).slideToggle(600);
      };
});
  
  $(".bottom-button").on("click", function() {
      if ($(this.nextElementSibling).css('display') == "none") {
        $("<div class='blank'><span></span></div>").insertAfter($(this).parent());
      } else {
        // remove inserted stuff
        $('.blank').slideUp(500);
      }
      $(this.nextElementSibling).slideToggle(600);
    });

 
});