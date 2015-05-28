<?php

class WP_Booklet2_Theme {
	
	private $_name;
	private $_directory;
	private $_url;
	
	function __construct($name) {
		$this->_name = $name;	
	}
	
	/**
	 * Set directory
	 *
	 * @param string $directory - theme folder path
	 *
	 * @return void
	 */
	function set_directory($directory) {
		$this->_directory = $directory;
	}
	
	/**
	 * Set URL
	 *
	 * @param string $url - theme URL
	 *
	 * @return void
	 */
	function set_url($url) {
		$this->_url = $url;
	}
	
	/**
	 * Get directory
	 *
	 * @return string
	 */
	function get_directory() {
		return $this->_directory;
	}
	
	/**
	 * Get URL
	 *
	 * @return string
	 */
	function get_url() {
		return $this->_url;
	}
	
	/**
	 * Get name
	 *
	 * @return string
	 */
	function get_name() {
		return $this->_name;
	}
	
	/**
	 * Get booklet.php path
	 *
	 * @return string
	 */
	function get_booklet_template_path() {
		return realpath( $this->_directory . "/booklet.php" );
	}
	
}