<?php 
/**
 * -----------------------------------------
 * Creates all the dashbord widgets callbacks
 * -----------------------------------------
 * @author < pratheesh@epixelsolutions.com >
 *
*/
	/*
	 * ------------------------------------------------------------
	 * Memebers downlines count
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_user_downlines_count', 'afl_user_downlines_count_callback');
 	add_action('wp_ajax_nopriv_afl_user_downlines_count', 'afl_user_downlines_count_callback');

	function afl_user_downlines_count_callback () {
		$data['text'] 					= '4';
	  $data['title'] 					= 'downline members';
	  $data['link'] 					=  '';
	  $data['box_color'] 			= 'bg-primary';
	  $data['valu_text_color']= 'text-white';
	  $data['text_color'] 		= 'text-dark';
	  $data['icon_color'] 		= 'text-white';

	  echo json_encode($data);
	  die();
	}


	/*
	 * ------------------------------------------------------------
	 * e-Wallet
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_user_e_wallet', 'afl_user_e_wallet_callback');
 	add_action('wp_ajax_nopriv_afl_user_e_wallet', 'afl_user_e_wallet_callback');

	function afl_user_e_wallet_callback () {
		$data['text'] 					= '4';
	  $data['title'] 					= 'E-Wallet';
	  $data['link'] 					=  '';
	  $data['box_color'] 			= 'bg-primary';
	  $data['valu_text_color']= 'text-white';
	  $data['text_color'] 		= 'text-dark';
	  $data['icon_color'] 		= 'text-white';

	  echo json_encode($data);
	  die();
	}

	/*
	 * ------------------------------------------------------------
	 * income & credits
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_user_total_credits', 'afl_user_total_credits_callback');
 	add_action('wp_ajax_nopriv_afl_user_total_credits', 'afl_user_total_credits_callback');

	function afl_user_total_credits_callback () {
		$data['text'] 					= '4';
	  $data['title'] 					= 'Income & credits';
	  $data['link'] 					=  '';
	  $data['box_color'] 			= 'bg-primary';
	  $data['valu_text_color']= 'text-white';
	  $data['text_color'] 		= 'text-dark';
	  $data['icon_color'] 		= 'text-white';

	  echo json_encode($data);
	  die();
	}

	/*
	 * ------------------------------------------------------------
	 * Expense & Debits
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_user_total_debits', 'afl_user_total_debits_callback');
 	add_action('wp_ajax_nopriv_afl_user_total_debits', 'afl_user_total_debits_callback');

	function afl_user_total_debits_callback () {
		$data['text'] 					= '4';
	  $data['title'] 					= 'Expense & Debits';
	  $data['link'] 					=  '';
	  $data['box_color'] 			= 'bg-primary';
	  $data['valu_text_color']= 'text-white';
	  $data['text_color'] 		= 'text-dark';
	  $data['icon_color'] 		= 'text-white';

	  echo json_encode($data);
	  die();
	}
	/*
	 * ------------------------------------------------------------
	 * E-wallet sum
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_user_e_wallet_sum', 'afl_user_e_wallet_sum_callback');
 	add_action('wp_ajax_nopriv_afl_user_e_wallet_sum', 'afl_user_e_wallet_sum_callback');

	function afl_user_e_wallet_sum_callback () {
		$data['text'] = 0;
    $data['link'] =  '#';
    $data['icon_size'] = 'text-3x';
    $data['icon_color'] = 'fa-money';
    $data['title'] = 'E-Wallet Balance';
    $data['box_color'] = 'bg-gray';
    $data['valu_text_color'] = 'text-white';
    $data['currency_text'] = 'text-white';

	  echo json_encode($data);
	  die();
	}
	/*
	 * ------------------------------------------------------------
	 * B-wallet Income
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_b_wallet_income', 'afl_b_wallet_income_callback');
 	add_action('wp_ajax_nopriv_afl_b_wallet_income', 'afl_b_wallet_income_callback');

	function afl_b_wallet_income_callback () {
		$data['text'] = '$ 310';
    $data['title_color'] =  'text-success';
    $data['text_color'] = 'text-muted';
    $data['title'] = 'B-wallet Income';

	  echo json_encode($data);
	  die();
	}
	/*
	 * ------------------------------------------------------------
	 * B-wallet Expense
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_b_wallet_expense', 'afl_b_wallet_expense_callback');
 	add_action('wp_ajax_nopriv_afl_b_wallet_expense', 'afl_b_wallet_expense_callback');

	function afl_b_wallet_expense_callback () {
		$data['text'] = '$ 310';
    $data['title_color'] =  'text-danger';
    $data['text_color'] = 'text-muted';
    $data['title'] = 'B-wallet Expense';

	  echo json_encode($data);
	  die();
	}
	/*
	 * ------------------------------------------------------------
	 * B-wallet Balance
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_b_wallet_balance', 'afl_b_wallet_balance_callback');
 	add_action('wp_ajax_nopriv_afl_b_wallet_balance', 'afl_b_wallet_balance_callback');

	function afl_b_wallet_balance_callback () {
		$data['text'] = '$ 310';
    $data['title_color'] =  'text-info';
    $data['text_color'] = 'text-muted';
    $data['title'] = 'B-wallet';

	  echo json_encode($data);
	  die();
	}
	/*
	 * ------------------------------------------------------------
	 * B-wallet Balance
	 * ------------------------------------------------------------
	*/
	add_action('wp_ajax_afl_member_rank', 'afl_member_rank_callback');
 	add_action('wp_ajax_nopriv_afl_member_rank', 'afl_member_rank_callback');

	function afl_member_rank_callback () {

	  echo '<span style="display: inline; padding: .2em .6em .3em; font-size: 100%;font-weight: 700;line-height: 1; color: #fff;
        text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25em;background-color:#eea236;">Active </span>';
	  die();
	}