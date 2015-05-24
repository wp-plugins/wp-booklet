<?php

class WP_Booklet2_Controller {

	function __construct() {

		//Initialize localization
		add_action( 'init', array( &$this, 'initialize_localization' ) );

		//Create booklet metaboxes
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );

		//Add settings page
		add_action('admin_menu', array( &$this, 'booklet_settings_page' ));
		
		//Add import page
		add_action('admin_menu', array( &$this, 'booklet_import_page' ));
	
		//Save data
		add_action ( 'save_post', array( &$this, 'save_data' ) );
	
		//Include admin scripts
		add_action( 'admin_enqueue_scripts', array( &$this, 'include_admin_scripts' ) );
		
		//Include frontend scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'include_frontend_scripts' ), 100 );
		
		//Add messages
		add_filter( 'post_updated_messages', array( &$this, 'modify_messages' ) );
		
		//Add shortcode column to booklet admin
		add_filter( 'manage_posts_columns', array( &$this, 'manage_booklet_columns' ) );
		add_filter( 'manage_posts_custom_column', array( &$this, 'manage_booklet_custom_columns' ), 10, 2);
		
		//Add shortcode
		add_shortcode ( 'wp-booklet', array( &$this, 'process_shortcode') );
		
		//Process PDF
        add_action('wp_ajax_process_pdf', array(&$this,'process_pdf') );	
	
        //Verify PDF
        add_action('wp_ajax_verify_pdf', array(&$this,'verify_pdf') );
		
		//Import
		add_action('wp_ajax_import_booklets', array(&$this,'import_booklets') );
		add_action( 'admin_notices', array(&$this,'import_notice') );
	}

	function import_notice() {
		
		if ( isset ( $_GET['page'] ) ) {
			if ( $_GET['page'] == 'wp-booklet2-import' ) {
				return;
			}
		}
		
		$importer = new WP_Booklet2_Importer();
		
		if ( $importer->getRemainingBookletCount() > 0 ) {
			include realpath( WP_BOOKLET2_DIR . "themes/admin/default/import-nag.php" );
		}
	}

	function initialize_localization() {
		load_plugin_textdomain('WP_BOOKLET2', false, realpath( WP_BOOKLET2_DIR . 'languages' ) );
	}

	function add_meta_boxes() {
		
		if ( get_post_type() != 'wp-booklet2' ) {
			return;
		}
		
		//Create pages metabox
		add_meta_box(
			'booklet-pages-metabox',
			'Booklet Pages',
			array( &$this, 'create_pages_metabox' ),
			'wp-booklet2',
			'normal',
			'high'
		);
		
		//Create shortcode metabox
		add_meta_box( 
			'booklet-shortcode-metabox',
			'Booklet shortcode',
			array( &$this, 'create_shortcode_metabox' ),
			'wp-booklet2',
			'side',
			'low'
		);
		
		//Create themes metabox
		add_meta_box( 
			'booklet-themes-metabox',
			'Booklet Themes',
			array( &$this, 'create_themes_metabox' ),
			'wp-booklet2',
			'side',
			'low'
		);
		
		//Create properties metabox
		add_meta_box( 
			'booklet-properties-metabox',
			'Booklet Properties',
			array( &$this, 'create_properties_metabox' ),
			'wp-booklet2',
			'side',
			'low'
		);
		
	}

	function create_pages_metabox( $post ) {
		
		if ( get_post_type() != 'wp-booklet2' ) {
			return;
		}
		
		//Get booklet
		$booklet = new WP_Booklet2_Booklet($post->ID);
		$page_ids = $booklet->get_all_pages();
		
		$pages = array();
		if ( !empty( $page_ids ) ) {
			foreach( $page_ids as $page_id ) {
				$pages[] = new WP_Booklet2_Page($page_id);
			}
		}
		
		//Can produce PDF?
		try {
			$pdf = new WP_Booklet2_PDF( realpath( WP_BOOKLET2_DIR . 'assets/pdf/test.pdf' ) );
			$pdf_capable = $pdf->is_convertible_to_image();
		}
		catch (Exception $e) {
			$pdf_capable = false;
		}
		
		include realpath( WP_BOOKLET2_DIR . "themes/admin/default/pages-metabox.php" );
	}

	function create_properties_metabox( $post ) {
		
		$booklet = new WP_Booklet2_Booklet($post->ID);
		$theme_manager = new WP_Booklet2_Theme_Manager();
		$themes = $theme_manager->get_all_themes();
		
		include realpath( WP_BOOKLET2_DIR . "themes/admin/default/properties-metabox.php" );
	
	}
	
	function create_themes_metabox( $post ) {
		
		$booklet = new WP_Booklet2_Booklet($post->ID);
		$theme_manager = new WP_Booklet2_Theme_Manager();
		$themes = $theme_manager->get_all_themes();
		$default_theme = $theme_manager->get_default_theme();
		$default_theme_name = $default_theme->get_name();
		
		include realpath( WP_BOOKLET2_DIR . "themes/admin/default/themes-metabox.php" );
	
	}
	
	function create_shortcode_metabox( $post ) {
		
		$booklet = new WP_Booklet2_Booklet($post->ID);
		
		include realpath( WP_BOOKLET2_DIR . "themes/admin/default/shortcode-metabox.php" );
	
	}

	function booklet_settings_page() {
		add_option('wp-booklet2-pdf-limit-status',"on");
		add_submenu_page( 'edit.php?post_type=wp-booklet2', 'Settings', 'Settings', 'manage_options', 'wp-booklet2-settings', array( &$this, 'display_settings_page') ); 
		add_action('admin_init', array( &$this, 'booklet_settings_store') );
	}
	
	function booklet_import_page() {
		add_submenu_page( 'edit.php?post_type=wp-booklet2', 'Import', 'Import', 'manage_options', 'wp-booklet2-import', array( &$this, 'display_import_page') ); 
	}
	
	function booklet_settings_store() {
		register_setting('wp-booklet2-settings', 'wp-booklet2-pdf-limit-status');
	}
	
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
	
	function display_import_page() {
		
		$importer = new WP_Booklet2_Importer();
		$remaining_booklets = $importer->getRemainingBookletCount();
		
		include realpath( WP_BOOKLET2_DIR . "themes/admin/default/importer-page.php" );
		
	}

	function save_data($post_id) {
		
		if ( empty( $_POST ) ) {
			return;
		} 
		
		$booklet = new WP_Booklet2_Booklet($post_id);
		
		//Save pages
		if ( !empty( $_POST['wp-booklet-attachment'] ) ) {
			
			$booklet->remove_all_pages();
			
			foreach ( $_POST['wp-booklet-attachment'] as $key => $attachment ) {
				if ( !empty($attachment) ) {
					
					$page = new WP_Booklet2_Page($attachment);
					$page->set_image($attachment);
					$page->set_page_link($_POST['wp-booklet-attachment-properties']['wp-booklet-page-link'][$key]);
					
					$page->update_page();
					
					$booklet->add_page($page->get_id());
					
				}
			}
			
		}
		
		//Save properties
		if ( !empty( $_POST['wp-booklet-metas'] ) ) {
			
			$booklet->set_width( $_POST['wp-booklet-metas']['wp-booklet-width'] );
			$booklet->set_height( $_POST['wp-booklet-metas']['wp-booklet-height'] );
			$booklet->set_speed( $_POST['wp-booklet-metas']['wp-booklet-speed'] );
			$booklet->set_delay( $_POST['wp-booklet-metas']['wp-booklet-delay'] );
			$booklet->set_direction( $_POST['wp-booklet-metas']['wp-booklet-direction'] );
			$booklet->set_cover_behavior( $_POST['wp-booklet-metas']['wp-booklet-closed'] );
			$booklet->set_padding( $_POST['wp-booklet-metas']['wp-booklet-padding'] );
			$booklet->set_theme( $_POST['wp-booklet-metas']['wp-booklet-theme'] );
			
			if ( $_POST['wp-booklet-metas']['wp-booklet-arrows'] == "true" ) {
				$booklet->enable_arrows();
			}
			else {
				$booklet->disable_arrows();
			}
			
			if ( $_POST['wp-booklet-metas']['wp-booklet-pagenumbers'] == "true" ) {
				$booklet->enable_page_numbers();
			}
			else {
				$booklet->disable_page_numbers();
			}
			
			if ( $_POST['wp-booklet-metas']['wp-booklet-thumbnails'] == "true" ) {
				$booklet->enable_thumbnails();
			}
			else {
				$booklet->disable_thumbnails();
			}


		}
		
		$booklet->update_booklet();
		
	}

	function include_admin_scripts($hook_suffix) {
		
		wp_enqueue_style( 'wpbooklet-global-css', WP_BOOKLET2_URL . '/themes/admin/default/css/global.css' );
		
		if ( get_post_type() != 'wp-booklet2' ) { return; }
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		
		wp_enqueue_media();
		wp_dequeue_script( 'autosave' );
		
		wp_enqueue_style( 'wpbooklet-admin-css', WP_BOOKLET2_URL . '/themes/admin/default/css/admin.css' );
		
		if ( get_bloginfo("version") >= 3.8 ) {
			wp_enqueue_style( 'wpbooklet-admin-css-gt-3.8', WP_BOOKLET2_URL . '/themes/admin/default/css/admin-gt-3.8.css' );
		}
	}
	
	function include_frontend_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-effects-core' );
		
		wp_register_script( 'jquery-wpbooklet', WP_BOOKLET2_URL . 'assets/js/jquery.wpbooklet.js' );
		wp_register_script( 'jquery-wpbookletcarousel', WP_BOOKLET2_URL . 'assets/js/jquery.wpbookletcarousel.js' );
		
		wp_enqueue_script( 'jquery-wpbooklet' );
		wp_enqueue_script( 'jquery-wpbookletcarousel' );
		
		$theme_manager = new WP_Booklet2_Theme_Manager();
		$themes = $theme_manager->get_all_themes();
		
		foreach( $themes as $theme ) {
			
			wp_enqueue_style( 'wp-booklet-' . $theme->get_name(), $theme->get_url() . "/booklet.css" );
			
		}
		
	}

	function modify_messages($messages) {
		global $post;
		
		if ( get_post_type() != 'wp-booklet2' ) {
			return $messages;
		}
		
		$booklet = new WP_Booklet2_Booklet( $post->ID );
		
		$messages['wp-booklet'] = array(
			"Shortcode is [wp-booklet id={$booklet->get_shortcode_id()}]",
			"Booklet updated. Shortcode is [wp-booklet id={$booklet->get_shortcode_id()}]",
			"Custom field updated.",
			"Custom field deleted.",
			"Booklet updated. Shortcode is [wp-booklet id={$booklet->get_shortcode_id()}]",
			"Shortcode is [wp-booklet id={$booklet->get_shortcode_id()}]",
			"Booklet published. Shortcode is [wp-booklet id={$booklet->get_shortcode_id()}]",
			"Booklet saved. Shortcode is [wp-booklet id={$booklet->get_shortcode_id()}]",
			"Booklet submitted",
			"Booklet scheduled",
			"Booklet draft updated"
		);
		
		return $messages;
	}

	function manage_booklet_columns( $columns ) {
		if ( get_post_type() != 'wp-booklet2' ) {
			return $columns;
		}
	
		$columns = array_merge( $columns, array( 'shortcode' => 'Shortcode' ) );
		unset( $columns['date'] );
		
		return $columns;
		
	}

	function manage_booklet_custom_columns( $column, $id ) {
		if ( get_post_type() != 'wp-booklet2' ) {
			return;
		}
		
		$booklet = new WP_Booklet2_Booklet($id);
		
		switch( $column ) {
			case 'shortcode' : 
				echo "[wp-booklet id={$booklet->get_shortcode_id()}]";
				break;
		}
	}
	
	function verify_pdf() {
		$pdf_id = $_POST['pdf_id'];
		$pdf_path = get_attached_file( $pdf_id );
	
		//Check file extension
		try {
			$pdf = new WP_Booklet2_PDF($pdf_path);
			$result = array( 'wpb_success'=>true );
		}
		catch(Exception $e) {
			$result = array( 'wpb_success'=>false, 'wpb_message' => 'Please select a valid PDF file.' );
		}
		echo json_encode($result);
		die();
	}
	
	function import_booklets() {
		
		$limit = 1;
		
		$importer = new WP_Booklet2_Importer($limit);
		$importer->import();
		
		$result = array(
			'remaining'=>$importer->getRemainingBookletCount()
		);
		
		echo json_encode( $result );
		die();
		
	}
	
	function process_pdf() {
		
		$pdf_id = $_POST['pdf_id'];
		$pdf_path = get_attached_file( $pdf_id );
		
		$pdf = new WP_Booklet2_PDF($pdf_path);
		
		$return = array(
			'wpb_success'=>false,
			'wpb_message'=>'An unknown error occurred.'
		);
		
		$images = $pdf->get_pages_as_photos();
		
		if ( $images ) {
			
			$return = array (
				'images'=>$images,
				'wpb_success'=>true,
				'wpb_message'=>'PDF processing succeeded.'
			);
			
		}

		echo json_encode($return);
		die();
		
	}
	
	function process_shortcode($atts) {
		extract( $atts );
		
		$booklet = new WP_Booklet2_Booklet($id);
		$booklet->output();
		
		$t = new WP_Booklet2_Theme_Manager();
		
	}

}