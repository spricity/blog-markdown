<?php

function dbg($data){
    echo '<pre>';
    print_r($data);
    exit;
}
if( !function_exists('_g')){
	function _g($param, $default=''){
		return (!isset($_GET[$param])) ? $default : htmlspecialchars($_GET[$param], ENT_QUOTES);
	}
}
if( !function_exists('_p')){
	function _p($param, $default=''){
		return (!isset($_POST[$param])) ? $default : htmlspecialchars($_POST[$param], ENT_QUOTES);
	}
}
function _form_error($name){
	$ci = & get_instance();
	return isset($ci->error[$name]) ? '<em class="error">' . $ci->error[$name] . '</em>': '';
}

function _form_value($name){
	$ci = & get_instance();
	return isset($ci->value[$name]) ? $ci->value[$name] : '';
}
function show_404(){
    echo '404 error';
    exit;
}
function get_real_url($url, $category){
	$url = str_replace('/', '-', $url);
	$pattern = '/\/?'.$category.'\/?(.*)/';
	preg_match($pattern, $url, $match);
	if(!$match){
		$url = $category . '-' . $url;
	}

	return $url;
}
 if(!function_exists('json')){
	function json($code = null, $msg=null, $url='/'){
			$result['code']         = $code;
			$result['msg']			= $msg;
			$r = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
			$inAjax = $r == 'xmlhttprequest';

			//if($inAjax){
				header('Content-type: text/json');
				echo (json_encode( $result ));exit;
			//}else{
				//header("Location:$url");exit;
			//}
	}
 }
function &get_instance()
{
	return Controller::get_instance();
}
function load_package($file, $seg = ''){
	$path = '';
	$package = read_file(DIR_DOCS . DS . $file);
	$package = json_decode($package);
	$active = false;
	$index_url = isset($package->url) && $package->url ? $package->url : '';
	if($index_url){
		$index_url = get_real_url($index_url, $package->category);
	}

	$package->url = $index_url ? $index_url : $package->category . '-index';

    $current_select = array();

	foreach($package->menu as $key => $item){
		if(is_array($item->path)){
			$item->path = implode(',', $item->path);
		}

		$item_url = isset($item->url) && $item->url ? $item->url : '';
		if($item_url){
			$item_url = get_real_url($item_url, $package->category);
		}
		$item->url = $item_url ? $item_url : $package->category . '-item-' . ($key+1);
		foreach($item->child as $child_key => $child){
			if(is_array($child->path)){
				$child->path = implode(',', $child->path);
			}


			$child_url = isset($child->url) && $child->url ? $child->url : '';
			if($child_url){
				$child_url = get_real_url($child_url, $package->category);
			}
			$child->url = $child_url ? $child_url : $package->category . '-item-' . ($key+1) . '-child-' . ($child_key+1);
		}
		if($seg && $seg === $item->url){
			$path = $item->path;
			$active = true;
			$item->active = true;
            $current_select = $item;
		}else{
			foreach($item->child as $child_key => $child){
				if(is_array($child->path)){
					$child->path = implode(',', $child->path);
				}


				$child_url = isset($child->url) && $child->url ? $child->url : '';
				if($child_url){
					$child_url = get_real_url($child_url, $package->category);
				}
				$child->url = $child_url ? $child_url : $package->category . '-item-' . ($key+1) . '-child-' . ($child_key+1);

				if($seg && $seg === $child->url){
					$item->active = true;
					$path = $child->path;
					$active = true;
                    $current_select = $child;
					$child->active = true;
				}
			}
		}

	}
	$package = json_decode(json_encode($package), true);

    if(isset($package['menu']) && count($package['menu']) > 0){
    	foreach ($package['menu'] as $p){
    		$packageArray[] = $p['sort'];
    	}
    	array_multisort($packageArray ,SORT_ASC ,SORT_REGULAR ,$package['menu']);//按点击排序
    }

    if(isset($package['menu']) && count($package['menu']) > 0){
    	if(!$active){
    		$package['menu'][0]['active'] = true;
            $current_select = $package['menu'][0];
    	}
        $path = $path ? $path : $package['menu'][0]['path'];
    }
	$res['path'] = $path;
	$res['package'] = $package;
	$res['name'] = $package['name'];
	$res['sort'] = $package['sort'];
	$res['category'] = $package['category'];
    $res['current_select'] = $current_select;
	$url = isset($package['url']) && $package['url'] ? $package['url'] : '';
	if($url){
		$url = get_real_url($url, $package['category']);
	}
	$res['url'] = $url ? $url : md5($package['category'] . $package['name']);
	return $res;
}
/**
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
if ( ! function_exists('directory_map'))
{
	function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
	{
		if ($fp = @opendir($source_dir))
		{
			$filedata	= array();
			$new_depth	= $directory_depth - 1;
			$source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			while (FALSE !== ($file = readdir($fp)))
			{
				// Remove '.', '..', and hidden files [optional]
				if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
				{
					continue;
				}

				if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
				{
					$filedata[$file] = directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
				}
				else
				{
					$filedata[] = $file;
				}
			}

			closedir($fp);
			return $filedata;
		}

		return FALSE;
	}
}
/**
 * Read File
 *
 * Opens the file specfied in the path and returns it as a string.
 *
 * @access	public
 * @param	string	path to file
 * @return	string
 */
