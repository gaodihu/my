<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Email Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/email_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Validate email address
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('get_url_path'))
{
	function get_url_path($string)
	{
		$url_path = preg_replace('/[^\d\w\-]/','-',strtolower(trim($string)));
        $url_path = preg_replace('/(\-+)/','-',$url_path);
        $last_url_path = substr($url_path,-1,1);
        if($last_url_path == '-'){
            $url_path = substr($url_path,0,-1);
        }
        return $url_path;
	}
}
