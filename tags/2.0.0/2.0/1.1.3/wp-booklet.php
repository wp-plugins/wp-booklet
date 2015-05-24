<?php
/**
 * Plugin Name: WP Booklet
 * Plugin URI: http://binarystash.blogspot.com/2013/11/wp-booklet.html
 * Description: Allows creation of flip books using the jQuery Booklet plugin
 * Version: 1.1.3
 * Author: BinaryStash
 * Author URI:  binarystash.blogspot.com
 * License: GPLv2 (http://www.gnu.org/licenses/gpl-2.0.html)
 */
 
//Define constants
if(!defined('WP_BOOKLET_URL')){
	define('WP_BOOKLET_URL', plugin_dir_url(__FILE__) );
}

if(!defined('WP_BOOKLET_DIR')){
	define('WP_BOOKLET_DIR', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR );
}

//Initialize plugin
function WP_Booklet_Instantiate() {
	new WP_Booklet();
}

if ( get_bloginfo("version") >= 3.5 ) {
	//Initialize plugin only if Wordpress version >= 3.5
	add_action( 'plugins_loaded', 'WP_Booklet_Instantiate', 15 );
}

//Class definitions
class WP_Booklet {
	
	public function __construct() {
		global $post;
		
		//Include some Wordpress PHP scripts
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		
		//Create custom post type
		add_action( 'init', array( &$this, 'init_booklet' ) );
		
		//Create booklet metaboxes
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
		
		//Add settings page
		add_action('admin_menu', array( &$this, 'booklet_settings_page' ));
		
		//Save data
		add_action ( 'save_post', array( &$this, 'save_data' ) );
	
		//Include admin scripts
		add_action( 'admin_enqueue_scripts', array( &$this, 'include_admin_scripts' ) );
		
		//Include frontend scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'include_frontend_scripts' ), 100 );
		
		//Add shortcode
		add_shortcode ( 'wp-booklet', array( &$this, 'process_shortcode') );
		
		//Add messages
		add_filter( 'post_updated_messages', array( &$this, 'modify_messages' ) );
		
		//Add shortcode column to booklet admin
		add_filter( 'manage_posts_columns', array( &$this, 'manage_booklet_columns' ) );
		add_filter( 'manage_posts_custom_column', array( &$this, 'manage_booklet_custom_columns' ), 10, 2);
		
		//Process PDF
        add_action('wp_ajax_process_pdf', array(&$this,'process_pdf') );
		
		//Verify PDF
        add_action('wp_ajax_verify_pdf', array(&$this,'verify_pdf') );
		
	}
	
	function booklet_settings_page() {
		add_option('wp-booklet-pdf-limit-status',"on");
		add_submenu_page( 'edit.php?post_type=wp-booklet', 'Settings', 'Settings', 'manage_options', 'wp-booklet-settings', array( &$this, 'display_settings_page') ); 
		add_action('admin_init', array( &$this, 'booklet_settings_store') );
	}
	
	function booklet_settings_store() {
		register_setting('wp-booklet-settings', 'wp-booklet-pdf-limit-status');
	}
	
	function display_settings_page() {
		
		//Check Ghostscript status
		$status_win = $this->_run_command("gswin32c -v");
		$status_linux = $this->_run_command("gs -v");
		
		if ( $status_win['error'] && $status_linux['error'] === false ) {
			$gs_status = $status_linux['message'];
		}
		else if ( $status_linux['error'] && $status_win['error'] === false ) {
			$gs_status = $status_win['message'];
		}
		else {
			$gs_status = $status_win['message'];
		}
		
		//Check Imagemagick status
		$status = $this->_run_command("convert -version");
		$im_status = $status['message'];
		
		//Is uploads folder writable by web server?
		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['path'];
		$writable = "No";
		if ( is_writable($upload_path) ) {
			$writable = "Yes";
		}
		
		//Do the actual test
		$actual_test = $this->_can_produce_pdf();
		
		include WP_BOOKLET_DIR . "/includes/admin/settings-page.php";
	}
	
