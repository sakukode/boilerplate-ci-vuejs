<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Template Library for Codeigniter Web Framework
 * 
 * @author 	Turan Karatuğ <tkaratug@hotmail.com.tr>
 * @version 	v1.0
 * @access 	public
 */

class Template
{
	// Codeigniter Instance
	protected $ci;

	// Platform Selector
	private $platform;

	// Theme Selector
	private $theme;

	// Asset Selector
	private $asset;

	// Layout Selector
	private $layout;

	//parts of layout
	private $_parts = array();

	//main content of layout
	private $_content;

	//text on page
	private $_props = array();

	// Default Theme Selector
	private $default_theme = 'default';

	function __construct()
	{
		$this->ci =& get_instance();
	}

	/**
	 * Setting CSS files.
	 * @param $css_file 	string	file path or url
	 * @param $source 	string	(local|remote)
	 * @return void
	 */
	public function set_css($css_file, $source = 'local')
	{

		// Check is set a platform
		if(!$this->get_platform())
			show_error('Please set platform.<br><br>Example: <br>$this->set_platform(\'public\');');

		if($source == 'remote') {
			$url = $css_file;
		} else {
			$url = base_url().'assets/' . $this->get_platform() . '/themes/' . $this->get_theme() . '/css/' . $css_file;

			// Check is file exists
			if(!file_exists($url)) {
				//echo $url;
				//show_error("Cannot locate stylesheet file: {$url}");
			}
		}

		$this->asset['header']['css'][]	= '<link rel="stylesheet" type="text/css" href="' . $url . '">';
	}

	/**
	 * Setting JS files.
	 * @param $js_file	string	file path or url
	 * @param $location 	string 	(header|footer)
	 * @param $source 	string	(local|remote)
	 * @return void
	 */
	public function set_js($js_file, $location = 'header', $source = 'local')
	{

		// Check is set a platform
		if(!$this->get_platform())
			show_error('Please set platform.<br><br>Example: <br>$this->set_platform(\'public\');');
		
		if($source == 'remote') {
			$url = $js_file;
		} else {
			$url = base_url().'assets/' . $this->get_platform() . '/themes/' . $this->get_theme() . '/js/' . $js_file;

			// Check is file exists
			if(!file_exists($url)) {
				//show_error("Cannot locate javascript file: {$url}.");
			}
		}

		$this->asset[$location]['js'][]	= '<script type="text/javascript" src="' . $url . '"></script>';
	}

	/**
	 * Setting Meta Tags
	 * @param $meta_name 	string	meta tag name
	 * @param $meta_content string	meta tag content
	 * @return void
	 */
	public function set_meta($meta_name, $meta_content)
	{
		$this->asset['header']['meta'][] = '<meta name="' . $meta_name . '" content="' . $meta_content . '">';
	}

	/**
	 * Setting Meta Tags
	 * @param $meta_name 	string	meta tag name
	 * @param $meta_content string	meta tag content
	 * @return void
	 */
	public function set_meta_property($meta_property, $meta_content, $data_dynamic= FALSE)
	{
		if($data_dynamic === TRUE) {
			$this->asset['header']['meta_property'][] = '<meta property="' . $meta_property . '" content="' . $meta_content .'" data-dynamic="true">';
		} else {
			$this->asset['header']['meta_property'][] = '<meta property="' . $meta_property . '" content="' . $meta_content . '">';
		}
	}

	/**
	 * Set Page Title
	 * @param $title string
	 * @return void
	 */
	public function set_title($title)
	{
		$this->asset['header']['title'] = '<title>' . $title . '</title>';
	}

	/**
	 * Get CSS Files
	 * @return array
	 */
	public function get_css()
	{
		return $this->asset['header']['css'];
	}

	/**
	 * Get JS Files
	 * @param $location 	(header|footer)
	 * @return array
	 */
	public function get_js($location = 'header')
	{
		return $this->asset[$location]['js'];
	}

	/**
	 * Get Meta Tags
	 * @return array
	 */
	public function get_meta()
	{
		return $this->asset['header']['meta'];
	}

	/**
	 * Get Meta Tags
	 * @return array
	 */
	public function get_meta_property()
	{
		return $this->asset['header']['meta_property'];
	}

	/**
	 * Get Page Title
	 * @return string
	 */
	public function get_title()
	{
		return $this->asset['header']['title'];
	}

	/**
	 * Set View File
	 * @param $layout string
	 * @return void
	 */
	public function set_layout($layout)
	{
		$this->layout = $layout;
	}

	/**
	 * Get View File
	 * @return string
	 */
	public function get_layout()
	{
		return $this->layout;
	}

	/**
	 * Set Theme
	 * @param $theme string
	 * @return void
	 */
	public function set_theme($theme)
	{
		if(is_dir('assets/' . $this->get_platform() . '/themes/'.$theme))
			$this->theme = $theme;
		else
			show_error("Cannot find theme folder: {$theme}.");
	}

	/**
	 * Get Theme
	 * @return string
	 */
	public function get_theme()
	{
		if(!$this->theme)
			$this->theme = $this->default_theme;

		return $this->theme;
	}

	/**
	 * Set Platform 
	 * @param $platform string (public|admin)
	 * @return void
	 */
	public function set_platform($platform)
	{
		if(is_dir('assets/'.$platform))
			$this->platform = $platform;
		else
			show_error("Cannot find platform folder : {$platform}.");
	}

	/**
	 * Get Platform
	 * @return string
	 */
	public function get_platform()
	{
		return $this->platform;
	}

	/**
	 * Render Layout
	 * @param $data array
	 * @return void
	 */
	public function render($data = array())
	{
		$data['theme']['assets'] = $this->asset;
		$data['parts'] = $this->_parts;
		$data['content'] = $this->_content;
		$this->ci->load->view($this->layout, $data);
	}

	public function set_content($view, $data = array()) {
		$this->_content = $this->ci->load->view($view, $data, TRUE);
	}

	public function set_part($name, $view, $data= array()) {
		$parts = $this->_parts;
		$data['theme']['assets'] = $this->asset;
		$data['props'] = $this->_props;
		$parts[$name] = $this->ci->load->view($view, $data, TRUE);
		$this->_parts = $parts;		
	}

	public function set_props($name, $value) {
		$props = $this->_props;
		$props[$name] = $value;
		$this->_props = $props;		
	}
}
