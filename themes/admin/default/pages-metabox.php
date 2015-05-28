<div class="wp-booklet-sortable">
	<?php if ( $pages ) : ?>
		<?php foreach( $pages as $key => $page ) : ?>
				<div class="wp-booklet-portlet">
					<div class="wp-booklet-portlet-header">
						Page
						<span class="wp-booklet-portlet-header-buttons">
							<span class="wp-booklet-header-visibility"></span>
							<span class="wp-booklet-header-remove"></span>
						</span>
					</div>
					<div class="wp-booklet-portlet-content">
						<div class="wp-booklet-portlet-content-left">
							<img src="<?php echo $page->get_image_url("medium") ?>" class="wp-booklet-img"/>
							<input class="wp-booklet-attachment-id" value="<?php echo $page->get_id() ?>" name="wp-booklet-attachment[]" type="hidden"/>
						</div>
						<div class="wp-booklet-portlet-content-right">
							<p>
								<label>Page Link</label><br/>
								<input class="widefat" type="text" value="<?php echo $page->get_page_link() ?>" name="wp-booklet-attachment-properties[wp-booklet-page-link][]"/>
							</p>
						</div>
						<div class="clearfix"></div>
						<input class="button-secondary wp-booklet-image-upload" type="button" value="Replace image"/>
					</div>
				</div>
		<?php endforeach ?>
	<?php endif ?>
