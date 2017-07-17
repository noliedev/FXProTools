<?php
/*
 * --------------------------------------------------------------
 * Save the detail related to user
 * --------------------------------------------------------------
*/
class Afl_user_register {
	public function __construct(){
		add_action( 'user_register', array($this,'afl_user_reistration_save', 10, 1 ));
	}

	function afl_user_reistration_save( $user_id ) {
    if ( isset( $_POST['first_name'] ) )
    	pr($_POST,1);
	   //update_user_meta($user_id, 'first_name', $_POST['first_name']);
	}
}
$obj = new Afl_user_register;