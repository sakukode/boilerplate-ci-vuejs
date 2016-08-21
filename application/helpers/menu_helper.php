<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('menu_active'))
{
	function menu_active($menu) {
		$ci =& get_instance();

		$current_menu = $ci->router->class;

		return $current_menu === $menu ? 'class="active"' : NULL;		
	}
}
