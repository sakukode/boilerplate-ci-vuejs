<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	 protected $_PER_PAGE = 0;

        public function __construct()
        {
            parent::__construct();
            $this->_PER_PAGE = get_setting('perpage');

            if (!$this->ion_auth->logged_in()) {
            	redirect('login');
			}
        }

     /**
     * Upload File Icon
     * @param  String $name   input[type file] name
     * @param  Object $file   file
     * @param  Object $config config library upload
     * @return Object         
     */
    protected function _upload_file($name, $file, $config) {
        //process upload picture
        $this->load->library('upload');
        $this->upload->initialize($config);
        //validation upload FALSE
        if(!$this->upload->do_upload($name))
        {
            $response = array(
                'status'  => FALSE,
                'message' => $this->upload->display_errors()
            );
            
            return $response;
        }
        else//validation upload TRUE/success
        {
            $upload    = $this->upload->data();
            $filename  = $upload['file_name'];

            $response = array(
                'status' => TRUE,
                'filename' => $filename,
                'message' => ''
            );
        
            return $response;
        }
    }    
}