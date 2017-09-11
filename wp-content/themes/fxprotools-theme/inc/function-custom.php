<?php
/**
 * ----------------------------
 * Fxprotools - Cusom Functions
 * ----------------------------
 * All custom functions
 */

// add_action('init', 'check_authentication');
// function check_authentication(){
// 	if(!is_user_logged_in()){
// 		get_site_url() . '/login/';
// 		// Force using of js to avoid too many redirect and header sent errors
// 		// echo "<script>location.href = {$url};</script>";
// 	}
// }

add_action('init', 'block_users_wp');
function block_users_wp()
{
	if(is_admin() && ! current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)){
		wp_redirect(home_url());
		exit;
	}
}

function get_courses_by_product_id($product_id)
{
	$courses_ids = get_post_meta($product_id , '_related_course'); 
	$courses     = array();
	if($courses_ids){
		foreach($courses_ids as $id){
			$courses[] = get_post($id[0]);
		}
	}
	return $courses;
}

function get_courses_by_category_id($category_id)
{
	$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'menu_order',
			'order'			   => 'ASC',
			'post_status'      => 'publish',
			'post_type'		   => 'sfwd-courses',
			'tax_query' => array(
			array(
				'taxonomy'    	 => 'ld_course_category',
				'field'  		 => 'term_id',
				'terms'			 => $category_id,
			),
		),
	);
	$courses = get_posts($args);
	return !$courses ? false : $courses;
}

function get_course_metadata($course_id)
{
	return get_post_meta( $course_id, '_sfwd-courses', true );
}

function get_course_price_by_id($course_id)
{
	$course_data = get_course_metadata($course_id);
	$price = $course_data['sfwd-courses_course_price'];
	return is_numeric($price) ? $price : 0;
}

function get_lessons_by_course_id($course_id)
{
	$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'menu_order',
			'order'			   => 'ASC',
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
	$lessons = get_posts($args);
	return !$lessons ? false : $lessons;
}

function get_user_progress()
{
	if(!is_user_logged_in()) return false;
	$current_user    = wp_get_current_user();
	$user_id         = $current_user->ID;
	$course_progress = get_user_meta( $user_id, '_sfwd-course_progress', true );
	return !$course_progress ? false : $course_progress;
}

function get_course_lesson_progress($course_id, $lesson_id)
{
	if(!$course_id || !$lesson_id) return false;
	$course_progress = get_user_progress();
	return $course_progress[$course_id]['lessons'][$lesson_id];
}

function get_lesson_parent_course($lesson_id)
{
	$course_id = get_post_meta($lesson_id , 'course_id',true); 
	$course = get_post($course_id);
	return !$course ? false : $course;
}

function get_course_category_children($course_cat_id)
{
	$children_ids = get_term_children($course_cat_id , 'ld_course_category');

	if( !empty($children_ids) ){
		$child_categories = get_terms( array(
		    'taxonomy'   => 'ld_course_category',
		    'include'    => $children_ids,
		    'hide_empty' => false,
		) ); 
		return !$child_categories ? false: $child_categories;
	} else{
		return false;
	}
}

function get_funnels()
{
	$args = array(
		'posts_per_page'   => -1,
		'orderby'          => 'menu_order',
		'order'			   => 'ASC',
		'post_status'      => 'publish',
		'post_type'		   => 'fx_funnel',
	);
	return get_posts($args);
}

function property_occurence_count($array, $property, $value){
	$count = 0;
	foreach ($array as $object) {
	    if ($object->{$property} == $value) $count++;
	}
	return $count;
}

function get_unique_property_count($array, $property, $url)
{
	$count = 0;
	foreach($array as $object){
		if($object->url == $url){
			$value = $object->{$property};
			$occurrence = property_occurence_count($array, $property, $value);
			if($occurrence == 1) $count += 1;
		}
	}
	return $count;
}

function get_property_count($array, $property, $url)
{
	$count = 0;
	foreach($array as $object){
		if($object->url == $url){
			if($object->{$property} > 0) $count++;
		}
	}
	return $count;
}


function date_is_in_range($date_from, $date_to, $date)
{
	$start_ts = strtotime($date_from); 
 	$end_ts = strtotime($date_to); 
	$ts = strtotime($date);
 	return (($ts >= $start_ts) && ($ts <= $end_ts));
}

