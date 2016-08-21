<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utility extends MY_Controller {

	private $_modul_name = "utility";

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
        $this->template->set_content('pages/'.$this->_modul_name.'/index');
        $this->template->render();
	}

    public function get_tables() {
        $tables = $this->db->list_tables();
        $data = array();

        foreach ($tables as $table) {
            $label = str_replace("_", " ", ucwords($table));

            $data[] = array(
                'name' => $table,
                'label' => $label
            );
        }

        echo json_encode($data);
    }

    public function check_key_backup() {
        $this->config->load('utility');
        $key_backup = $this->config->item('key_backup');
        $key = $this->input->get('key');

        if($key == null || $key == "") {
            echo json_encode(array('status'=> FALSE, 'message' => 'key required'));
        } else {
            if($key == $key_backup) {
                echo json_encode(array('status'=> TRUE));
            } else {
                echo json_encode(array('status'=> FALSE, 'message'=> 'your key entered is wrong'));
            }
        }
    }

    public function backup() {
        $tables = $this->input->post('tables');

        //init folder download
        $dir = './download';
        //init file except not deleted
        $leave_files = array('index.html');

        //delete older file temp download
        foreach( glob("$dir/*") as $file ) {
            if( !in_array(basename($file), $leave_files) )
                unlink($file);
        }
        
        if(count($tables) > 0) {

            $this->load->dbutil();
            $dbs = $this->dbutil->list_databases();
            $rand_num = rand();
            $filename = $dbs[0]."-".$rand_num;
            $prefs = array(
                'tables'        => $tables,
                'ignore'        => array(),
                'format'        => 'zip', 
                'filename'      => $filename.'.sql',
                'add_drop'      => TRUE,
                'add_insert'    => TRUE,
                'newline'       => "\n"
            );

            $backup = $this->dbutil->backup($prefs);
            //$this->load->helper('file');
            //write_file('./download/'.$filename.'.sql.gz', $backup);
            if(file_put_contents('./download/'.$filename.'.sql.gz' ,$backup)!=FALSE){
                echo json_encode(array('status' => TRUE, 'path' => base_url().'download/'.$filename.'.sql.gz'));
            } else {
                echo json_encode(array('status'=> FALSE, 'message' => 'Failed Backup Database'));
            }
        } else {
            echo json_encode(array('status'=> FALSE, 'message' => 'Please select a table'));
        }
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
        $this->template->set_js(base_url().'build/vue.js','footer', 'remote');
        $this->template->set_js(base_url().'build/vue-validator.js','footer', 'remote');
        $this->template->set_js(base_url().'build/'.$this->_modul_name.'.js','footer', 'remote');
    }
}
