<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enhanced Form Validation library by CI Bootstrap 3
 */
class MY_Form_validation extends CI_Form_validation {

	public $CI;

	public function __construct($rules = array())
	{
		parent::__construct($rules);
		$this->CI =& get_instance();
	}

    function run($module = '', $group = '') {
		(is_object($module)) AND $this->CI =& $module;
		return parent::run($group);
	}
	
	/**
	 * Custom rules
	 */

    public function compare_pk($str, $field)
    {
        $field1 = $field2 = '';
        sscanf($field, '%[^.].%[^.].%[^.]', $table,$field1,$field2);
        return isset($this->CI->db , $this->_field_data[$field2])
            ? ($this->CI->db->limit(1)->get_where(
                    $table, array($field1 => $str,$field2 => $this->_field_data[$field2]['postdata']))->num_rows() === 0)
            : FALSE;
    }

	// Check if the input value already exists in the specified database field.
	public function exists($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);
		return isset($this->CI->db)
			? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 1)
			: FALSE;
	}

    function is_arabic_text($str) {
        $pattern = '/^([\x{0620}-\x{064a} ])+$/u';
        if (preg_match($pattern, $str))
            return TRUE;
        return FALSE;
    }

    function is_number($str) {
        $pattern = '/^([0-9]|[\x{0660}-\x{0669}])+$/u';
        if (preg_match($pattern, $str))
            return TRUE;
        return FALSE;
    }

	public function not_value($value, $pram)
	{
		if ($value == $pram)
			return FALSE;
		else
			return TRUE;
	}
}