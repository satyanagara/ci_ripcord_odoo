<?php
/**
 * Created by :
 * 
 * User: AndrewMalachel
 * Date: 3/28/14
 * Time: 9:05 AM
 * Proj: RentalFleets
 */
if (! function_exists ( 'sitename' )) {
	function sitename($echo = FALSE) {
		$CI = &get_instance ();
		$sitename = $CI->config->item ( 'sitename' );
		if ($echo) {
			echo $sitename;
			return;
		}
		return $sitename;
	}
}

if (! function_exists ( 'trace' )) {
	function trace($var) {
		$type = gettype ( $var );
		if ($type == 'array') {
			print '<pre>';
			print_r ( $var );
			print '</pre>';
		} else {
			print ($var) ;
		}
		exit ();
	}
}
