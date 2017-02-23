<?php
function send_response($status = 0, $msg = '', $data=null) {
	$res ['status'] = $status;
	$res ['msg'] = $msg;
	if (!is_null($data) || !empty($data)) {
		$res ['data'] = $data;
	}
	header ( "Content-Type:application/json" );
	print json_encode ( $res, JSON_NUMERIC_CHECK );
	exit ();
}
