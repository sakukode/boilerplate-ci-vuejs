<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_model extends MY_Model
{
    public $table = 'groups'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key
    
    public function __construct()
    {
        parent::__construct();      
    }
}