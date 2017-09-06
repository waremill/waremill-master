<?php

abstract class vc_helper {

	protected $_files;

	protected $_vc;

	protected $_path;

	protected $_args;

	protected $_vc_name;

	public function __construct( VC $vc )	{
		$this->_vc = $vc;

		$this->_path = dirname(__FILE__);

		$this->_args = array();

		$this->_vc_name = str_replace($this->_vc->_prefix, '', get_class($this));

		if( $this->_vc->_development && !empty($this->_files) && is_array($this->_files))	{

			foreach($this->_files as $path => $dependency)	{
				if( !file_exists($this->_vc_name. '/' . $path ) )	{
					$this->_vc->vcDownload($this->_vc_name . '/' . $path);
				}
			}
		}
	}
	public function init()	{

	}
	public function enqueue()	{
		add_action('wp_enqueue_scripts', array($this, 'vcWpEnqueue'));
	}
	public function vcWpEnqueue()	{
		if( function_exists('wp_enqueue_style') && function_exists('wp_enqueue_script') )	{
			foreach( $this->_files as $path => $dependecy )	{
				$file = pathinfo($path);

				if( $file['extension'] == 'css' ) {
					wp_enqueue_style( get_class($this) . '_' . $file['filename'], get_stylesheet_directory_uri() . '/framework/' . $this->_vc->_helper_dir . '/' . $this->_vc_name . '/' . $path, array() );
				}

				if( $file['extension'] == 'js' ) {
					wp_enqueue_script( get_class($this) . '_' . $file['filename'], get_stylesheet_directory_uri() . '/framework/' . $this->_vc->_helper_dir . '/' . $this->_vc_name . '/' . $path, $dependecy, null, true );
				}
			}
		}

		return;
	}
	public function __set( $name, $value )	{
		$this->_args[$name] = $value;
	}
	public function __get( $name )	{
		return isset($this->_args[$name]) ? $this->_args[$name] : false;
	}
}