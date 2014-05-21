<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class Controller {

	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
        $map = directory_map(DIR_DOCS . DS . '.config', 1);
        $url = _g('c');
        $url = $url ? $url : _g('t');
        $child_url = _g('f');
        $child_url = $child_url ? $child_url : _g('t1');
        $render = array();
        foreach($map as $file){
            $data[] = $item = load_package('.config/' . $file, $child_url);
            $sort[] = $item['sort'];
            if($item['url'] === $url){
                $nav_active = $item['category'];
                $menu = $item['package']['menu'];
                $category = $item['package']['category'];
                $path = $item['path'];
                $act = $item['url'];
                $current_select = $item['current_select'];
            }
        }
        array_multisort($sort ,SORT_ASC ,SORT_REGULAR ,$data);//按点击排序
        if(!isset($menu)){
            $nav_active = $data[0]['package']['category'];
            $menu = $data[0]['package']['menu'];
            $category = $data[0]['package']['category'];
            $path = $data[0]['path'];
            $act = $data[0]['url'];
            $current_select = $data[0];
        }
        $this->navs = $data;
        $this->current_select = $current_select;
        $this->act = $act;
        $this->path = $path;
        $this->category = $category;
        $this->menu = $menu;
        $this->nav_active = $nav_active;
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */
