<?php

class WP_Booklet2_Settings_Page {
	
	function setUp() {
		
		//Add settings page
		add_action('admin_menu', array( &$this, 'booklet_settings_page' ));
		
	}
	
	/**
	 * Create the settings page
	 *
	 * @return void
	 */
	function booklet_settings_page() {
		add_option('wp-booklet2-pdf-limit-status',"on");
		add_submenu_page( 'edit.php?post_type=wp-booklet2', 'Settings', 'Settings', 'manage_options', 'wp-booklet2-settings', array( &$this, 'display_settings_page') ); 
		add_action('admin_init', array( &$this, 'booklet_settings_store') );
	}
	
	/**
	 * Register options in the settings page
	 *
	 * @return void
	 */
	function booklet_settings_store() {
		register_setting('wp-booklet2-settings', 'wp-booklet2-pdf-limit-status');
	}
	
	/**
	 * Render settings page
	 *
	 * @return void
	 */
	function display_settings_page() {
		
		//Check Ghostscript status
		
		try {
			$cmd = new WP_Booklet2_Command("gswin32c","-v");
			$gs_status = $cmd->run_command("gswin32c -v");
		}
		catch (Exception $e) {
			$gs_status['error'] = true;
			$gs_status['message'] = $e->getMessage();
		}
		
		if ( $gs_status['error'] ) {
			try {
				$cmd = new WP_Booklet2_Command("gswin64c","-v");
				$gs_status = $cmd->run_command("gswin64c -v");
			}
			catch (Exception $e) {
				$gs_status['error'] = true;
				$gs_status['message'] = $e->getMessage();
			}
		}
		
		if ( $gs_status['error'] ) {
			try {
				$cmd = new WP_Booklet2_Command("gs","-v");
				$gs_status = $cmd->run_command();
			}
			catch (Exception $e) {
				$gs_status['error'] = true;
				$gs_status['message'] = $e->getMessage();
			}
		}
		
		//Check Imagemagick status
		try {
			$cmd = new WP_Booklet2_Command("convert","-version");
			$im_status =  $cmd->run_command();
		}
		catch (Exception $e) {
			$im_status['error'] = true;
			$im_status['message'] = $e->getMessage();
		}
		
		//Is uploads folder writable by web server?
		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['path'];
		$writable = "No";
		if ( is_writable($upload_path) ) {
			$writable = "Yes";
		}
		
		//Do the actual test
		try {
			$pdf = new WP_Booklet2_PDF( realpath( WP_BOOKLET2_DIR . 'assets/pdf/test.pdf' ) );
			$actual_test = $pdf->is_convertible_to_image();
		}
		catch (Exception $e) {
			$actual_test = false;
		}
		
		include realpath( WP_BOOKLET2_DIR . "themes/admin/default/settings-page.php" );
	}
	
}