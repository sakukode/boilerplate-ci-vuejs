<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
    private $_modul_name = 'User';
    private $_per_page = 15;
 
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('template', 'form_validation'));
        $this->load->helper(array('form'));
        $this->template->set_platform('public');
        $this->template->set_theme('admin-lte');        
    }

    public function login()
    {       
        if($this->ion_auth->logged_in()) {
            redirect('/','refresh');
        }

        $this->template->set_title('Login');
        $this->template->set_meta('author','');
        $this->template->set_meta('keyword','');
        $this->template->set_meta('description','');
            
        $this->_loadcss();
        $this->_loadjs();    
        $this->template->set_js(base_url().'build/user/login.js','footer', 'remote'); 

        $this->template->set_layout('layouts/login');
        $this->template->set_content('pages/user/login');
        $this->template->render();
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
        $this->template->set_js(base_url().'build/user/index.js','footer', 'remote');

        $this->template->set_layout('layouts/main');
        $this->template->set_content('pages/user/index');
        $this->template->render();
    }

    public function profile()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
        }

        $this->template->set_title($this->_modul_name);
        $this->template->set_meta('author','');
        $this->template->set_meta('keyword','');
        $this->template->set_meta('description','');
            
        $this->_loadcss();
        $this->_loadjs();
        $this->_loadpart();
        $this->template->set_js(base_url().'build/user/profile.js','footer', 'remote');

        $this->template->set_layout('layouts/main');
        $this->template->set_content('pages/user/profile');
        $this->template->render();
    }

    public function get_all() {
        $search = $this->input->get('q');
        $by = $this->input->get('by');
        $sort = $this->input->get('sort');
        $page = $this->input->get('page');
        $per_page = $this->_per_page;

        $this->load->model('user_model');
        $total = $total = $this->user_model->where('first_name', 'LIKE', $search, TRUE)->where('last_name', 'LIKE', $search, TRUE)->where('email', 'LIKE', $search, TRUE)->count_rows();
        $data = $this->user_model->where('first_name', 'LIKE', $search, TRUE)->where('last_name', 'LIKE', $search, TRUE)->where('email', 'LIKE', $search, TRUE)->order_by($by, $sort)->paginate($per_page, $total, $page);

        $data_custom = array();

        foreach ($data as $row) {
            $group = get_user_group($row->id);
            $row->group = $group[0]->name;
            $data_custom[] = $row;
        }

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

        if(count($pages) == 1) {
            $pages = array();
        }

        echo json_encode(array('data'=> $data_custom, 'pages' => $pages, 'currentPage'=> $page, 'numberOfPages'=>$number_of_pages));
    }

    public function get() {     
        $id = $this->input->get('id', TRUE);

        if($id) {
            $user = user($id);
            $group = get_user_group($user->id);
            $result = array(
                'id' => $user->id,
                'username' => $user->username,
                'email'=> $user->email,
                'firstname'=> $user->first_name,
                'lastname'=> $user->last_name,
                'group'=> $group[0]->name,
                'phone' => $user->phone
            );

            echo json_encode($result);
        } 
    }

    public function insert() {
        if($this->_set_validation() == TRUE) {
            $username = $this->input->post('username');
            $password = "password"; //default password
            $email = $this->input->post('email');
            $firstname = $this->input->post('firstname');
            $lastname = $this->input->post('lastname');   
            $phone = $this->input->post('phone');
            
            $additional_data = array(
                'first_name' => $firstname,
                'last_name' => $lastname,     
                'phone' => $phone       
            );
            $group = array($this->input->post('group'));
            
            $result = $this->ion_auth->register($username, $password, $email, $additional_data, $group);

            if($result) {                
                $response = array('status'=> TRUE, 'message' =>$this->ion_auth->messages());
            } else {                
                $response = array('status'=> TRUE, 'message' =>$this->ion_auth->errors());
            }

            echo json_encode($response);

        } else {
            echo json_encode(array('status'=> FALSE, 'message'=> validation_errors()));
        }
    }

     public function delete() {
        $id = $this->input->post('id', TRUE);
        if($id && $this->ion_auth->is_admin()) {
            $group = 'admin';
            if (!$this->ion_auth->in_group($group, $id))
            {
                $result = $this->ion_auth->delete_user($id);
                if($result) {                    
                    $response = array('status' => TRUE, 'message' => $this->ion_auth->messages());
                } else {                    
                    $response = array('status' => FALSE, 'message' => $this->ion_auth->errors());
                }
                
            } else {                
                $response = array('status' => FALSE, 'message' => "authorized not allowed");             
            }   

            echo json_encode($response);
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'authorized not allowed'));
        }
    }

    public function do_login() {

        if(!$this->input->is_ajax_request()) {
            redirect('/', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('identity', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === TRUE)
        {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
            {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('success_message', $this->ion_auth->messages());
                echo json_encode(array('status' => TRUE, 'redirect' => site_url('/')));
            }
            else
            {
                // if the login was un-successful                
                echo json_encode(array('status' => FALSE, 'message' => $this->ion_auth->errors()));
            }
        }
        else
        {
            $message = validation_errors();
            
            echo json_encode(array('status' => FALSE, 'message' => $message));
        }
    }

     // log the user out
    public function logout()
    {
        // log the user out
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('success_message', $this->ion_auth->messages());
        redirect('login', 'refresh');
    }

    public function get_user_login() {
        if(!$this->input->is_ajax_request()) {
            redirect('/','refresh');
        }

        $user = user_login();
        $group_user = get_user_group($user->id);
        $data = array(
            'username' => $user->username,
            'email' => $user->email,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'group' => $group_user[0]->name
        );

        echo json_encode($data);
    }

    public function update_profile() {
        if(!$this->input->is_ajax_request()) {
            redirect('/','refresh');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('firstname', 'Firstname', 'required');
        $this->form_validation->set_error_delimiters('', '<br />');

        if($this->form_validation->run() == TRUE) {
            $userId = user_login('id');
            $data = array(
                'first_name' => $this->input->post('firstname', TRUE),
                'last_name' => $this->input->post('lastname', TRUE)
            );

            $result = $this->ion_auth->update($userId, $data);
            
            if($result) {
                $response = array('status' => TRUE, 'message' => $this->ion_auth->messages());
            } else {
                $response = array('status'=> TRUE, 'message' => $this->ion_auth->errors());
            }

            echo json_encode($response);
        } else {
            echo json_encode(array('status'=> FALSE, 'message' => validation_errors()));
        }
    }

    public function update_password() {
        $this->form_validation->set_rules('old', 'Old Password', 'required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
        $this->form_validation->set_rules('confirm', 'Confirm New Password', 'required|matches[new]');
        $this->form_validation->set_error_delimiters('', '<br />');

        if($this->form_validation->run() === TRUE) {
            $identity = $this->session->userdata('identity');
            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change)
            {
                //if the password was successfully changed                
                $response = array('status' => TRUE, 'message' => $this->ion_auth->messages());
            }
            else
            {                
                $response = array('status' => FALSE, 'message' => $this->ion_auth->errors());
            }

            echo json_encode($response);
        } else {
            echo json_encode(array('status' => FALSE, 'message' => validation_errors()));
        }
    }

    protected function _set_validation() {
        $tables = $this->config->item('tables','ion_auth');        
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|is_unique['.$tables['users'].'.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique['.$tables['users'].'.email]');
        $this->form_validation->set_rules('firstname', 'Nama Depan', 'required');
        $this->form_validation->set_rules('group', 'Grup', 'required');
        $this->form_validation->set_error_delimiters('', '<br />');
       

        return $this->form_validation->run();
    }

    protected function _loadcss() {
        $this->template->set_css('bootstrap.min.css');        
        $this->template->set_css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css', 'remote');        
        $this->template->set_css('https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css', 'remote');        
        $this->template->set_css('AdminLTE.min.css');
        $this->template->set_css('skin-blue.min.css');  
        $this->template->set_css('sweetalert.min.css');
        $this->template->set_css('icheck-square-blue.css');
    }

    protected function _loadjs() {      
        $this->template->set_js('jquery-2.2.3.min.js','header');
        $this->template->set_js('bootstrap.min.js','footer');          
        $this->template->set_js('sweetalert.min.js','footer');     
        $this->template->set_js('icheck.min.js','footer');
        $this->template->set_js(base_url().'build/vue.js','footer', 'remote');  
        $this->template->set_js(base_url().'build/vue-router.js','footer', 'remote'); 
        $this->template->set_js(base_url().'build/vue-animated-list.js','footer', 'remote'); 
        $this->template->set_js(base_url().'build/vue-validator.js','footer', 'remote');
    }

    protected function _loadpart() {
        $this->template->set_part('navbar', 'parts/navbar');  
        $this->template->set_part('sidebar', 'parts/sidebar');       
        $this->template->set_part('footer', 'parts/footer');
    }

}