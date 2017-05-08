<?php
/**
 * Developed by Adnan Bashir.
 * Email: pisces_adnan@hotmail.com
 * Autour: Adnan Bashir
 * Date: 5/30/12
 * Time: 12:56 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . "/class.xmlparser.php";
class Import extends SimpleLargeXMLParser {

    var $DB;
    var $table;
    var $upload_path = '';
    var $type = 'csv';
    var $file_field = '';

    private $full_path_file = '';

    public function __construct() {

        parent::__construct();
    }


    public function do_import() {

        $CI =& get_instance();
        $CI->load->database();
        $this->DB = $CI->db;

        $config['upload_path'] = $this->upload_path;
        $config['allowed_types'] = $this->type;


        $CI->load->library('upload');
        $CI->upload->initialize($config);

        $CI->upload->do_upload($this->file_field);
        $data = $CI->upload->data();
        $full_path_file = $data['full_path'];

        if ($full_path_file) {
            switch ($this->type) {
                case 'xml':
                    $this->loadXML($this->full_path_file);
                    $XML = $this->parseXML();


                    foreach ($XML as $rows) {
                        $import = 'INSERT INTO {$this->table} SET ';
                        foreach ($rows as $col => $rows) {
                            $import .= "`{$col}` = '{$this->DB->escape($rows[0])}' ";
                        }
                        $this->DB->query($import);
                    }

                    $return['total_records'] = count($XML);

                    break;
                default:

                    $handle = fopen("$full_path_file", "r");

                    $i = -1;
                    $columns = '';
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $i++;
                        if ($i == 0) {
                            $columns = $data;
                        } else {
                            foreach ($data as &$a) {
                                $a = addslashes($a);
                            }
                            $import = "INSERT INTO {$this->table} (`" . trim(join("`,`", $columns)) . "`)  VALUES ('" . trim(join('\',\'', $data)) . "')";
                            $this->DB->query($import);
                        }
                    }

                    $return['total_records'] = $i;
                    break;
            }

            return $return;
        } else {
            return $return['error'] = 'File Not Upload';
        }
    }
}

