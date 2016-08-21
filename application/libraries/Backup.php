<?php
/**
 * technically replacement for Mysql backup from native CI which only can use Mysql
 * @property CI_DB_active_record $db
 */
class Backup
{
    private $ci;
    private $db;
     
    function __construct() {
        $this->ci =& get_instance();
        $this->db =& $this->ci->db;
    }
     
    /**
     * Backup database
     * @param bool|string $save_to Save to file(path & name) or return it as string(when set to FALSE)
     * @param bool $compress set to TRUE if you want to compress it to GZip
     * @param array $tables List of tables you want to backup
     * @return string|null
     */
    function backup($save_to = FALSE, $compress = FALSE, $tables = array())
    {
        //get all of the tables
        if( count($tables) == 0 )
        {
            $tables = array();
            $query = $this->db->query('SHOW TABLES');
            if ( $query->num_rows() > 0 )
            {
                $field_name = 'Tables_in_' . $this->db->database;
                foreach ( $query->result() as $item )
                {
                    array_push($tables, $item->$field_name);
                }   
            }
        }
        else
        {
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
         
        //cycle through
        $return = '';
        foreach($tables as $table)
        {
            $query = $this->db->get($table);
             
            if ( $query->num_rows() > 0 )
            {
                $return.= 'DROP TABLE `' . $table . '`;';
                $query_create = $this->db->query("SHOW CREATE TABLE `$table`");
                $idx = 'Create Table';
                $create_syntax = $query_create->row_array();
                $create_syntax = $create_syntax[$idx];
                 
                $return.= "\n\n" . $create_syntax . ";\n\n";
                 
                 
                foreach ( $query->result() as $row )
                {
                    $values = array();
                     
                    foreach ( $row as $field=>$value )
                    {
                        array_push($values, $this->db->escape($value));
                    }
                     
                    $return.= 'INSERT INTO `'.$table.'` VALUES(' . implode(',', $values) .  ");\n";
                }
                 
            }
             
            $return.="\n\n\n";
        }
 
        if ( $save_to === FALSE ){ if ( $compress === TRUE ){ return gzencode($return); } else { return $return; } }
        else
        {
            $handle  = fopen($save_to, 'w');
            if ( $handle !== FALSE )
            {
                if ( $compress === FALSE ){ fwrite($handle, $return); }
                else { fwrite($handle, gzencode($return)); }
                fclose($handle);
            }
            else
            {
                exit("Error!! Can't write to file with path '$return'");
            }
        }
    }
}