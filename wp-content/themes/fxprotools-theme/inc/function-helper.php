<?php
/**
 * -----------------------------
 * Fxprotools - Helper Functions
 * -----------------------------
 */

// Styled Array
 function dd($array) {
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function get_courses_by_product_id($product_id){
	$courses_ids = get_post_meta( $product_id , '_related_course',true); 


	if( !$courses_ids){
		return false;
	}

	else{
		$courses = array();

		foreach($courses_ids as $id){
			$courses[] = get_post( $id );
		}
		
		return $courses;
	}
	
}

function get_lessons_by_course_id($course_id){
	$args = array(
	    'posts_per_page'   => -1,
	    'orderby'          => 'menu_order',
	    'post_status'      => 'publish',
	    'post_type'		   => 'sfwd-lessons',
	    'meta_query' => array(
	        array(
	            'key'     => 'course_id',
	            'value'   => $course_id,
	            'compare' => '=',
	        ),
	    ),
	   
	);

	$lessons = get_posts( $args );


	if( !$lessons){
		return false;
	}

	else{

		return $lessons;
	}
	
}

function get_user_progress(){
	

	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
	} 

	else {
		return false;
	}

	$course_progress = get_user_meta( $user_id, '_sfwd-course_progress', true );

	if(!$course_progress){
		return false;
	}

	else{
		return $course_progress;
	}

}

function get_course_lesson_progress($course_id, $lesson_id){

	if(!$course_id || !$lesson_id){
		return false;
	}

	else{
		$course_progress = get_user_progress();
		return $course_progress[$course_id]['lessons'][$lesson_id];
	}
	

}
