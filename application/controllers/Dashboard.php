<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	private $_modul_name = "Dashboard";

	function __construct()
    {
        parent::__construct();
        $this->load->library('template');
        $this->template->set_platform('public');
        $this->template->set_theme('admin-lte');        
    }


	public function index()
	{
		$this->template->set_title($this->_modul_name);
        $this->template->set_meta('author','');
        $this->template->set_meta('keyword','');
        $this->template->set_meta('description','');
            
        $this->_loadcss();
        $this->_loadjs();
        $this->_loadpart();

        $this->template->set_layout('layouts/main');
        $this->template->set_content('pages/dashboard/index');
        $this->template->render();
	}

	protected function _loadpart() {
        $this->template->set_part('navbar', 'parts/navbar');  
        $this->template->set_part('sidebar', 'parts/sidebar');       
        $this->template->set_part('footer', 'parts/footer');
    }


    protected function _loadcss() {
        $this->template->set_css('bootstrap.min.css');        
        $this->template->set_css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css', 'remote');        
        $this->template->set_css('https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css', 'remote');        
        $this->template->set_css('AdminLTE.min.css');
        $this->template->set_css('skin-blue.min.css');    
    }

    protected function _loadjs() {      
        $this->template->set_js('jquery-2.2.3.min.js','footer');
        $this->template->set_js('bootstrap.min.js','footer');    
        $this->template->set_js('app.min.js','footer');      
    }
}
