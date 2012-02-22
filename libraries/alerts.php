<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * alerts
 * 
 * Tools to alert and set/get flashdata from alerts
 * 
 * @license		http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author		Mike Funk
 * @link		http://mikefunk.com
 * @email		mike@mikefunk.com
 * 
 * @file		alerts.php
 * @version		1.0
 * @date		02/22/2012
 * 
 * Copyright (c) 2012
 */

// --------------------------------------------------------------------------

/**
 * alerts class.
 */
class alerts
{
	// --------------------------------------------------------------------------
	
	/**
	 * _ci
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_ci;
	
	// --------------------------------------------------------------------------
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->_ci =& get_instance();
		$this->_ci->load->library('session');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * set_error function.
	 * 
	 * @access public
	 * @param string $msg
	 * @param string $key (default: '')
	 * @return bool
	 */
	public function set_error($msg, $key = '')
	{
		return $this->_set_item('error', $msg, $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * get_error function.
	 * 
	 * @access public
	 * @param string $key (default: '')
	 * @return bool
	 */
	public function get_error($key = '')
	{
		return $this->_get_item('error', $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * set_success function.
	 * 
	 * @access public
	 * @param string $msg
	 * @param string $key (default: '')
	 * @return bool
	 */
	public function set_success($msg, $key = '')
	{
		return $this->_set_item('success', $msg, $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * get_success function.
	 * 
	 * @access public
	 * @param string $key (default: '')
	 * @return bool
	 */
	public function get_success($key = '')
	{
		return $this->_get_item('success', $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * set_info function.
	 * 
	 * @access public
	 * @param string $msg
	 * @param string $key (default: '')
	 * @return bool
	 */
	public function set_info($msg, $key = '')
	{
		return $this->_set_item('info', $msg, $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * get_info function.
	 * 
	 * @access public
	 * @param string $key (default: '')
	 * @return bool
	 */
	public function get_info($key = '')
	{
		return $this->_get_item('info', $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * get_all function.
	 * 
	 * @access public
	 * @return array
	 */
	public function get_all()
	{
		return $this->_get_item();
	}
	
	/**
	 * display_error function.
	 * 
	 * @access public
	 * @param string $key (default: '')
	 * @return string
	 */
	public function display_error($key = '')
	{
		return $this->_display_item('error', $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * display_success function.
	 * 
	 * @access public
	 * @param string $key (default: '')
	 * @return string
	 */
	public function display_success($key = '')
	{
		return $this->_display_item('error', $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * display_info function.
	 * 
	 * @access public
	 * @param string $key (default: '')
	 * @return string
	 */
	public function display_info($key = '')
	{
		return $this->_display_item('info', $key);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * display_all function.
	 * 
	 * @access public
	 * @return string
	 */
	public function display_all()
	{
		$this->_display_item();
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * _set_item function.
	 * 
	 * @access private
	 * @param mixed $type
	 * @param mixed $msg
	 * @param string $key (default: '')
	 * @return bool
	 */
	private function _set_item($type, $msg, $key = '')
	{
		// retrive the flashdata, add to the array, set it again
		$arr = $this->session->flashdata($type);
		if ($arr == FALSE) { $arr = array(); }
		if ($key == '')
		{
			$arr[] = $msg;
		}
		else
		{
			$arr[$key] = $msg;
		}
		$this->session->set_flashdata($arr);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * _get_item function.
	 * 
	 * @access private
	 * @param string $type (default: '')
	 * @param string $key (default: '')
	 * @return array
	 */
	private function _get_item($type = '', $key = '')
	{
		// if it's all alerts
		if ($type == '')
		{
			$arr = array(
				'errors' => $this->session->flashdata('errors'),
				'success' => $this->session->flashdata('success'),
				'info' => $this->session->flashdata('info')
			);
			return $arr;
		}
		// else it's a specific type
		else
		{
			// return all messages for that type
			if ($key == '')
			{
				return $this->session->flashdata($type);
			}
			// return just the message with that key
			else
			{
				$arr = $this->session->flashdata($type);
				if (isset($arr[$key]))
				{
					return $arr[$key];
				}
				else
				{
					return FALSE;
				}
			}
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * _display_item function.
	 * 
	 * @access private
	 * @param string $type (default: '')
	 * @param string $key (default: '')
	 * @return string
	 */
	private function _display_item($type = '', $key = '')
	{
		$out = '';
		
		// if no type is passed, add all message data to output
		if ($type == '')
		{
			$arr = $this->_get_item();
			if ($arr == FALSE) { $arr = array(); }
			
			foreach ($arr as $each_type)
			{
				foreach ($each_type as $item)
				{
					$out .= $this->_wrap($item, $each_type);
				}
			}
		}
		// else just this type
		else
		{
			$arr = $this->_get_item($type);
			if ($arr == FALSE) { $arr = array(); }
			
			// if key is set, just return that key
			if ($key != '')
			{
				$out .= $this->_wrap($arr[$key], $type);
			}
			else
			{
				foreach ($arr as $item)
				{
					$out .= $this->_wrap($item, $type);
				}
			}
		}
		
		return $out;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * _wrap function.
	 * 
	 * @access private
	 * @param string $msg
	 * @param string $type (default: '')
	 * @return void
	 */
	private function _wrap($msg, $type = '')
	{
		$this->config->load('alerts_config');
		
		$out = config_item('before_all');
		$out .= config_item('before_each');
		if ($type != '') 
		{
			$out .= config_item('before_'.$type); 
		}
		else
		{
			$out .= config_item('before_no_type'.$type);
		}
		$out .= $msg;
		$out .= config_item('after_all');
		$out .= config_item('after_each');
		if ($type != '') 
		{
			$out .= config_item('after_'.$type); 
		}
		else
		{
			$out .= config_item('after_no_type'.$type);
		}
		return $out;
	}
	
	// --------------------------------------------------------------------------
}
/* End of file alerts.php */
/* Location: ./ci_authentication/libraries/alerts.php */