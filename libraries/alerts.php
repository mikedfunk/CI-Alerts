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
	 * @return bool
	 */
	public function set_error($msg)
	{
		return $this->_set_item('error', $msg);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * get_error function.
	 * 
	 * @access public
	 * @return bool
	 */
	public function get_error()
	{
		return $this->_get_item('error');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * set_success function.
	 * 
	 * @access public
	 * @param string $msg
	 * @return bool
	 */
	public function set_success($msg)
	{
		return $this->_set_item('success', $msg);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * get_success function.
	 * 
	 * @access public
	 * @return bool
	 */
	public function get_success()
	{
		return $this->_get_item('success');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * set_warning function.
	 * 
	 * @access public
	 * @param string $msg
	 * @return bool
	 */
	public function set_warning($msg)
	{
		return $this->_set_item('warning', $msg);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * get_warning function.
	 * 
	 * @access public
	 * @return bool
	 */
	public function get_warning()
	{
		return $this->_get_item('warning');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * set_info function.
	 * 
	 * @access public
	 * @param string $msg
	 * @return bool
	 */
	public function set_info($msg)
	{
		return $this->_set_item('info', $msg);
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * get_info function.
	 * 
	 * @access public
	 * @return bool
	 */
	public function get_info()
	{
		return $this->_get_item('info');
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
	 * @return string
	 */
	public function display_error()
	{
		return $this->_display_item('error');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * display_success function.
	 * 
	 * @access public
	 * @return string
	 */
	public function display_success()
	{
		return $this->_display_item('success');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * display_info function.
	 * 
	 * @access public
	 * @return string
	 */
	public function display_info()
	{
		return $this->_display_item('info');
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
		return $this->_display_item();
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * _set_item function.
	 * 
	 * @access private
	 * @param mixed $type
	 * @param mixed $msg
	 * @return bool
	 */
	private function _set_item($type, $msg)
	{
		// retrive the flashdata, add to the array, set it again
		$arr = $this->_ci->session->flashdata($type);
		if ($arr == FALSE || $arr == '') { $arr = array(); }
		$arr[] = $msg;
		$this->_ci->session->set_flashdata($type, serialize($arr));
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * _get_item function.
	 * 
	 * @access private
	 * @param string $type (default: '')
	 * @return array
	 */
	private function _get_item($type = '')
	{
		// if it's all alerts
		if ($type == '')
		{
			$arr = array(
				'error' => unserialize($this->_ci->session->flashdata('error')),
				'success' => unserialize($this->_ci->session->flashdata('success')),
				'warning' => unserialize($this->_ci->session->flashdata('warning')),
				'info' => unserialize($this->_ci->session->flashdata('info'))
			);
			return $arr;
		}
		// else it's a specific type
		else
		{
			return unserialize($this->_ci->session->flashdata($type));
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * _display_item function.
	 * 
	 * @access private
	 * @param string $type (default: '')
	 * @return string
	 */
	private function _display_item($type = '')
	{
		$this->_ci->config->load('alerts_config');
		
		$out = '';
		
		// if no type is passed, add all message data to output
		if ($type == '')
		{
			$arr = $this->_get_item();
			
			if ($arr == FALSE) { $arr = array(); }
			
			foreach ($arr as $type => $items)
			{
				if (is_array($items))
				{
					$out .= config_item('before_all');
					foreach ($items as $item)
					{
						$out .= $this->_wrap($item, $type);
					}
					$out .= config_item('after_all');
				}
			}
		}
		// else just this type
		else
		{	
			$arr = $this->_get_item($type);
			
			if ($arr == FALSE) { $arr = array(); }
			
			if (is_array($arr))
			{
				$out .= config_item('before_all');
				foreach ($arr as $item)
				{	
					$out .= $this->_wrap($item, $type);
				}
				$out .= config_item('after_all');
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
		$this->_ci->config->load('alerts_config');
		
		$out = '';
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