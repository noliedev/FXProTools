<?php 
function afl_generate_users () {
	echo afl_eps_page_header();
	
	echo afl_content_wrapper_begin();
		afl_generate_users_form();
	echo afl_content_wrapper_end();
}

function afl_generate_users_form () {
	$website = "http://example.com";

	afl_generate_users_form_callback();
	if (!empty($_POST['submit'])) {
		$error_count 		= 0;
		$success_count 	= 0;

		$start_with = $_POST['user_name_start'];
		$count = $_POST['user_count'];

		$sponsor = $_POST['sponsor'];
		preg_match('#\((.*?)\)#', $sponsor, $matches);
		$sponsor_uid = $matches[1];

		for ($i = 1; $i <= $count ; $i++) { 
			$name = $start_with.'-'.$i;
			if (!username_exists( $name )) {
				$userdata = array(
	        'user_login'    	=>  $name ,
	        'user_email'    	=>   $name.'@eps.com',
	        'user_pass'     	=>   $name,
	        'first_name'    	=>   $name,
	        'last_name'     	=>   $name,
        );
        $user = wp_create_user( $name, $name, $name.'@eps.com' );

        if ($user) {
        	$reg_object = new Eps_affiliates_registration;
	        $reg_object->afl_join_member(
	        	array(
	        		'uid'=>$user,
	        		'sponsor_uid' => $sponsor_uid
	        		)
	        );

        	$success_count+=1;
        } else {
        	$error_count+=1;
        }
			} else {
        $error_count+=1;
			}
		}
		if ($success_count) {
			echo wp_set_message('Generated users count : '.$success_count, 'success');
		}
		if ($error_count) {
			echo wp_set_message('Errro occured count : '.$error_count, 'error');
		}
	}
}

