<div class="wp-booklet-container-light wp-booklet-<?php echo $instance_id ?>-container">
	<div class="wp-booklet-light wp-booklet-<?php echo $instance_id ?>">
		<?php foreach ( $pages as $key=>$page ) : ?>
			<div class="page">
				<?php $page_link = $page->get_page_link(); ?>
				<?php if ( !empty($page_link) ) : ?>
					<a href="<?php echo $page->get_page_link() ?>" >
						<img src="<?php echo $page->get_image_url("large") ?>" alt=""/>
					</a>
				<?php else : ?>	
					<img src="<?php echo $page->get_image_url("large") ?>" alt=""/>
				<?php endif ?>	
			</div>
		<?php endforeach ?>
	</div>
	<?php if ( $booklet->are_thumbnails_enabled() ) : ?>
		<div class="wp-booklet-thumbs-light wp-booklet-<?php echo $instance_id ?>-thumbs">
			<div class="wp-booklet-carousel" data-wpbookletcarousel="true">
				<ul>
					<?php foreach ( $pages as $key=>$page ) : ?>
						<li>
							<a data-photo="<?php echo $key ?>">
								<img src="<?php echo $page->get_image_url("medium") ?>" alt=""/>
							</a>
						</li>
					<?php endforeach ?>
				</ul>
			</div>
			<a class="wp-booklet-carousel-prev inactive" href="#" data-wpbookletcarouselcontrol="true"></a>
			<a class="wp-booklet-carousel-next" href="#" data-wpbookletcarouselcontrol="true"></a>
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
		
		var pageWidth = <?php echo $booklet->get_width() ?>;
		var pageHeight = <?php echo $booklet->get_height() ?>; 
		var pagePadding = <?php echo $booklet->get_padding()  ?>;
		var bookletWidth = <?php echo $booklet->get_width() ?> + (4 * pagePadding);
		var bookletHeight = <?php echo $booklet->get_height() ?> + (2 * pagePadding);
		var bookletSide = 45;
		var thumbsSide = 11;
		var bookletContainerWidth =  bookletWidth + bookletSide * 2;
		var bookletThumbsContainerWidth = bookletWidth - 22 ;
		
		/*----- Set up booklet -----*/
		
		booklet.wpbooklet({
			width:bookletWidth,
			height:bookletHeight,
			speed:<?php echo $booklet->get_speed() ?>,
			<?php if ( $booklet->get_delay() > 0 ) : ?>
			auto:true,
			delay:<?php echo $booklet->get_delay() ?>,
			<?php endif ?>
			create: function(event, data) {
				<?php if ( $booklet->are_thumbnails_enabled() ) : ?>
					bookletThumbsContainer.find(' .wp-booklet-carousel li:eq(0) a').addClass('selected');
				<?php endif  ?>
			},
			direction:'<?php echo $booklet->get_direction() ?>',
			arrows:<?php echo $booklet->are_arrows_enabled() ? "true" : "false" ?>,
			pageNumbers:<?php echo $booklet->are_page_numbers_enabled() ? "true" : "false"  ?>,
			start: function(event, data) { 
				<?php if ( $booklet->are_thumbnails_enabled() ) : ?>
					
					<?php if ( $booklet->get_cover_behavior() != "false" ) : ?>
						data.index--;
					<?php endif ?>
					
					bookletThumbsContainer.find(' .wp-booklet-carousel li a').removeClass('selected');
					bookletThumbsContainer.find(' .wp-booklet-carousel li:eq('+data.index+') a').addClass('selected');
					bookletThumbsContainer.find(" .wp-booklet-carousel").wpbookletcarousel('scroll', data.index);
				<?php endif ?>
			},
			pagePadding:pagePadding,
			<?php if ( $booklet->get_cover_behavior() == 'center-closed' ) : ?>
				autoCenter:true,
				closed:true
			<?php else: ?>
				closed:<?php echo ( $booklet->get_cover_behavior() == 'center-closed' || $booklet->get_cover_behavior() == 'closed' ) ? "true" : "false" ?>
			<?php endif ?>
		});
		
		/*----- Set up carousel -----*/
		
		<?php if ( $booklet->are_thumbnails_enabled() ) : ?>
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
				
				<?php if ( $booklet->get_cover_behavior() == 'open' ) : ?>
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
				jQuery(v).parent().find(".wp-booklet-thumbs-light").width( allowedWidth - bookletSide * 2 );
			});
		}
		
	});
</script>
