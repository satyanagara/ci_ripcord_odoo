<?php if(!defined('BASEPATH')) die('Die already!');
/**
 * Created by :
 * 
 * User: AndrewMalachel
 * Date: 3/12/14
 * Time: 11:34 AM
 * Proj: RentalFleets
 */
class MY_Router extends CI_Router{
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */
	function _validate_request($segments)
	{
//		echo "<pre>";
//		var_dump($segments);
		if (count($segments) == 0)
		{
			return $segments;
		}
		$base = APPPATH.'controllers/';
		$class = '';
		$dir = '';
		$has_other = FALSE;
		$temp_dir = '';
		$temp_class = '';
		// going through all the segments
		foreach($segments as $segs) {
			// it's a directory.. save it
			if(is_dir($base.$dir.$segs)){
				// gotta save it in a temp, just in case...
				if(file_exists($base.$dir.$segs.EXT)){
					$has_other = TRUE;
					$temp_dir = $dir;
					$temp_class = $segs;
				}
				$dir .= $segs.'/';

			}
			// it's no more a directory, check if "file" is empty
			elseif(empty($file)) {
				// well, file is empty, so check if the current segment is a file..
				if(file_exists($base.$dir.$segs.EXT)){
					$file = $segs.EXT;
					$class = $segs;
				// it's not a file, but it's no more a directory..
				//check if the directory has default controller, than the segment might be a method...
				} elseif($has_other && $dir == $temp_dir){
					$file = $temp_class.EXT;
					$class = $temp_class;
					$method = $segs;
					break;
				} elseif(file_exists($base.$dir.$this->default_controller.EXT)){
					$file = $this->default_controller.EXT;
					$class= $this->default_controller;
					$method = $segs;
					break;
				// it's not a file, and the default controller is no more... so it's nothing...
				} else {
					show_404($base.$dir.$segs);
				}
			} else {
				$method = $segs;
				break;
			}
		}
		// after all that, if the file is still empty, try the default controller
		if(empty($file) && file_exists($base.$dir.$this->default_controller.EXT)){
			$file = $this->default_controller.EXT;
			$class = $this->default_controller;
		}
		// and if method is also empty, it means they go to "index"...
		if(empty($method)){
			$method = 'index';
		}
		if(empty($class)) {
			$class = $this->default_controller;
			$dir = '';
		}
//		var_dump($dir);
//		var_dump( ucfirst($class) );
//		var_dump($method);

		$this->directory = $dir;
		$this->class = $class;
		$this->method = $method;

//		var_dump($this->fetch_class());
		$seg_imp = implode('/', $segments);
		$seg_imp = str_replace($dir, '', $seg_imp);

		$seg_exp = explode('/', $seg_imp);
		if($seg_exp[0]!=$class) {
			$seg_exp[0]=$class;
		}
		if(isset($seg_exp[1]) && $seg_exp[1]!=$method) {
			$seg_exp[1]=$method;
		}
		return $seg_exp;


		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$segments[0].'.php'))
		{
			return $segments;
		}

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);

			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].'.php'))
				{
					if ( ! empty($this->routes['404_override']))
					{
						$x = explode('/', $this->routes['404_override']);

						$this->set_directory('');
						$this->set_class($x[0]);
						$this->set_method(isset($x[1]) ? $x[1] : 'index');

						return $x;
					}
					else
					{
						show_404($this->fetch_directory().$segments[0]);
					}
				}
			}
			else
			{
				// Is the method being specified in the route?
				if (strpos($this->default_controller, '/') !== FALSE)
				{
					$x = explode('/', $this->default_controller);

					$this->set_class($x[0]);
					$this->set_method($x[1]);
				}
				else
				{
					$this->set_class($this->default_controller);
					$this->set_method('index');
				}

				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.'.php'))
				{
					$this->directory = '';
					return array();
				}

			}

			return $segments;
		}


		// If we've gotten this far it means that the URI does not correlate to a valid
		// controller class.  We will now see if there is an override
		if ( ! empty($this->routes['404_override']))
		{
			$x = explode('/', $this->routes['404_override']);

			$this->set_class($x[0]);
			$this->set_method(isset($x[1]) ? $x[1] : 'index');

			return $x;
		}


		// Nothing else to do at this point but show a 404
		show_404($segments[0]);
	}

	// --------------------------------------------------------------------

}

// END OF MY_Router.php File