function afl_generate_users_form_callback( ){
	$form = array();
	$form['#method'] = 'post';
	$form['#action'] = $_SERVER['REQUEST_URI'];
	$form['user_name_start'] = array(
		'#type' =>'text',
		'#title' =>'starting-with',
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['sponsor'] = array(
		'#type' =>'auto_complete',
		'#title' =>'sponsor',
		'#auto_complete_path' => 'users_auto_complete',
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['user_count'] = array(
		'#type' =>'text',
		'#title' =>'No.of users',
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['submit'] = array(
		'#type' =>'submit',
		'#value' =>'Generate'
	);
	echo afl_render_form($form);
}
function afl_generate_users_form_validation ($name) {

}


/* ------------------- Purchases ------------------------------------------*/
function afl_test_purchases () {
	echo afl_eps_page_header();
	
	echo afl_content_wrapper_begin();
		afl_test_purchses_form();
	echo afl_content_wrapper_end();
}

function afl_test_purchses_form() {
	if (isset($_POST['submit']) && !empty($_POST['product'])) {
		afl_test_purchses_form_submit($_POST);
	}
	$html_tag = '';

	$html_tag .= '<style>.pricingTable{
    text-align: center;
    background: #727cb6;
    padding-top: 5px;
    transition: all 0.5s ease-in-out 0s;
}
.pricingTable > .pricingTable-header{
    color:#fff;
    background: #273238;
    height: 190px;
    position: relative;
    transition: all 0.5s ease 0s;
}
.pricingTable > .pricingTable-header:after{
    content: "";
    border-bottom: 40px solid #727cb6;
    border-left: 248px solid transparent;
    position: absolute;
    right:0px;
    bottom: 0px;
}

.pricingTable-header > .heading{
    display: block;
    padding: 20px 0;
}
.heading > h3{
    margin: 0;
    text-transform: uppercase;
}
.pricingTable-header > .price-value{
    display: block;
    font-size: 60px;
    line-height: 60px;
}
.pricingTable-header > .price-value > .mo{
    font-size: 14px;
    display: block;
    line-height: 0px;
    text-transform: uppercase;
}
.pricingTable-header > .price-value > .currency{
    font-size: 24px;
    margin-right: 4px;
    position: relative;
    bottom:30px;
}
.pricingTable > .pricingContent{
    text-transform: uppercase;
    color:#fff
}
.pricingTable > .pricingContent > ul{
    list-style: none;
    padding: 0;
}
.pricingTable > .pricingContent > ul > li{
    padding: 15px 0;
    border-bottom: 1px solid #fff;
}
.pricingTable > .pricingContent > ul > li:last-child{
    border: 0px none;
}
.pricingTable-sign-up{
    padding: 30px 0;
}
.pricingTable-sign-up > .btn-block{
    width: 80%;
    margin: 0 auto;
    background: #273238;
    border:2px solid #fff;
    color:#fff;
    padding: 15px 12px;
    text-transform: uppercase;
    font-size: 18px;
}

.pink{
    background: #ed687c;
}
.pink .pricingTable-header:after{
    border-bottom-color: #ed687c;
}
.orange{
    background: #e67e22;
}
.orange .pricingTable-header:after{
    border-bottom-color: #e67e22;
}
.blue{
    background: #3498db;
}
.blue .pricingTable-header:after{
    border-bottom-color: #3498db;
}

.pricingTable input{
			visibility:hidden;
		}
.selected {
		border: 4px solid #ccc;
		background: #273238;
		padding: 2px;
		filter: alpha(opacity=100);
		opacity: 1;
}
.pricingTable:hover{cursor:pointer;}
</style>';
	
	$html_tag .= '<form action = "" method ="post">';
	$html_tag .= '<div class="container">';
	$html_tag .= '<div class="row">';

	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "professional">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Professional</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>345';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '<ul>';
	$html_tag .= '<li>$200 initial setup fee</li>';
	$html_tag .= '<li>+$145 monthly renewal</li>';
	$html_tag .= '</ul>';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">145 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';


	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "business">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Business</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>360';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '<ul>';
	$html_tag .= '<li>$200 initial setup fee</li>';
	$html_tag .= '<li>+$160 monthly renewal</li>';
	$html_tag .= '</ul>';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">145 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';

	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "auto-trader">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Auto-Trader</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>410';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '<ul>';
	$html_tag .= '<li>$225 initial setup fee</li>';
	$html_tag .= '<li>+$185 monthly renewal</li>';
	$html_tag .= '</ul>';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">145 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';

	$html_tag .= '<div class="col-md-3 col-sm-6">';
	$html_tag .= '<div class="pricingTable">';
	$html_tag .= '<input type = "radio" name = "product" value = "advanced">';
	$html_tag .= '<div class="pricingTable-header">';
	$html_tag .= '<span class="heading">';
	$html_tag .= '<h3>Advanced (1 on 1)</h3>';
	$html_tag .= '</span>';
	$html_tag .= '<span class="price-value">';
	$html_tag .= '<span class="currency">$</span>5185';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingContent">';
	$html_tag .= '<ul>';
	$html_tag .= '<li>$5000 initial setup fee</li>';
	$html_tag .= '<li>+$185 monthly renewal</li>';
	$html_tag .= '</ul>';
	$html_tag .= '</div>';
	$html_tag .= '<div class="pricingTable-sign-up">
                  <span class="btn btn-block btn-default">145 point</span>
              </div>';
	$html_tag .= '</div>';
	$html_tag .= '</div>';

	$html_tag .= '</div>';
	$html_tag .= '</div>';

	$html_tag .= '<input type ="submit" class = "btn btn-primary" name = "submit" value = "Purchase">';
	$html_tag .= '</form>';
	echo $html_tag;
}

function afl_test_purchses_form_submit () {
	$product = $_POST['product'];
	$args 	 = array();
	
	$args['uid'] 			= get_current_user_id();
	$args['order_id']	=	10;
	$args['afl_point']=	145;

	switch ($product) {
		case 'professional':
			$args['amount_paid']	=	345;
		break;
		case 'business':
			$args['amount_paid']	=	360;
		break;
		case 'Auto-Trader':
			$args['amount_paid']	=	410;
		break;
		case 'advanced':
			$args['amount_paid']	=	5185;
		break;
	}

	$resp = apply_filters('eps_commerce_purchase_complete', $args);
	if ($resp['status'] == 1) {
		echo wp_set_message('Purchase product', 'success');
	} else {
		echo wp_set_message('Error occured', 'error');
	}

}