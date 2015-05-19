<div class="wp-booklet-container-default wp-booklet-<?php echo $instance_id ?>-container">
	<div class="wp-booklet-default wp-booklet-<?php echo $instance_id ?>">
		<?php foreach ( $pages as $key=>$page ) : ?>
			<div class="page">
				<?php 
					$image = wp_get_attachment_image_src( $page, 'large' );
				?>
				<?php if ( $properties["wp-booklet-popup"] == "true" ) : ?>
					<div class="wp-booklet-popup-trigger">
						<a data-link="<?php echo $pages_properties[$key]['wp-booklet-page-link'] ?>" rel="wp-booklet-popup-<?php echo $instance_id ?>" href="<?php echo $image[0] ?>">
							<img src="<?php echo $image[0] ?>" alt=""/>
						</a>
					</div>
				<?php elseif ( !empty($pages_properties[$key]['wp-booklet-page-link'] ) ) : ?>
					<a href="<?php echo $pages_properties[$key]['wp-booklet-page-link'] ?>" >
						<img src="<?php echo $image[0] ?>" alt=""/>
					</a>
				<?php else : ?>	
					<img src="<?php echo $image[0] ?>" alt=""/>
				<?php endif ?>	
			</div>
		<?php endforeach ?>
	</div>
	<?php if ( $properties['wp-booklet-thumbnails'] == 'true' ) : ?>
		<div class="wp-booklet-thumbs-default wp-booklet-<?php echo $instance_id ?>-thumbs">
			<div class="wp-booklet-carousel" data-wpbookletcarousel="true">
				<ul>
					<?php foreach ( $pages as $key=>$page ) : ?>
						<?php $image = wp_get_attachment_image_src( $page, 'medium' ); ?>
						<li>
							<a data-photo="<?php echo $key ?>">
								<img src="<?php echo $image[0] ?>" alt=""/>
							</a>
						</li>
					<?php endforeach ?>
				</ul>
			</div>
			<a class="wp-booklet-carousel-prev inactive" href="#" data-wpbookletcarouselcontrol="true">&lsaquo;</a>
			<a class="wp-booklet-carousel-next" href="#" data-wpbookletcarouselcontrol="true">&rsaquo;</a>
		</div>
	<?php endif ?>
</div>


