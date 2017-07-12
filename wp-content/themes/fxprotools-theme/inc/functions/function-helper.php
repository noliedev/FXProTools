<?php
/**
 * -----------------------------
 * Fxprotools - Helper Functions
 * -----------------------------
 * Fxprotools theme related settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('FX_Helper')){

	class FX_Helper {
		
		// Styled Array
		public function dd($array) {
			echo '<pre>';
			print_r($array);
			echo '</pre>';
		}

	}

}

return new FX_Helper();