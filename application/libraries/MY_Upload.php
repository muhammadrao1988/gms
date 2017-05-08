<?php
/**
 * Developed by Adnan Bashir.
 * Email: pisces_adnan@hotmail.com
 * Autour: Adnan Bashir
 * Date: 5/30/12
 * Time: 12:56 AM
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Upload extends CI_Upload {


    public function __construct() {

        parent::__construct();

    }


    public function upload_multi($file_name) {


        $error = array();
        $upload_data = array();
        $FILES = $_FILES[$file_name];
        if (is_array($FILES['name'])) {
            foreach ($_FILES as $key => $file) {
                if (!$this->do_upload($file_name)) {
                    $error[] = $this->display_errors();
                } else {
                    $upload_data[] = $this->data();
                }
            }

        } else {
            if (!$this->do_upload($file_name)) {
                $error = $this->display_errors();
            } else {
                $upload_data = $this->data();
            }
        }
        $return['error'] = $error;
        $return['upload_data'] = $upload_data;
        #Then return what happened with the files
        return $return;
    }
}

