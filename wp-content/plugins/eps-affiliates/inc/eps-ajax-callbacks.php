<?php 
/*
 * ------------------------------------------------
 * get users name and id
 * ------------------------------------------------
*/
function users_auto_complete_callback() {
	$users[] = array('value'=>'1','data'=>'u1');
	$users[] = array('value'=>'2','data'=>'u2');
	$users[] = array('value'=>'3','data'=>'u3');
	$users[] = array('value'=>'4','data'=>'u4');
	echo json_encode($users);
	die();
}