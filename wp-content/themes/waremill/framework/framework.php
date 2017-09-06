<?php
/*
 * @name VC
 * @author Anton Iulian-Leonard
 * @email leo@verycreative.info
 * 
 */
class VC {
	/*
	 * @name instance
	 * static variable that will contain the class instance
	*/
	static protected $_instance;

	public $_pathHelpers;

	private $_api_uri;

	public $_prefix;

	public $_devolpment;

	public $_helper_dir;

	function __construct()	{
		add_theme_support('post-thumbnails');

		$this->_helper_dir = 'helpers';

		$this->_pathHelpers = dirname(__FILE__) . '/' . $this->_helper_dir;

		$this->_api_uri = 'http://verycreative.info/framework/resurse';

		$this->_prefix = 'vc_';

		$this->_development = true;

		add_action('wp_enqueue_scripts', array($this, 'loadDefaultStyle'), 9999);
	}
	/*
	 * @name Init
	 * Singleton Class Instantiation
	*/
	static public function init()	{
		if( !(self::$_instance instanceof self) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}
	/*
	 * 
	*/
	public function loadDefaultStyle() {
	    // Remove the REST API endpoint.
	    remove_action('rest_api_init', 'wp_oembed_register_route');

	    // Turn off oEmbed auto discovery.
	    // Don't filter oEmbed results.
	    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

	    // Remove oEmbed discovery links.
	    remove_action('wp_head', 'wp_oembed_add_discovery_links');

	    // Remove oEmbed-specific JavaScript from the front-end and back-end.
	    remove_action('wp_head', 'wp_oembed_add_host_js');
	    		
		if( function_exists('wp_enqueue_style') ) {
			if(is_rtl()){
				wp_enqueue_style( 'custom', get_template_directory_uri() . '/style-rtl.css',null,null);
			}else{
				wp_enqueue_style( 'custom', get_template_directory_uri() . '/style.css?ver=433333'.date('d-m-h-m-s'),null,null); 
			}
		}
		if ( function_exists('wp_enqueue_script') )	{
			wp_deregister_script( 'jquery' );
			wp_enqueue_script( 'jquery', get_template_directory_uri() . '/script.js?ver=2ww25a112322442212'.date('d-m-h-m-s'),null,null,true);
		}
	}
	protected function getPathVcClass( $name )	{
		return  $name . '/' . $name . '.php';
	}
	protected function getVcClass($name)	{
		return $this->_prefix . $name; 
	}
	public function makePath( $path )	{
		$dir = pathinfo($path , PATHINFO_DIRNAME);
		if( is_dir($dir) )
			return true;

		if( $this->makePath($dir) && mkdir($dir, 0777) )
			return true;

		return false;
	}
	public function vcDownload( $path )	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_api_uri . '/' . $path); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch); 
		curl_close($ch);

		if( $output )	{
			$this->makePath($this->_pathHelpers . '/' . $path);
			$file = fopen($this->_pathHelpers . '/' . $path, 'wb');
			fwrite($file, $output);
			fclose($file);

			return true;
		}

		return false;
	}
	protected function helperExists( $path )	{
		if( !file_exists($this->_pathHelpers) ) {
			mkdir($this->_pathHelpers, 0777);
			$this->vcDownload('helper.php');
			require_once $this->_pathHelpers .'/helper.php';

			return false;
		}

		require_once $this->_pathHelpers .'/helper.php';

		if( file_exists($this->_pathHelpers. '/' . $path ) )
			return true;

		return false;
	}
	protected function loadVcClass( $name )	{
		$path = $this->getPathVcClass($name);

		if( $this->helperExists($path) ) {
			require_once $this->_pathHelpers . '/' . $path;

			if( class_exists($this->getVcClass($name)) )
				return true;

			return false;
		}

		if( $this->vcDownload($path) ) {
			require_once $this->_pathHelpers . '/' . $path;

			if( class_exists($this->getVcClass($name)) )
				return true;

			return false;
		}

		return false;
	}
	public function __get( $name )	{
		if( $this->loadVcClass($name) ) {
			$name = $this->getVcClass($name);

			return new $name($this);
		}

		throw new Exception('VeryCreative Helper ' . $name . ' does not exists');
	}
	function __call( $name, $args = '' )	{
		if( $this->loadVcClass($name) )	{
			$name = $this->getVcClass($name);
			$args = func_get_args();
			$instance = new $name($this);

			foreach( $args[1] as $key => $arg )	{
				$instance->{'arg'.$key} = $arg;
			}

			return call_user_func(array($instance, 'init'));
		}
	}
}

$vc = VC::init();