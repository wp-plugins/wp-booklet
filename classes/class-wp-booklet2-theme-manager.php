<?php

class WP_Booklet2_Theme_Manager {
	
	private $_themes;
	private $_default_theme;
	
	function __construct() {
		
		$this->_default_theme = 'light';
		
		$theme_locations = array(
			array(
				'theme_directory' => WP_BOOKLET2_DIR . "themes/booklet",
				'theme_url' => WP_BOOKLET2_URL . "themes/booklet"
			),
			array(
				'theme_directory' => get_stylesheet_directory() . "/wpbooklet",
				'theme_url' => get_stylesheet_directory_uri() . "/wpbooklet"
			)
		);
		
		foreach( $theme_locations as $theme_location ) {
			
			if ( !is_dir( $theme_location['theme_directory'] ) ) { return; }
			
			$contents = scandir( $theme_location['theme_directory'] );
			
			foreach ( $contents as $content ) {
				
				if ( is_dir( $theme_location['theme_directory'] . "/" . $content ) && $content != '.' && $content !== '..' ) {
					
					$theme = new WP_Booklet2_Theme($content);
					$theme->set_directory( realpath( $theme_location['theme_directory'] . '/' . $content ) );
					$theme->set_url( $theme_location['theme_url'] . '/' . $content );
					
					$this->_themes[$content] = $theme;
					
				}
				
			}
			
		}
		
	}
	
	/**
	 * Get all themes
	 *
	 * @return array
	 */
	function get_all_themes() {
		return $this->_themes;
	}
	
	/**
	 * Get default theme
	 *
	 * @return WP_Booklet2_Theme
	 */
	function get_default_theme() {
		$themes = $this->_themes;
		return $themes[ $this->_default_theme ];
	}
	
	/**
	 * Checks theme's existence
	 *
	 * @param string $theme - theme name
	 *
	 * @return bool
	 */
	function theme_exists($theme) {
		return array_key_exists( $theme, $this->_themes );
	}
}