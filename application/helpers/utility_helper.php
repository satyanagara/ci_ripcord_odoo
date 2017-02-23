<?php

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