	function verify_pdf() {
		$pdf_id = $_POST['pdf_id'];
		$pdf_path = get_attached_file( $pdf_id );
	
		//Check file extension
		$filetype = wp_check_filetype($pdf_path);
		if ( $filetype['type'] != 'application/pdf' ) {
			$result = array( 'wpb_success'=>false, 'wpb_message' => 'Please select a valid PDF file.' );
		}
		else {
			$result = array( 'wpb_success'=>true );
		}
		echo json_encode($result);
		die();
	}
	
	private function _can_produce_pdf() {
		$wp_upload_dir = wp_upload_dir();
		$upload_path = $wp_upload_dir['path'];
		
		$pdf = WP_BOOKLET_DIR . 'pdf/test.pdf';
		$target = $upload_path . '/wp-booklet-test-' . uniqid() . '.jpg';
		
		$result = $this->_run_command("convert -limit memory 32MiB -limit map 64MiB {$pdf} {$target}");
		
		if ( !$result['error'] ) {
			$file_exists = file_exists( $target );
			@unlink( $target );
			return $file_exists;
		}
		else {
			return false;
		}
	}
	
	function process_pdf() {
		$pdf_id = $_POST['pdf_id'];
		$post_id = $_POST['post_id'];
		$pdf_path = get_attached_file( $pdf_id );
		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['path'];
		$image_group = uniqid();
		$pdf_page_count =  $this->_get_pdf_page_count( $pdf_path );
		$page_count = ( get_option('wp-booklet-pdf-limit-status') == 'on' && $pdf_page_count > 10 ) ? 10 : $this->_get_pdf_page_count( $pdf_path );
		
		//Check that upload directory is writable by server
		if ( $upload_dir['error'] || !is_writable($upload_path) ) {
			echo json_encode( array(
				'wpb_success'=>false,
				'wpb_message'=>$upload_dir['error']
			));
			die;
		}
		
		//Use Imagemagick and Ghostscript to convert PDF pages into jpegs
		$operation = $this->_run_command("convert -density 200 -verbose -limit memory 32MiB -limit map 64MiB {$pdf_path}[0-9] {$upload_path}/{$image_group}.jpg");
		if ( $operation['error'] ) {
			echo json_encode( array(
				'wpb_success'=>false,
				'wpb_message'=>'An unknown error occurred.'
			));
			die;
		}
		
		//Fill this array with new attachments
		$images = array();
		
		//Insert images as new attachments
		for ( $ctr = 0; $ctr < $page_count; $ctr++ ) {
			
			//Prepare attachment
			$filename = $upload_path."/".$image_group."-".$ctr.".jpg";
			$wp_filetype = wp_check_filetype(basename($filename), null );
			$attachment = array(
				'guid' => $upload_dir['url'] . '/' . basename( $filename ), 
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			
			//Insert attachment
			$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
			
			//Create attachment metadata
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			
			$images[] = array('id'=>$attach_id,'src'=>$upload_dir['url'] . '/' . basename( $filename ));
		}
		
		echo json_encode(array(
			'images'=>$images,
			'wpb_success'=>true,
			'wpb_message'=>'PDF processing succeeded.'
		));
		die();
	}
	
	private function _get_pdf_page_count($file) {
		if(!file_exists($file))return null;
		if (!$fp = @fopen($file,"r"))return null;
		$pages=0;
		while(!feof($fp)) {
			$line = fgets($fp,255);
			if (preg_match('/\/Count [0-9]+/', $line, $matches)){
				preg_match('/[0-9]+/',$matches[0], $matches2);
				if ($pages<$matches2[0]) $pages=$matches2[0];
			}
		}
		fclose($fp);
		return (int)$pages;
	}
	
	function manage_booklet_custom_columns( $column, $id ) {
		if ( get_post_type() != 'wp-booklet' ) {
			return;
		}
		
		switch( $column ) {
			case 'shortcode' : 
				echo "[wp-booklet id={$id}]";
				break;
		}
	}
	
	function manage_booklet_columns( $columns ) {
		if ( get_post_type() != 'wp-booklet' ) {
			return $columns;
		}
	
		$columns = array_merge( $columns, array( 'shortcode' => 'Shortcode' ) );
		unset( $columns['date'] );
		
		return $columns;
		
	}
	
	function modify_messages($messages) {
		global $post;
		
		if ( get_post_type() != 'wp-booklet' ) {
			return $messages;
		}
		
		$messages['wp-booklet'] = array(
			"Shortcode is [wp-booklet id={$post->ID}]",
			"Booklet updated. Shortcode is [wp-booklet id={$post->ID}]",
			"Custom field updated.",
			"Custom field deleted.",
			"Booklet updated. Shortcode is [wp-booklet id={$post->ID}]",
			"Shortcode is [wp-booklet id={$post->ID}]",
			"Booklet published. Shortcode is [wp-booklet id={$post->ID}]",
			"Booklet saved. Shortcode is [wp-booklet id={$post->ID}]",
			"Booklet submitted",
			"Booklet scheduled",
			"Booklet draft updated"
		);
		
		return $messages;
	}
	
	function process_shortcode($atts) {
		extract( $atts );
		
		$meta = get_post_custom($id);
		$instance_id = uniqid();
		$pages = maybe_unserialize( $meta['wp_booklet_pages'][0] );
		$properties = maybe_unserialize( $meta['wp_booklet_metas'][0] );
		$pages_properties = maybe_unserialize( $meta['wp_booklet_pages_properties'][0] );
		
		if ( empty( $pages ) ) {
			echo "Booklet is empty or it doesn't exist.";
			return;
		}
		
		ob_start();
		include WP_BOOKLET_DIR . "/includes/booklet/booklet.php";
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	private function _command_exists($cmd) {
		$existence = shell_exec($cmd);
		return !empty($existence);
	}
	
	private function _run_command($cmd) {
		try {
			exec( $cmd, $output, $return_var );
			
			if ( $return_var === 0  ) {
				$result['error'] = false;
				$result['message'] = implode(" ",$output);
			}
			else {
				$result['error'] = true;
				$result['message'] = "Command exited with error code: " . $return_var;
			}
		}
		catch(Exception $e) {
			$result['error'] = true;
			$result['message'] = $e->getMessage();
		}
		
		return $result;
	}
	
	function include_admin_scripts() {
		if ( get_post_type() != 'wp-booklet' ) {
			return;
		}
	
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		
		wp_enqueue_media();
		wp_dequeue_script( 'autosave' );
		
		wp_enqueue_style( 'wpbooklet-admin-css', WP_BOOKLET_URL . 'css/admin/admin.css' );
		
		if ( get_bloginfo("version") >= 3.8 ) {
			wp_enqueue_style( 'wpbooklet-admin-css-gt-3.8', WP_BOOKLET_URL . 'css/admin/admin-gt-3.8.css' );
		}
	}
	
	function include_frontend_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-effects-core' );
		
		wp_enqueue_script( 'jquery-wpbooklet', WP_BOOKLET_URL . 'js/jquery.wpbooklet.js' );
		wp_enqueue_script( 'jquery-wpcolorbox', WP_BOOKLET_URL . 'js/jquery.wpcolorbox.js' );
		wp_enqueue_script( 'jquery-wpbookletcarousel', WP_BOOKLET_URL . 'js/jquery.wpbookletcarousel.js' );
		
		wp_enqueue_style( 'jquery-wpbooklet-css', WP_BOOKLET_URL . 'css/booklet/jquery.wpbooklet.css' );
		wp_enqueue_style( 'jquery-wpcolorbox-css', WP_BOOKLET_URL . 'css/booklet/jquery.wpcolorbox.css' );
		wp_enqueue_style( 'wpbooklet-css', WP_BOOKLET_URL . 'css/booklet/booklet.css' );
		wp_enqueue_style( 'jquery-wpcarousel-css', WP_BOOKLET_URL . 'css/booklet/jquery.wpbookletcarousel.css' );
	}
	
	function save_data($post_id) {
		
		if ( empty( $_POST ) ) {
			return;
		} 
		
		//Save pages
		if ( !empty( $_POST['wp-booklet-attachment'] ) ) {
			foreach ( $_POST['wp-booklet-attachment'] as $key => $attachment ) {
				if ( !empty($attachment) ) {
					$pages[] = sanitize_text_field( $attachment );
					$pages_properties[$key]['wp-booklet-page-link'] = sanitize_text_field( $_POST['wp-booklet-attachment-properties']['wp-booklet-page-link'][$key] );
				}
			}
			delete_post_meta( $post_id, 'wp_booklet_pages' );
			update_post_meta( $post_id, 'wp_booklet_pages', $pages );
			
			delete_post_meta( $post_id, 'wp_booklet_pages_properties' );
			update_post_meta( $post_id, 'wp_booklet_pages_properties', $pages_properties );
		}
		
		//Save properties
		if ( !empty( $_POST['wp-booklet-metas'] ) ) {
			$properties['wp-booklet-width'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-width'] );
			$properties['wp-booklet-height'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-height'] );
			$properties['wp-booklet-speed'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-speed'] );
			$properties['wp-booklet-delay'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-delay'] );
			$properties['wp-booklet-direction'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-direction'] );
			$properties['wp-booklet-arrows'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-arrows'] );
			$properties['wp-booklet-pagenumbers'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-pagenumbers'] );
			$properties['wp-booklet-closed'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-closed'] );
			$properties['wp-booklet-padding'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-padding'] );
			$properties['wp-booklet-thumbnails'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-thumbnails'] );
			$properties['wp-booklet-popup'] = sanitize_text_field( $_POST['wp-booklet-metas']['wp-booklet-popup'] );
			
			delete_post_meta( $post_id, 'wp_booklet_metas' );
			update_post_meta( $post_id, 'wp_booklet_metas', $properties );
		}
	}
	
	function add_meta_boxes() {
		
		if ( get_post_type() != 'wp-booklet' ) {
			return;
		}
		
		//Create pages metabox
		add_meta_box(
			'booklet-pages-metabox',
			'Booklet Pages',
			array( &$this, 'create_pages_metabox' ),
			'wp-booklet',
			'normal',
			'high'
		);
		
		//Create properties metabox
		add_meta_box(
			'booklet-properties-metabox',
			'Booklet Properties',
			array( &$this, 'create_properties_metabox' ),
			'wp-booklet',
			'side',
			'low'
		);
	}
	
	function create_pages_metabox( $post ) {
		
		if ( get_post_type() != 'wp-booklet' ) {
			return;
		}
		
		//Get meta
		$meta = get_post_custom($post->ID);
		$pages = array();
		$pages_properties = array();
		
		if ( isset(  $meta['wp_booklet_pages'] ) ) { 
			$pages = maybe_unserialize( $meta['wp_booklet_pages'][0] );
		}
		
		if ( isset(  $meta['wp_booklet_pages_properties'] ) ) {
			$pages_properties = maybe_unserialize( $meta['wp_booklet_pages_properties'][0] );
		}
	
		//Can produce PDF?
		$pdf_capable = $this->_can_produce_pdf();
		
		include WP_BOOKLET_DIR . "/includes/admin/pages-metabox.php";
	}
	
	function create_properties_metabox( $post ) {
		
		$meta = get_post_custom($post->ID);
		$properties = array(
			'wp-booklet-width'=>600,
			'wp-booklet-height'=>400,
			'wp-booklet-speed'=>1000,
			'wp-booklet-delay'=>0,
			'wp-booklet-direction'=>"LTR",
			'wp-booklet-arrows'=>"false",
			'wp-booklet-pagenumbers'=>"false",
			'wp-booklet-closed'=>"false",
			'wp-booklet-padding'=>10,
			'wp-booklet-thumbnails'=>"false",
			'wp-booklet-popup'=>"false"
		);
		
		if ( isset( $meta['wp_booklet_metas'] ) ) {
			$properties = maybe_unserialize( $meta['wp_booklet_metas'][0] );
		}
		
		include WP_BOOKLET_DIR . "/includes/admin/properties-metabox.php";
	}
	
	function init_booklet() {
	
		$labels = array(
			'name' => _x( 'Booklet', 'post type general name' ),
			'singular_name' => _x( 'Booklet', 'post type singular name' ),
			'add_new' => _x( 'Add New booklet', 'Booklet' ),
			'add_new_item' => __( 'Add New Booklet' ),
			'edit_item' => __( 'Edit booklet' ),
			'new_item' => __( 'New booklet' ),
			'all_items' => __( 'All booklets' ),
			'view_item' => __( 'View booklet' ),
			'search_items' => __( 'Search booklet' ),
			'not_found' => __( 'No booklet found' ),
			'not_found_in_trash' => __( 'No booklet found in Trash' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Booklet' )
		);
		 
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title' )
		);
		register_post_type( 'wp-booklet', $args );
	
	}

} 


