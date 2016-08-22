<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_setting'))
{
	function get_setting($name) {
		$CI =& get_instance();
		$CI->load->model('setting_model');

		$setting = $CI->setting_model->where('name', $name)->get();

		if($setting) {
			return $setting->value;
		}
	}

	
}
