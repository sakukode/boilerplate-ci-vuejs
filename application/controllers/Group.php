<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends MY_Controller {

	private $_modul_name = "group";
    private $_model_name = "group_model";
    private $_model = "";

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

    public function get_all() {
        $search = $this->input->get('q');
        $by = $this->input->get('by');
        $sort = $this->input->get('sort');
        $page = $this->input->get('page');
        $per_page = $this->_PER_PAGE;

        $total = $total = $this->_model->where('name', 'LIKE', $search, TRUE)->count_rows();
        $data = $this->_model->where('name', 'LIKE', $search, TRUE)->order_by($by, $sort)->paginate($per_page, $total, $page);

        $pages = array();
        $number_of_pages = ceil($total / $per_page);

        // set the lower bound as 5 from the current page
        $fromPage = $page - 5;

        // bounds check that you're not calling for 0 or negative number pages
        if($fromPage < 1) {
            $fromPage = 1;
        }

        // set the upper bound for what you want
        $toPage = $fromPage + 9; // how many pages you'd like shown

        // check that it doesn't exceed the maximum number of pages you have
        if($toPage > $number_of_pages) {
            $toPage = $number_of_pages;
        }

        for ($x=$fromPage; $x<= $toPage; $x++) {
            if($x == $page) {
                $pages[] = array('number' => $x, 'class' => 'active', 'current' => TRUE);
            } else {
                $pages[] = array('number' => $x, 'class' => '', 'current' => FALSE);
            }
        }

        if(count($pages) == 1) {
            $pages = array();
        }

        echo json_encode(array('data'=> $data, 'pages' => $pages, 'currentPage'=> $page, 'numberOfPages'=>$number_of_pages));
    }

    public function get() {        
        $id = $this->input->get('id', TRUE);

        if($id) {
            $result = $this->_model->get($id);

            echo json_encode($result);
        }
    }

    public function insert() {       
        if($this->_validation()) 
        {
            $name = $this->input->post('name', TRUE);
            $description = $this->input->post('description', TRUE);

            $result = $this->ion_auth->create_group($name, $description);

            if($result) {
                $response = array('status' => TRUE, 'message' => $this->ion_auth->messages());
            } else {
                $response = array('status' => FALSE, 'message' => $this->ion_auth->errors());
            }

            echo json_encode($response);
        } else {
            echo json_encode(array('status' => FALSE, 'message' => validation_errors()));
        }
    }

    public function update($id) {    
        if($id) {
            if($this->_validation()) 
            {
                $name = $this->input->post('name', TRUE);
                $description = $this->input->post('description', TRUE);
                
                $result = $this->ion_auth->update_group($id, $name, $description);

                if($result) {
                    $response = array('status' => TRUE, 'message' => $this->ion_auth->messages());
                } else {
                    $response = array('status' => FALSE, 'message' => $this->ion_auth->errors());
                }

                echo json_encode($response);
            } else {
                echo json_encode(array('status' => FALSE, 'message' => validation_errors()));
            }
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Error System'));
        }
    }

    public function delete() {       
        $id = $this->input->post('id', TRUE);

        if($id) {
            $result = $this->ion_auth->delete_group($id);

            if($result) {
                $response = array('status' => TRUE, 'message' => $this->ion_auth->messages());
            } else {
                $response = array('status' => FALSE, 'message' => $this->ion_auth->errors());
            }

            echo json_encode($response);
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Error System'));
        }        
    }

    public function _validation() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');      

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
        $this->template->set_css('sweetalert.min.css');        
        $this->template->set_css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css', 'remote');        
        $this->template->set_css('https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css', 'remote');        
        $this->template->set_css('AdminLTE.min.css');
        $this->template->set_css('skin-blue.min.css');    
    }

    protected function _loadjs() {      
        $this->template->set_js('jquery-2.2.3.min.js','header');
        $this->template->set_js('bootstrap.min.js','footer');
        $this->template->set_js('sweetalert.min.js','footer');   
        $this->template->set_js('app.min.js','footer'); 
        $this->template->set_js(base_url().'build/vue.js','footer', 'remote');  
        $this->template->set_js(base_url().'build/vue-router.js','footer', 'remote'); 
        $this->template->set_js(base_url().'build/vue-animated-list.js','footer', 'remote'); 
         $this->template->set_js(base_url().'build/vue-validator.js','footer', 'remote'); 
        $this->template->set_js(base_url().'build/'.$this->_modul_name.'.js','footer', 'remote');      
    }
}