<script type="text/javascript">
	jQuery(document).ready( function() {
		
		/*----- Set variables -----*/
		
		var booklet = jQuery(".wp-booklet-<?php echo $instance_id ?>");
		var bookletContainer = jQuery(".wp-booklet-<?php echo $instance_id ?>-container");
		var bookletThumbsContainer = jQuery(".wp-booklet-<?php echo $instance_id ?>-thumbs");
		var bookletExpanded = jQuery(".wp-booklet-<?php echo $instance_id ?>-expanded");
		
		var pageWidth = <?php echo $properties['wp-booklet-width'] ?>;
		var pageHeight = <?php echo $properties['wp-booklet-height'] ?>; 
		var pagePadding = <?php echo $properties['wp-booklet-padding'] > -1 ? $properties['wp-booklet-padding'] : 10  ?>;
		var bookletWidth = <?php echo $properties['wp-booklet-width'] ? ( $properties['wp-booklet-width'] * 2 ) : 560?> + (4 * pagePadding);
		var bookletHeight = <?php echo $properties['wp-booklet-height'] ? $properties['wp-booklet-height'] : 380 ?> + (2 * pagePadding);
		var bookletSide = 74;
		var thumbsSide = 11;
		var bookletContainerWidth =  bookletWidth + bookletSide * 2;
		var bookletThumbsContainerWidth = bookletWidth - 22 ;
		
		/*----- Set up booklet -----*/
		
		booklet.wpbooklet({
			width:bookletWidth,
			height:bookletHeight,
			speed:<?php echo $properties['wp-booklet-speed'] ? $properties['wp-booklet-speed'] : 1000 ?>,
			<?php if ( $properties['wp-booklet-delay'] > 0 ) : ?>
			auto:true,
			delay:<?php echo $properties['wp-booklet-delay'] ?>,
			<?php endif ?>
			create: function(event, data) {
				<?php if ( $properties['wp-booklet-thumbnails'] == 'true' ) : ?>
					bookletThumbsContainer.find(' .wp-booklet-carousel li:eq(0) a').addClass('selected');
				<?php endif  ?>
			},
			direction:'<?php echo $properties['wp-booklet-direction'] ?>',
			arrows:<?php echo $properties['wp-booklet-arrows'] ?>,
			pageNumbers:<?php echo $properties['wp-booklet-pagenumbers']  ?>,
			start: function(event, data) { 
				<?php if ( $properties['wp-booklet-thumbnails'] == 'true' ) : ?>
					
					<?php if ( $properties['wp-booklet-closed'] != "false" ) : ?>
						data.index--;
					<?php endif ?>
					
					bookletThumbsContainer.find(' .wp-booklet-carousel li a').removeClass('selected');
					bookletThumbsContainer.find(' .wp-booklet-carousel li:eq('+data.index+') a').addClass('selected');
					bookletThumbsContainer.find(" .wp-booklet-carousel").wpbookletcarousel('scroll', data.index);
				<?php endif ?>
			},
			pagePadding:pagePadding,
			<?php if ( $properties['wp-booklet-closed'] == 'closable-centered' ) : ?>
				autoCenter:true,
				closed:true
			<?php else: ?>
				closed:<?php echo $properties['wp-booklet-closed'] ?>
			<?php endif ?>
		});
		
		/*----- Set up carousel -----*/
		
		<?php if ( $properties['wp-booklet-thumbnails'] == 'true' ) : ?>
			bookletThumbsContainer.find('.wp-booklet-carousel').wpbookletcarousel();
			bookletThumbsContainer.find('.wp-booklet-carousel-prev').wpbookletcarouselControl({ target: '-=1' });
			bookletThumbsContainer.find('.wp-booklet-carousel-next').wpbookletcarouselControl({ target: '+=1' }); 
			jQuery('.wp-booklet-carousel-next, .wp-booklet-carousel-prev').on('wpbookletcarouselcontrol:active', function(e) {
                jQuery(e.currentTarget).removeClass('inactive');
            });
            jQuery('.wp-booklet-carousel-next, .wp-booklet-carousel-prev').on('wpbookletcarouselcontrol:inactive', function(e) {
                jQuery(e.currentTarget).addClass('inactive');
            });
			bookletThumbsContainer.find('.wp-booklet-carousel a').on('click', function(e) {
				bookletThumbsContainer.find(' .wp-booklet-carousel li a').removeClass('selected');
				jQuery(e.currentTarget).addClass('selected');
				
				<?php if ( $properties['wp-booklet-closed'] == "false" ) : ?>
					var index = jQuery(e.currentTarget).parent().parent().find("li").index( jQuery(e.currentTarget).parent() );
					if ( index == 0 ) { index = 'start' }
				<?php else: ?>
					var index = jQuery(e.currentTarget).parent().parent().find("li").index( jQuery(e.currentTarget).parent() ) + 1;
					if ( index == 1 ) { index = 'start' }
				<?php endif ?>
				booklet.wpbooklet('gotopage',index);
			});

			
		<?php endif ?>
		
		/*----- Make booklet responsive -----*/
		
		resizeBooklet();
		jQuery(window).resize(resizeBooklet);
		function resizeBooklet() {
			
			jQuery(booklet).each( function(i,v) {
				var allowedWidth = jQuery(v).parent().parent().width();
				
				jQuery(v).wpbooklet("option","width",allowedWidth - bookletSide * 2);
				jQuery(v).wpbooklet("option","height",((allowedWidth - bookletSide * 2) / 2) * pageHeight / pageWidth);
				jQuery(v).parent().find(".wp-booklet-thumbs-default").width( allowedWidth - bookletSide * 2 );
			});
		}
		
		/*----- Set up popups -----*/
		
		booklet.find(".wp-booklet-popup-trigger a").wpcolorbox({
			rel:"wp-booklet-popup-<?php echo $instance_id ?>",
			maxWidth:"100%",
			width:"100%",
			scalePhotos:true
		});
		
		jQuery(document).bind('wpcbox_complete', function(data){
			var wpcboxIndex = booklet.find(".wpcboxElement").index( jQuery.wpcolorbox.element() );
			<?php if ( $properties['wp-booklet-closed'] == "false" ) : ?>
				if ( wpcboxIndex == 0 ) { wpcboxIndex = 'start' }
			<?php else: ?>
				if ( wpcboxIndex == 1 ) { wpcboxIndex = 'start' }
			<?php endif ?>
			booklet.wpbooklet('gotopage',wpcboxIndex);
		});
		
	});
</script>
