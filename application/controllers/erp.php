<?php
class Erp extends CI_Controller {
	var $user_id;
	var $db;
	var $url;
	var $password;
	var $ep_common = "/xmlrpc/2/common";
	var $ep_object = "/xmlrpc/2/object";
	var $resp_error_code = 404;
	var $resp_success_code = 200;
	public function __construct() {
		parent::__construct ();
		$this->load->library ( 'ripcord/ripcord' );
		$this->load->helper ( 'response' );
		$this->load->config ( 'odoo' );
		$this->db = $this->config->item ( 'db' );
		$this->url = $this->config->item ( 'url' );
		// GETTING U/P FROM URL REQUEST
		$username = $this->input->post_get ( 'u' );
		$this->password = $this->input->post_get ( 'p' );
		
		// AUTH
		$this->user_id = $this->ripcord->client ( $this->url . $this->ep_common )->authenticate ( $this->db, $username, $this->password, array () );
		if (( int ) $this->user_id == 0) {
			send_response ( $this->resp_error_code, 'Wrong Credential' );
		}
	}
	public function index() {
		print "TEST";
		#$this->_resp_success ( 'Hello From API' );
	}
	// ######################
	// ##### CORES ##########
	// ######################
	private function _call($obj, $method, $arg1 = array(), $arg2 = array()) {
		$exec = $this->ripcord->client ( $this->url . $this->ep_object )->execute_kw ( $this->db, $this->user_id, $this->password, $obj, $method, $arg1, $arg2 );
		$this->_resp_error ( $exec );
		$this->_resp_success ( $exec );
	}
	private function _call_search_read($obj, $domain = array(), $params = array()) {
		$this->_call ( $obj, 'search_read', $domain, $params );
	}
	private function _call_read($obj, $id = 0, $fields = array('name')) {
		$this->_call ( $obj, 'read', array (
				$id 
		), $fields );
	}
	private function _call_write($obj, $id = 0, $data = array()) {
		$this->_call ( $obj, 'write', array (
				$id 
		), array (
				$data 
		) );
	}
	private function _call_create($obj, $data = array()) {
		$this->_call ( $obj, 'create', $data );
	}
	private function _call_unlink($obj, $id = 0) {
		$this->_call ( $obj, 'unlink', array (
				array (
						$id 
				) 
		) );
	}
	private function _call_custom($obj, $method, $id = 0) {
		$this->_call ( $obj, $method, array (
				$id 
		) );
	}
	private function _resp_error($exec) {
		if (isset ( $exec ['faultCode'] )) {
			send_response ( $this->resp_error_code, $exec ['faultString'] );
		}
	}
	private function _resp_success($exec) {
		send_response ( $this->resp_success_code, 'OK', $exec );
	}
	
	// ######END OF CORES######
	
	// SAMPLES
	public function asset() {
		$this->_call_search_read ( 'hr.equipment' );
	}
	public function order() {
		$this->_call_search_read ( 'kt.order' );
	}
	public function partner_one() {
		$id = ( int ) $this->uri->segment ( 6, 0 );
		$this->_call_read ( 'res.partner', array (
				$id 
		), array (
				'fields' => array (
						'name' 
				) 
		) );
	}
	public function partner_unlink() {
		$id = ( int ) $this->uri->segment ( 6, 0 );
		$this->_call_unlink ( 'res.partner', $id );
	}
	public function partner() {
		$this->_call_search_read ( 'res.partner', array (
				array (
						array (
								'customer',
								'=',
								FALSE 
						) 
				) 
		), array (
				'fields' => array (
						'name',
						'email',
						'phone' 
				) 
		) );
	}
}

#Dev by Gin2