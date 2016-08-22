<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {

	private $_modul_name = "setting";
    private $_model_name = "setting_model";
    private $_table_name = "settings";
    private $_primary_key = "id";

	function __construct()
    {
        parent::__construct();
        $this->load->library('template');
        $this->template->set_platform('public');
        $this->template->set_theme('admin-lte');      

        $this->load->model($this->_model_name);
        $model_name = $this->_model_name;
        $this->_model = $this->$model_name;  
    }


	public function general()
	{
		$this->template->set_title($this->_modul_name);
        $this->template->set_meta('author','');
        $this->template->set_meta('keyword','');
        $this->template->set_meta('description','');
            
        $this->_loadcss();
        $this->_loadjs();
        $this->template->set_js(base_url().'build/setting/general.js', 'footer', 'remote');
        $this->_loadpart();

        $this->template->set_layout('layouts/main');
        $this->template->set_content('pages/'.$this->_modul_name.'/general');
        $this->template->render();
	}

    public function get_general() {
        $result = $this->_model->where('type', 'general')->get_all();
        $data = array();
        if($result) {
            foreach($result as $row) {               
                switch ($row->name) {
                    case 'sitename':
                        $data['sitename'] = $row->value;
                        break;
                    case 'address':
                        $data['address'] = $row->value;
                        break;
                    case 'phone':
                        $data['phone'] = $row->value;
                        break;
                    case 'perpage':
                        $data['perpage'] = $row->value;
                        break;
                    default:
                        # code...
                        break;
                }
            }
        } else {
            $data = array(
                'sitename' => '',
                'address' => '',
                'phone' => '',
                'perpage' => ''
            );
        }

        echo json_encode($data);
    }

    public function save_general() {
        if($this->_validation_general()) {
            $sitename = $this->input->post('sitename', TRUE);
            $address = $this->input->post('address', TRUE) ? $this->input->post('address', TRUE) : ' ';
            $phone = $this->input->post('phone', TRUE) ? $this->input->post('phone', TRUE) : ' ';
            $perpage = $this->input->post('perpage', TRUE);

            $data = array();

            $data[] = array('name' => 'sitename', 'value' => $sitename);
            $data[] = array('name' => 'address', 'value' => $address);
            $data[] = array('name' => 'phone', 'value' => $phone);
            $data[] = array('name' => 'perpage', 'value' => $perpage);

            $result = $this->_model->update($data, 'name');

            if($result) {
                $response = array('status'=> TRUE, 'message' => 'Success Update General Setting');
            } else {
                $response = array('status' => FALSE, 'message' => "Failed Update General Setting");
            }

            echo json_encode($response);
        } else {
            echo json_encode(array('status'=> FALSE, 'message'=> validation_errors()));
        }
    }

    protected function _validation_general() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('sitename', 'Site Name', 'required');
        $this->form_validation->set_rules('perpage', 'Per Page', 'required|numeric');

        $this->form_validation->set_error_delimiters('', '<br />');

        return $this->form_validation->run();
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
        $this->template->set_js(base_url().'build/vue-router.js','footer', 'remote'); 
        $this->template->set_js(base_url().'build/vue-animated-list.js','footer', 'remote'); 
        $this->template->set_js(base_url().'build/vue-validator.js','footer', 'remote');     
    }
}
