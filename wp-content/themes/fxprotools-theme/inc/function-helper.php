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
	$courses_ids = get_post_meta( $product_id , '_related_course'); 


	if( !$courses_ids){
		return 0;
	}

	else{
		$courses = array();

		foreach($courses_ids as $id){
			$courses[] = get_post( $id[0] );
		}
		
		return $courses;
	}
	
}