function get_funnel_stats($funnel_id, $date_filter = array())
{
	$visits = affiliate_wp()->visits->get_visits( array( 'affiliate_id' => affwp_get_affiliate_id( get_current_user_id()), 'order_by' => 'visit_id' ) );
	if(sizeof($date_filter) > 0){
		foreach($visits as $key => $visit){
			 if( !date_is_in_range($date_filter['date_from'], $date_filter['date_to'], date("m/d/Y", strtotime($visit->date))) ) unset($visits[$key]);
		}
	}
	$funnel = array( 'cp_url' => rwmb_meta('capture_page_url', '', $funnel_id),
		 			 'lp_url' => rwmb_meta('landing_page_url', '', $funnel_id)
		 			);
	$cp_stats = array( 'page_views' => array('all' 	 => 0, 'unique' => 0),
					   'opt_ins' 	=> array('all' 	 => 0, 'rate' 	 => 0),
					   'sales' 		=> array('count' => 0, 'rate'	 => 0),
				);
	$lp_stats = array( 'page_views' => array('all' 	 => 0, 'unique' => 0),
					   'opt_ins' 	=> array('all' 	 => 0, 'rate' 	 => 0),
					   'sales' 		=> array('count' => 0, 'rate' 	 => 0),
				);
	$sales_stats = array( 'customer_sales' => 0, 'distributor_sales' => 0);

	//all
	$cp_stats['page_views']['all'] = property_occurence_count($visits, 'url', $funnel['cp_url']);
	$lp_stats['page_views']['all'] = property_occurence_count($visits, 'url', $funnel['lp_url']);

	//unique
	$cp_stats['page_views']['unique'] = get_unique_property_count($visits, 'ip', $funnel['cp_url']);
	$lp_stats['page_views']['unique'] = get_unique_property_count($visits, 'ip', $funnel['lp_url']);

	//sales
	$lp_stats['sales']['count'] = get_property_count($visits, 'referral_id', $funnel['lp_url']);
	$lp_stats['sales']['rate'] = $lp_stats['sales']['count'] < 1 ? 0 :  round( $lp_stats['sales']['count'] / $lp_stats['page_views']['all'] * 100, 2);
	
	$stats = array( 'capture' => $cp_stats,
					'landing' => $lp_stats,
					'totals' => $sales_stats,
				);

	return $stats;
}

function get_user_checklist()
{
	$checklist = get_user_meta(get_current_user_id(), '_onboard_checklist', true);
	return is_array($checklist) ? $checklist : ThemeSettings::register_user_checklist(get_current_user_id());
}

function get_checklist_next_step_url()
{
	$checklist = get_user_checklist();
	foreach($checklist as $key => $value){
		if( empty($value) ){
			switch($key){
				case 'verified_email': return home_url() . '/verify-email/';
				case 'verified_profile': return home_url() . '/my-account/';
				case 'scheduled_webinar': return home_url() . '/coaching/';
				case 'accessed_products': return home_url() . '/access-products/';
				case 'got_shirt': return home_url() . '/free-shirt/';
				case 'shared_video': return home_url() . '/share-video/';
				case 'referred_friend': return home_url() . '/refer-a-friend/';
			}
		}
	}
	return $url;
}

function resend_email_verification(){
	if( get_current_user_id() > 0)
	{
		ThemeSettings::send_email_verification(get_current_user_id());
	}
}

function verify_email_address($verification_code)
{
	if( get_current_user_id() > 0)
	{
		$user = get_user_by('id', get_current_user_id() );
		$secret = "fxprotools-";
		$hash = MD5( $secret . $user->data->user_email);
		if($hash == $verification_code)
		{
			$checklist = get_user_checklist();
			$checklist['verified_email'] = true;
			update_user_meta( get_current_user_id(), '_onboard_checklist', $checklist );
			return true;
		} else{
			return false;
		}
	} else {
		return false;
	}
}

function get_user_referrals(){
	if(get_current_user_id() > 0){
		$affiliate_id = affwp_get_affiliate_id( get_current_user_id() );
		$affiliate_referrals = affiliate_wp()->referrals->get_referrals( array(
			'number'       => -1,
			'affiliate_id' => $affiliate_id
		) );
		return $affiliate_referrals;
	}
	
}