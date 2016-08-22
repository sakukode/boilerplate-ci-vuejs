<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class People extends MY_Controller {

	private $_modul_name = "people";
    private $_model_name = "people_model";
    private $_table_name = "people";
    private $_primary_key = "id";
    private $_model = "";
    private $_exclude_field = array('id', 'created_at', 'updated_at', 'deleted_at');

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

        $data['path_export_xls'] = site_url($this->_modul_name."/export_xls");
        $data['path_download_template'] = site_url($this->_modul_name."/download_template");

        $this->template->set_layout('layouts/main');
        $this->template->set_content('pages/people/index', $data);
        $this->template->render();
	}

    public function get_all() {
        $search = $this->input->get('q');
        $by = $this->input->get('by');
        $sort = $this->input->get('sort');
        $page = $this->input->get('page');
        $per_page = $this->_PER_PAGE;

        $total = $total = $this->_model->where('first_name', 'LIKE', $search, TRUE)->where('last_name', 'LIKE', $search, TRUE)->count_rows();
        $data = $this->_model->where('first_name', 'LIKE', $search, TRUE)->where('last_name', 'LIKE', $search, TRUE)->order_by($by, $sort)->paginate($per_page, $total, $page);

        $pages = [];
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
            $first_name = $this->input->post('firstname', TRUE);
            $last_name = $this->input->post('lastname', TRUE);
            $gender = $this->input->post('gender', TRUE);
            $email = $this->input->post('email', TRUE);

            $data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'gender' => $gender,
                'email' => $email
            );

            $result = $this->_model->insert($data);

            if($result) {
                $response = array('status' => TRUE, 'message' => 'Success Inserting Data');
            } else {
                $response = array('status' => FALSE, 'message' => 'Failed Inserting Data');
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
                $first_name = $this->input->post('firstname', TRUE);
                $last_name = $this->input->post('lastname', TRUE);
                $gender = $this->input->post('gender', TRUE);
                $email = $this->input->post('email', TRUE);

                $data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'gender' => $gender,
                    'email' => $email
                );

                $result = $this->_model->update($data, $id);

                if($result) {
                    $response = array('status' => TRUE, 'message' => 'Success Updating Data');
                } else {
                    $response = array('status' => FALSE, 'message' => 'Failed Updating Data');
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
            $result = $this->_model->delete($id);

            if($result) {
                $response = array('status' => TRUE, 'message' => 'Success Delete People');
            } else {
                $response = array('status' => FALSE, 'message' => 'Failed Delete People');
            }

            echo json_encode($response);
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Error System'));
        }        
    }

    public function _validation() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('firstname', 'Firstname', 'required');
        $this->form_validation->set_rules('lastname', 'Lastname', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

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

    public function export_xls() {
        $this->load->library('excel');

        $fields = $this->db->list_fields($this->_table_name);
        $header = array();
        $body = array();
        $result = $this->_model->get_all();

        foreach ($fields as $field) {       
            $header[] = ucwords(str_replace("_", " ", $field));
        }

        if($result) {
            foreach ($result as $row) {
                $data = array();
                foreach ($fields as $field) {
                    $data[] = $row->$field;                    
                }

                $body[] = $data;
            }
        }

        $openTo = 'browser';
        $filename = $this->_table_name.'.xlsx';
        $type = 'XLSX';

        $this->excel->write($header, $body, $type, $openTo, $filename);
    }

    public function download_template() {
        $this->load->library('excel');

        $fields = $this->db->list_fields($this->_table_name);
        $header = array();
        $body = array();
        $exclude_field = $this->_exclude_field;
       
        foreach ($fields as $field) {       
            if(!in_array($field, $exclude_field)) {
                $header[] = ucwords(str_replace("_", " ", $field));
            }
        }

        $openTo = 'browser';
        $filename = 'template_'.$this->_table_name.'.xlsx';
        $type = 'XLSX';

        $this->excel->write($header, $body, $type, $openTo, $filename);
    }

    public function import_xls() {
        $input_file = 'filexls';
        $file = $_FILES[$input_file];

        //get fields list
        $exclude_field = $this->_exclude_field;
        $fields = $this->db->list_fields($this->_table_name);
        $header = array();
        foreach ($fields as $field) {
            if(!in_array($field, $exclude_field)) {
                $header[] = $field;
            }
        }

        //config file upload
        $config = array(
            'upload_path' => './upload/',
            'allowed_types' => 'xls|xlsx',
            //'max_size' => '2000',
            'encrypt_name' => TRUE
        );

        //upload file
        $result = $this->_upload_file($input_file, $file, $config);

        if($result['status'] == true) {
            /** if success uploaded file */
            $file = './upload/'.$result['filename'];
        
            //read file xls
            $this->load->library('excel');
            $result = $this->excel->read($file);
            $data = array();

            //push data from row xls
            foreach ($result->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $key => $index) {
                    if($key != 1) {
                        $row = array();
                        if(count($index) == count($header)) {
                            foreach ($header as $k => $v) {
                                $row[$v] = $index[$k];
                            }

                            $data[] = $row;
                        }
                    }
                }
            }

            //insert data to table database
            $result = $this->_model->insert($data);

            if($result) {
                echo json_encode(array('status' => true, 'message' => 'Success Import Data from Xls'));

                //remove file temp xls
                unlink($file);
            } else {
                echo json_encode(array('status' => false, 'message' => 'Failed Import Data from Xls'));
            }
        } else {
            echo json_encode($result);
        }
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
        $this->template->set_js(base_url().'build/people.js','footer', 'remote');      
    }
}