if ( ! function_exists('read_file'))
{
	function read_file($file)
	{
		if ( ! file_exists($file))
		{
			return FALSE;
		}

		if (function_exists('file_get_contents'))
		{
			return file_get_contents($file);
		}

		if ( ! $fp = @fopen($file, FOPEN_READ))
		{
			return FALSE;
		}

		flock($fp, LOCK_SH);

		$data = '';
		if (filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}
}

// ------------------------------------------------------------------------

/**
 * Write File
 *
 * Writes data to the file specified in the path.
 * Creates a new file if non-existent.
 *
 * @access	public
 * @param	string	path to file
 * @param	string	file data
 * @return	bool
 */
if ( ! function_exists('write_file'))
{
	function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE)
	{
		if ( ! $fp = @fopen($path, $mode))
		{
			return FALSE;
		}

		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);

		return TRUE;
	}
}

/**
 * Header Redirect
 *
 * Header redirect in two flavors
 * For very fine grained control over headers, you could use the Output
 * Library's set_header() function.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the method: location or redirect
 * @return	string
 */
if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri);
		}

		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}
// ------------------------------------------------------------------------

/**
 * Delete Files
 *
 * Deletes all files contained in the supplied directory path.
 * Files must be writable or owned by the system in order to be deleted.
 * If the second parameter is set to TRUE, any directories contained
 * within the supplied base directory will be nuked as well.
 *
 * @access	public
 * @param	string	path to file
 * @param	bool	whether to delete any directories found in the path
 * @return	bool
 */
if ( ! function_exists('delete_files'))
{
	function delete_files($path, $del_dir = FALSE, $level = 0)
	{
		// Trim the trailing slash
		$path = rtrim($path, DIRECTORY_SEPARATOR);

		if ( ! $current_dir = @opendir($path))
		{
			return FALSE;
		}

		while (FALSE !== ($filename = @readdir($current_dir)))
		{
			if ($filename != "." and $filename != "..")
			{
				if (is_dir($path.DIRECTORY_SEPARATOR.$filename))
				{
					// Ignore empty folders
					if (substr($filename, 0, 1) != '.')
					{
						delete_files($path.DIRECTORY_SEPARATOR.$filename, $del_dir, $level + 1);
					}
				}
				else
				{
					unlink($path.DIRECTORY_SEPARATOR.$filename);
				}
			}
		}
		@closedir($current_dir);

		if ($del_dir == TRUE AND $level > 0)
		{
			return @rmdir($path);
		}

		return TRUE;
	}
}
?>
