<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('load_controller'))
{
    function load_controller($controller, $method = '')
    {
	
        require_once(FCPATH . APPPATH . 'controllers/' . $controller . '.php');
	
        $controller = new $controller();
	#echo '<pre>';print_r($controller);exit();
	if(!isset($method)){
        	return $controller->$method();
	}
    }
}
