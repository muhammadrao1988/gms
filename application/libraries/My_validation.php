<?php
/**
 * Developed by Adnan Bashir.
 * Email: pisces_adnan@hotmail.com
 * Autour: Adnan Bashir
 * Date: 5/26/12
 * Time: 12:07 PM
 */

class MY_Form_validation extends CI_Form_validation {

	function unique($value, $params) {

		$CI =& get_instance();
		$CI->load->database();

		$CI->form_validation->set_message('unique','The %s is already being used.');

		list($table, $field) = explode(".", $params, 2);

		$query = $CI->db->select($field)->from($table)->where($field, $value)->limit(1)->get();

		if ($query->row()) {
			return false;
		} else {
			return true;
		}

	}
}