</div>
<a class="button wp-booklet-sortable-add-pages">Add pages</a>
<?php if ( $pdf_capable ) : ?>
<a class="button wp-booklet-sortable-upload-pdf">Upload PDF</a>
<?php endif ?>
<div class="wp-booklet-preloader-overlay"><div class="wp-booklet-preloader-note"></div></div>	
<script type="text/javascript">
	jQuery(document).ready( function() {
		var newPage = '<div class="wp-booklet-portlet">' +
							'<div class="wp-booklet-portlet-header">' +
								'Page' +
								'<span class="wp-booklet-portlet-header-buttons">' +
									'<span class="wp-booklet-header-visibility"></span>' +
									'<span class="wp-booklet-header-remove"></span>' +
								'</span>' +
							'</div>' +
							'<div class="wp-booklet-portlet-content">' +
								'<div class="wp-booklet-portlet-content-left">' +
									'<div class="wp-booklet-page-placeholder"></div>' +
									'<input class="wp-booklet-attachment-id" name="wp-booklet-attachment[]" type="hidden"/>' +
									'<input class="button-secondary wp-booklet-image-upload" type="button" value="Upload image"/>' +
								'</div>' +
								'<div class="wp-booklet-portlet-content-right">' +
									'<p>' +
										'<label>Page Link</label><br/>' +
										'<input class="widefat" type="text" value="" name="wp-booklet-attachment-properties[wp-booklet-page-link][]"/>' +
									'</p>' +
								'</div>' +
								'<div class="clearfix"></div>' +
							'</div>' +
						'</div>';
		
		/* Sortable */
		jQuery(".wp-booklet-sortable").sortable();
		
		jQuery("body").on('click','.wp-booklet-sortable .wp-booklet-portlet-header', function(e){
			jQuery(e.currentTarget).parent().toggleClass("wp-booklet-portlet-hidden");
		});
		
		jQuery("body").on('click','.wp-booklet-sortable .wp-booklet-header-remove', function(e) {
			jQuery(e.currentTarget).parents('.wp-booklet-portlet').remove();
		});
		
		/* Add pages */
		
		var pages_uploader = wp.media({
			'title':'Choose images',
			'button':{
				'text':'Choose images'
			},
			'library':{
				'type':'image'
			},
			'multiple':true
		});
		
		pages_uploader.on('select', function() {
			attachments = pages_uploader.state().get('selection').toJSON();
			for( var ctr = 0; ctr < attachments.length; ctr++ ) {
				jQuery(".wp-booklet-sortable").append(newPage);
				var currPage = jQuery(".wp-booklet-sortable .wp-booklet-portlet").last();
				currPage.find('.wp-booklet-portlet-content-left').prepend('<img src='+attachments[ctr].url+' class="wp-booklet-img"/> ');
				currPage.find('.wp-booklet-attachment-id').val(attachments[ctr].id);
				currPage.find('.wp-booklet-image-upload').val('Replace image');
				currPage.find('.wp-booklet-page-placeholder').remove();
			}
		});
		
		jQuery(".wp-booklet-sortable-add-pages").on('click', function(e) {
			e.preventDefault();
			
			if ( pages_uploader ) {
				pages_uploader.open();
				return;
			}
			
			pages_uploader.open();
		});
		
		/* PDF upload */
		
		var pdf_uploader = wp.media({
			'title':'Choose PDF',
			'button': {
				text:'Choose PDF'
			},
			multiple:false
		});
		
		pdf_uploader.on('select', function() {
			attachment = pdf_uploader.state().get('selection').first().toJSON();
			processPDF(attachment);
		});
		
		jQuery(".wp-booklet-sortable-upload-pdf").on("click",function(e) {
			e.preventDefault();
			
			<?php if( get_option('wp-booklet2-pdf-limit-status') == 'on' ) : ?>
			if ( !confirm("This feature only adds the first 10 pages of the PDF file. Continue?") ) { return; }
			<?php endif ?>
			
			if ( pdf_uploader ) {
				pdf_uploader.open();
				return;
			}
			
			pdf_uploader.open();
			
		});	
		
		function showPreloader(text) {
			jQuery(".wp-booklet-preloader-overlay .wp-booklet-preloader-note").html(text);
			jQuery(".wp-booklet-preloader-overlay").show().animate({opacity:0.8},200);
		}
		function hidePreloader() {
			jQuery(".wp-booklet-preloader-overlay").animate({opacity:0},200).hide();
		}
		
		function processPDF(attachment) {
			
			showPreloader('Validating PDF. Please keep this window open');
			
			var data = {
				'pdf_id':attachment.id,
				'action':'verify_pdf'
			}
			
			jQuery.post(ajaxurl,data,function(verification) {
				if ( verification.wpb_success ) {
					hidePreloader();
					showPreloader('Processing PDF. Please keep this window open.');
					var data = {
						'pdf_id':attachment.id,
						'post_id':<?php echo $post->ID ?>,
						'action':'process_pdf'
					}
					jQuery.post(ajaxurl,data,function(response) {
						if ( response.wpb_success ) {
							for( var ctr = 0; ctr < response.images.length; ctr++ ) {
								jQuery(".wp-booklet-sortable").append(newPage);
								var currPage = jQuery(".wp-booklet-sortable .wp-booklet-portlet").last();
								currPage.find('.wp-booklet-portlet-content-left').prepend('<img src='+response.images[ctr].src+' class="wp-booklet-img"/> ');
								currPage.find('.wp-booklet-attachment-id').val(response.images[ctr].id);
								currPage.find('.wp-booklet-image-upload').val('Replace image');
								currPage.find('.wp-booklet-page-placeholder').remove();
							}
						}
						else {
							alert(response.wpb_message);
						}
						hidePreloader();
					},'json');
				}
				else {
					hidePreloader();
					alert(verification.wpb_message);
				}
			},'json');
		}
		
		/* Replace image */
		
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
					current_page.find('.wp-booklet-portlet-content-left').prepend('<img src='+media_attachment.url+' class="wp-booklet-img"/> ');
				}
				
				current_page.find('.wp-booklet-attachment-id').val(media_attachment.id);
				current_page.find('.wp-booklet-image-upload').val('Replace image');
				current_page.find('.wp-booklet-page-placeholder').remove();
			});
			
			current_page_frame.open();
		});
	});
</script>
