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
     * @param $str String Column1 value
     * @param $field String {Table Name}.{Column1 Name}.{Column2 Name}....{Column[N] Name}
     * @return bool
     */
    public function compare_pk($str, $field)
    {
        $pattern = str_repeat('%[^.].',(substr_count($field, '.'))).'%[^.]';
        pretty_var($field);
        pretty_var($pattern);
        $values = sscanf($field, $pattern);
        if (!is_array($values) || count($values) < 2)
            return FALSE;
        pretty_var($values);
        $where = array();
        for ($i=1 ; $i<count($values) ; $i++) {
            if (isset($this->_field_data[$values[$i]]))
                $where[$values[$i]] = $this->_field_data[$values[$i]]['postdata'];
            else
                return FALSE;
        }
        return isset($this->CI->db) ?
            ($this->CI->db->limit(1)->get_where($values[0],$where)->num_rows() === 0)
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
        $str = trim($str);
        $pattern = '/^([\x{0620}-\x{064a} ])+$/u';
        if (preg_match($pattern, $str))
            return TRUE;
        return FALSE;
    }

    function is_not_arabic_text($str) {
        $str = trim($str);
        $pattern = '/([\x{0620}-\x{064a} ])+/u';
        if (preg_match($pattern, $str))
            return FALSE;
        return TRUE;
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