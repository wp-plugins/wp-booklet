<?php
/**
 * Plugin Name: WP Booklet
 * Plugin URI: http://binarystash.blogspot.com/2013/11/wp-booklet.html
 * Description: Allows creation of flip books using the jQuery Booklet plugin
 * Version: 1.0.4
 * Author: BinaryStash
 * Author URI:  binarystash.blogspot.com
 * License: GPLv2 (http://www.gnu.org/licenses/gpl-2.0.html)
 */
 
//Define constants
if(!defined('WP_BOOKLET_URL')){
	define('WP_BOOKLET_URL', plugin_dir_url(__FILE__) );
}

//Check WP version
if ( get_bloginfo("version") < 3.5 ) {
	die("Wordpress must be or above version 3.5");
}

class WP_Booklet {
	
	public function __construct() {
		global $post;
		
		//Create custom post type
		add_action( 'init', array( &$this, 'init_booklet' ) );
		
		//Create booklet metaboxes
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
		
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
		add_filter( 'manage_posts_columns', array( &$this, manage_booklet_columns ) );
		add_filter( 'manage_posts_custom_column', array( &$this, manage_booklet_custom_columns ), 10, 2);
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
		$pages = maybe_unserialize( $meta['wp_booklet_pages'][0] );
		$properties = maybe_unserialize( $meta['wp_booklet_metas'][0] );
		
		if ( empty( $pages ) ) {
			echo "Booklet is empty or it doesn't exist.";
			return;
		}
		
		//TODO: Move to a template file
		?>
			<div>
				<div id="wp-booklet-<?php echo $id ?>">
					<?php foreach ( $pages as $page ) : ?>
						<div class="page">
							<?php $image = wp_get_attachment_image_src( $page, 'large' ) ?>
							<img src="<?php echo $image[0] ?>" alt=""/>
						</div>
					<?php endforeach ?>
				</div>
				<script type="text/javascript">
					jQuery(document).ready( function() {
						jQuery("#wp-booklet-<?php echo $id ?>").booklet({
							width:<?php echo $properties['wp-booklet-width'] ? ( $properties['wp-booklet-width'] * 2 ) + 40 : 600 ?>,
							height:<?php echo $properties['wp-booklet-height'] ? $properties['wp-booklet-height'] + 20 : 400 ?>,
							speed:<?php echo $properties['wp-booklet-speed'] ? $properties['wp-booklet-speed'] : 1000 ?>,
							<?php if ( $properties['wp-booklet-delay'] > 0 ) : ?>
							auto:true,
							delay:<?php echo $properties['wp-booklet-delay'] ?>,
							<?php endif ?>
							direction:'<?php echo $properties['wp-booklet-direction'] ?>',
							arrows:<?php echo $properties['wp-booklet-arrows'] ?>,
							pageNumbers:<?php echo $properties['wp-booklet-pagenumbers']  ?>,
							closed:<?php echo $properties['wp-booklet-closed'] ?>
						});
					});
				</script>
			</div>
			<style type="text/css">
				#wp-booklet-<?php echo $id ?> .page img {
					max-width:100%;
				}
				
