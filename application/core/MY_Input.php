<?php if(!defined('BASEPATH')) die('Just Die');

class MY_Input extends CI_Input
{
	private $header;

	function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------------

	function isSecure() {
		return !empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the GET array with Default Value if no return
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function get($index = '', $xss_clean = FALSE, $default = FALSE)
	{
		$CI = &get_instance();
		$CI->load->helper('security');

		$input = $this->_fetch_from_array($_GET, $index, $xss_clean);
		if(!$input && $default !== FALSE)
		{
			return strip_image_tags($default);
		}
		else
		{
			return strip_image_tags($input);
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the POST array with Default Value if no return
	*
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	string
	* @param	bool
	* @param	string
	* @return	string
	*/
	function post($index = '', $xss_clean = FALSE, $default = FALSE)
	{
		$input = $this->_fetch_from_array($_POST, $index, $xss_clean);
		if(!$input && $default !== FALSE)
		{
			return $default;
		}
		else
		{
			return $input;
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	function _array_grab($array, $xss_clean = FALSE)
	{
		$CI = &get_instance();
		$CI->load->helper('security');

		if(isset($array) && is_array($array) && count($array) > 0)
		{
			if($xss_clean === TRUE)
			{
				foreach($array as $ark => $arv)
				{
					$array[$ark] = $CI->security->xss_clean($arv);
				}
			}
			return $array;
		} else {
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

	/**
	* Fetch ALL items from the GET array
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function all_get($xss_clean = FALSE)
	{
		$CI = &get_instance();
		$CI->load->helper('security');

		$input = $this->_array_grab($_GET, $xss_clean);
		if($input)
		{
			foreach($input as &$inp) $inp = strip_image_tags($inp);
			return $input;
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch ALL items from the POST array
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	bool
	* @return	string
	*/
	function all_post($xss_clean = FALSE)
	{
		$CI = &get_instance();
		$CI->load->helper('security');

		$input = $this->_array_grab($_POST, $xss_clean);
		if($input)
		{
			return $input;
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch ALL items from the GET array then "override" by POST array
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	bool
	* @return	string
	*/
	function all_getpost($xss_clean = FALSE)
	{
		$get = $this->all_get($xss_clean);
		$get = $get?$get:array();
		$post = $this->all_post($xss_clean);
		$post = $post?$post:array();
		return $get + $post;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch ALL items from the POST array then "override" by GET array
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	bool
	* @return	string
	*/
	function all_postget($xss_clean = FALSE)
	{
		$post = $this->all_post($xss_clean);
		$post = $post?$post:array();
		$get = $this->all_get($xss_clean);
		$get = $get?$get:array();
		return $post + $get;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the GET array first or the POST with Default Value if no return
	*
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	string	The index key
	* @param	bool	XSS cleaning
	* @return	string
	*/
	function get_post($index = '', $xss_clean = FALSE, $default = FALSE)
	{
		$return = $this->get($index, $xss_clean, $default);
		if($return == $default)
		{
			$return = $this->post($index, $xss_clean, $default);
		}
		
		return $return;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the POST array first or the GET with Default Value if no return
	*
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	string	The index key
	* @param	bool	XSS cleaning
	* @return	string
	*/
	function post_get($index = '', $xss_clean = FALSE, $default = FALSE)
	{
		$return = $this->post($index, $xss_clean, $default);
		if($return == $default)
		{
			$return = $this->get($index, $xss_clean, $default);
		}
		
		return $return;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the COOKIE array
	*
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	/*function cookie($index = '', $xss_clean = FALSE, $default = FALSE)
	{
		$input = $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
		if(!$input && $default) {
			return $default;
		} else {
			return $input;
		}
		return FALSE;
	}*/
	function cookie($index = '', $xss_clean = FALSE, $default = FALSE) {
		$input = $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
		if(!$input && ($default !== FALSE)) {
			return $default;
		} else {
			return $input;
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the SERVER array
	*
	* @author	Arieditya Pr.dH <arieditya.prdh@urbanesia.com>, <arieditya.prdh@yahoo.com>
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function server($index = '', $xss_clean = FALSE, $default = FALSE)
	{
		$input = $this->_fetch_from_array($_SERVER, $index, $xss_clean);
		if(!$input && $default !== FALSE){
			return $default;
		} else {
			return $input;
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	function isAjax()
	{
		return ($this->server('HTTP_X_REQUESTED_WITH') == "XMLHttpRequest") ? true : false;
	}

	// --------------------------------------------------------------------

	function isOAuth($extData = array())
	{
		$CI = &get_instance();

		$req = $this->isOAuthHeader();

		$oKeys = array(
			'signature',
			'consumer_key',
			'signature_method',
			'nonce',
			'timestamp',
			'version',
			'callback',
			'token',
			'verifier'
		);
		$oauth = array();
		foreach($oKeys as $oKey){
			$oauth[$oKey] = empty($req["oauth_{$oKey}"])?
				$CI->input->get_post('oauth_'.$oKey, TRUE, NULL)
				:$req["oauth_{$oKey}"];
		}

		$oauth = $oauth + $extData;

		$check = true;

		foreach($oauth as $ok => $ov){
			$exclude = array('callback', 'token', 'verifier');
//			echo "{$ok} => {$ov}"   ;
			if(in_array($ok, $exclude)) continue;
			if(empty($ov)) $check = false;
		}
		if(!$check) return FALSE;
		return $oauth;

	}

	// --------------------------------------------------------------------

	function isXAuth()
	{
		$CI = &get_instance();
		$xauth['username'] = $CI->input->post('x_auth_username', TRUE, NULL);
		$xauth['password'] = $CI->input->post('x_auth_password', TRUE, NULL);
		$xauth['xauthmod'] = $CI->input->post('x_auth_mode', TRUE, NULL);
		$xauth['xauthmod'] = $xauth['xauthmod'] == "client_auth"? $xauth['xauthmod'] : FALSE;
		$oauth = $this->isOAuth($xauth);

		if(!$oauth) return FALSE;
		return $oauth;
	}

	// --------------------------------------------------------------------

	function isOAuthHeader(){
		$CI = &get_instance();
		$auth = $CI->input->get_request_header('Authorization', TRUE);
		$req = array();
		if (strncasecmp($auth, 'OAuth', 4) == 0)
		{
			$vs = explode(',', substr($auth, 6));
			foreach ($vs as $v)
			{
				if (strpos($v, '='))
				{
					$v = trim($v);
					list($name,$value) = explode('=', $v, 2);
					if (!empty($value) && $value{0} == '"' && substr($value, -1) == '"')
					{
						$value = substr(substr($value, 1), 0, -1);
					}

					if (strcasecmp($name, 'realm') == 0)
					{
						$req['realm'] = $value;
					}
					else
					{
						$req[$name] = $value;
					}
				}
			}
		}
		return $req;
	}

	// --------------------------------------------------------------------
    function _clean_input_keys($str)
    {
        if ( ! preg_match("/^[a-z0-9:_\/-àâçéèêëîôùû]+$/i", $str))
        {
            exit('Disallowed Key Characters : '.$str);
        }

        // Clean UTF-8 if supported
        if (UTF8_ENABLED === TRUE)
        {
            $str = $this->uni->clean_string($str);
        }

        return $str;
    }

}
