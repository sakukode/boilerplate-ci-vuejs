<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
    public $table = 'users'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    
    public function __construct()
    {
        parent::__construct();      
    }
}