				#wp-booklet-<?php echo $id ?>,
				#wp-booklet-<?php echo $id ?> * {
					box-sizing:content-box;
					-moz-box-sizing:content-box;
				}
			</style>
		<?php
	}
	
	function include_admin_scripts() {

		if ( get_post_type() != 'wp-booklet' ) {
			return;
		}
	
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		
		wp_enqueue_media();
		wp_dequeue_script( 'autosave' );
		
		
	}
	
	function include_frontend_scripts() {
	
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		
		wp_enqueue_script( 'jquery-easing', WP_BOOKLET_URL . 'js/jquery.easing.1.3.js' );
		wp_enqueue_script( 'jquery-booklet', WP_BOOKLET_URL . 'js/jquery.booklet.latest.min.js' );
		wp_enqueue_style( 'jquery-booklet-css', WP_BOOKLET_URL . 'css/jquery.booklet.latest.css' );
		
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
				}
			}
			delete_post_meta( $post_id, 'wp_booklet_pages' );
			update_post_meta( $post_id, 'wp_booklet_pages', $pages );
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
		
		//TODO: Move to template file
		$meta = get_post_custom($post->ID);
		$pages = maybe_unserialize( $meta['wp_booklet_pages'][0] );
		?>
		<div class="wp-booklet-sortable">
		<?php if ( $pages ) : ?>
			<?php foreach( $pages as $page ) : ?>
					<div class="wp-booklet-portlet">
						<div class="wp-booklet-portlet-header">
							Page
							<span class="wp-booklet-portlet-header-buttons">
								<span class="wp-booklet-header-visibility"></span>
								<span class="wp-booklet-header-remove"></span>
							</span>
						</div>
						<div class="wp-booklet-portlet-content" style="display:block">
							<?php $image = wp_get_attachment_image_src( $page, $size, $icon ); ?> 
							<img src="<?php echo $image[0] ?>" class="wp-booklet-img"/>
							<input class="wp-booklet-attachment-id" value="<?php echo $page ?>" name="wp-booklet-attachment[]" type="hidden"/>
							<input class="button-secondary wp-booklet-image-upload" type="button" value="Replace image"/>
						</div>
					</div>
			<?php endforeach ?>
		<?php endif ?>
		</div>
		<a class="button wp-booklet-sortable-add-page">Add page</a>
		<script type="text/javascript">
			jQuery(document).ready( function() {
				
				/* Sortable */
				jQuery(".wp-booklet-sortable").sortable();
				
				jQuery("body").on('click','.wp-booklet-sortable .wp-booklet-portlet-header .wp-booklet-header-visibility', function(e){
					jQuery(e.currentTarget).parents(".wp-booklet-portlet").find(".wp-booklet-portlet-content").toggle();
				});
				
				jQuery(".wp-booklet-sortable-add-page").on('click', function(e) {
					var newPage = '<div class="wp-booklet-portlet">' +
									'<div class="wp-booklet-portlet-header">' +
										'Page' +
										'<span class="wp-booklet-portlet-header-buttons">' +
											'<span class="wp-booklet-header-visibility"></span>' +
											'<span class="wp-booklet-header-remove"></span>' +
										'</span>' +
									'</div>' +
									'<div class="wp-booklet-portlet-content" style="display:block">' +
										'<input class="wp-booklet-attachment-id" name="wp-booklet-attachment[]" type="hidden"/>' +
										'<input class="button-secondary wp-booklet-image-upload" type="button" value="Upload image"/>' +
									'</div>' +
								'</div>';
					jQuery(".wp-booklet-sortable").append(newPage);
				});
				
				jQuery("body").on('click','.wp-booklet-sortable .wp-booklet-header-remove', function(e) {
					jQuery(e.currentTarget).parents('.wp-booklet-portlet').remove();
				});
				
				/* WP Media */
				
				var current_page;
				var current_page_frame;
				
				jQuery("body").on("click",".wp-booklet-image-upload",function(e) {
					e.preventDefault();
					
					current_page = jQuery(e.currentTarget).parents(".wp-booklet-portlet-content");
					
					if ( current_page_frame ) {
						current_page_frame.open();
						return;
					};
					
					current_page_frame = wp.media({
						className: 'media-frame cs-frame',
						frame: 'select',
						multiple: false,
						title: 'Select image',
						library: {
							type:'image'
						},
						button: {
							text:'Use image'
						}
					});
					
					current_page_frame.on('select',function() {
						var media_attachment;
						
						media_attachment = current_page_frame.state().get('selection').first().toJSON();
						
						if( current_page.find(".wp-booklet-img").length > 0 ) {
							current_page.find('.wp-booklet-img').attr('src',media_attachment.url);
						}
						else {
							current_page.prepend('<img src='+media_attachment.url+' class="wp-booklet-img"/> ');
						}
						
						current_page.find('.wp-booklet-attachment-id').val(media_attachment.id);
						current_page.find('.wp-booklet-image-upload').val('Replace image');
						
					});
					
					current_page_frame.open();
				});
			});
		</script>
		<style type="text/css">
			.wp-booklet-sortable {
				margin-bottom:25px;
			}
			
			.wp-booklet-sortable .ui-sortable-placeholder { 
				border: 1px dashed #bbbbbb !important; 
				visibility: visible !important;
				background:#f5f5f5 !important;
			}
			.wp-booklet-sortable .wp-booklet-portlet-header {
				background: linear-gradient(to top, #ECECEC, #F9F9F9) repeat scroll 0 0 #F1F1F1;
				padding:5px;
				border-bottom-width: 1px;
				border-bottom-color: #DFDFDF;
				box-shadow: 0 1px 0 #FFFFFF;
				text-shadow: 0 1px 0 #FFFFFF;
				border-top-left-radius: 3px;
				border-top-right-radius: 3px;
				cursor:move;
			}
			
			.wp-booklet-sortable .wp-booklet-portlet {
				border:1px solid #DFDFDF;
				margin-bottom:5px;
				background:linear-gradient(to top, #F5F5F5, #F9F9F9) repeat scroll 0 0 #F5F5F5;
			}
			
			.wp-booklet-sortable .wp-booklet-portlet-content {
				display:none;
				padding:10px;
			}
			
			.wp-booklet-sortable .wp-booklet-portlet-header-buttons {
				display:block;
				float:right;
			}
			
			.wp-booklet-sortable .wp-booklet-img {
				width:250px;
				display:block;
				margin-bottom:10px;
			}
			
			.wp-booklet-sortable .wp-booklet-header-remove {
				display:inline-block;
				width:15px;
				height:15px;
				background:url("../wp-includes/images/uploader-icons.png") -100px 0 no-repeat;
				cursor:pointer;
			}
			
			.wp-booklet-sortable .wp-booklet-header-visibility {
				display:inline-block;
				width:17px;
				height:15px;
				margin-right:10px;
				cursor:pointer;
				background:url("../wp-includes/images/down_arrow-2x.gif") 0 -13px no-repeat;
			}
			
			#minor-publishing-actions,
			#misc-publishing-actions {
				display:none;
			}
		</style>
		<?php
		
	}
	
	function create_properties_metabox( $post ) {
		
		//TODO: Move to a template file
		
		$meta = get_post_custom($post->ID);
		$properties = maybe_unserialize( $meta['wp_booklet_metas'][0] );
		?>
		<p>
			<label>Page width</label><br/>
			<input size="18" type="text" name="wp-booklet-metas[wp-booklet-width]" value="<?php echo $properties['wp-booklet-width'] ? $properties['wp-booklet-width'] : 600  ?>"/> pixels
		</p>
		<p>
			<label>Page height</label><br/>
			<input size="18" type="text" name="wp-booklet-metas[wp-booklet-height]" value="<?php echo $properties['wp-booklet-height'] ? $properties['wp-booklet-height'] : 400 ?>"/> pixels
		</p>
		<p>
			<label>Flip speed</label><br/>
			<input size="18" type="text" name="wp-booklet-metas[wp-booklet-speed]" value="<?php echo $properties['wp-booklet-speed'] ? $properties['wp-booklet-speed'] : 1000 ?>"/> milliseconds 
		</p>
		<p>
			<label>Automatic flip delay</label><br/>
			<input size="18" type="text" name="wp-booklet-metas[wp-booklet-delay]" value="<?php echo $properties['wp-booklet-delay'] ? $properties['wp-booklet-delay'] : 0 ?>"/> milliseconds<br/>
			<span style="font-size:10px;font-style:italic">Set to 0 for manual flipping.</span>
		</p>
		<p>
			<label>Page direction</label>
			<select class="widefat" name="wp-booklet-metas[wp-booklet-direction]">
				<option selected="selected" value="LTR">LTR</option>
				<option value="RTL">RTL</option>
			</select>
		</p>
		<p>
			<label>Show navigation arrows?</label>
			<select class="widefat" name="wp-booklet-metas[wp-booklet-arrows]">
				<option <?php if ( $properties["wp-booklet-arrows"] == "true" ) { echo "selected='selected'"; } ?> value="true">Yes</option>
				<option <?php if ( $properties["wp-booklet-arrows"] == "false" || !$properties["wp-booklet-arrows"] ) { echo  "selected='selected'"; } ?> value="false">No</option>
			</select>
		</p>
		<p>
			<label>Show page numbers?</label>
			<select class="widefat" name="wp-booklet-metas[wp-booklet-pagenumbers]">
				<option <?php if ( $properties["wp-booklet-pagenumbers"] == "true" || !$properties["wp-booklet-arrows"] ) { echo "selected='selected'"; } ?> value="true">Yes</option>
				<option <?php if ( $properties["wp-booklet-pagenumbers"] == "false" ) { echo "selected='selected'"; } ?> value="false">No</option>
			</select>
		</p>
		<p>
			<label>Start the booklet closed?</label>
			<select class="widefat" name="wp-booklet-metas[wp-booklet-closed]">
				<option <?php if ( $properties["wp-booklet-closed"] == "true" ) { echo "selected='selected'"; } ?> value="true">Yes</option>
				<option <?php if ( $properties["wp-booklet-closed"] == "false" || !$properties["wp-booklet-closed"] ) { echo  "selected='selected'"; } ?> value="false">No</option>
			</select>
		</p>
		<?php
	
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
 
function WP_Booklet_Instantiate() {
	new WP_Booklet();
}
add_action( 'plugins_loaded', 'WP_Booklet_Instantiate', 15 );