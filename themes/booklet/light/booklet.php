<div class="wp-booklet-container-light wp-booklet-<?php echo $instance_id ?>-container">
	<div class="wp-booklet-light wp-booklet-<?php echo $instance_id ?>">
		<?php foreach ( $pages as $key=>$page ) : ?>
			<div class="page" data-page="<?php echo $key ?>">
				
				<?php $page_link = $page->get_page_link(); ?>
					
				<?php if ( $booklet->are_popups_enabled() ) : ?>
				
					<a class="wp-booklet-popup-trigger" <?php if ( !empty( $page_link ) ): ?>data-link="<?php echo $page_link ?>"<?php endif ?> href="<?php echo $page->get_image_url("large") ?>" >
						<img src="<?php echo $page->get_image_url("large") ?>" alt=""/>
					</a>
				
				<?php else: ?>
					
					<?php if ( !empty($page_link) ) : ?>
						<a href="<?php echo $page_link ?>" >
							<img src="<?php echo $page->get_image_url("large") ?>" alt=""/>
						</a>
					<?php else : ?>	
						<img src="<?php echo $page->get_image_url("large") ?>" alt=""/>
					<?php endif ?>	
				
				<?php endif ?>
				
			
			</div>
		<?php endforeach ?>
	</div>
	<?php if ( $booklet->are_thumbnails_enabled() ) : ?>
		<div class="wp-booklet-thumbs-light wp-booklet-<?php echo $instance_id ?>-thumbs" dir="<?php echo strtolower( $booklet->get_direction() ) ?>">
			<div class="wp-booklet-carousel" data-wpbookletcarousel="true">
				<ul>
					<?php foreach ( $pages as $key=>$page ) : ?>
						<li data-page="<?php echo $key ?>">
							<a>
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
		var bookletDirection = '<?php echo $booklet->get_direction() ?>';
		
		var pageWidth = <?php echo $booklet->get_width() ?>;
		var pageHeight = <?php echo $booklet->get_height() ?>; 
		var pagePadding = <?php echo $booklet->get_padding()  ?>;
		var bookletWidth = <?php echo $booklet->get_width() ?> + (4 * pagePadding);
		var bookletHeight = <?php echo $booklet->get_height() ?> + (2 * pagePadding);
		var speed = <?php echo $booklet->get_speed() ?>; 
		var delay = <?php echo $booklet->get_delay() ?>;
		var bookletSide = 45;
		var thumbsSide = 11;
		var bookletContainerWidth =  bookletWidth + bookletSide * 2;
		var bookletThumbsContainerWidth = bookletWidth - 22 ;
		
		var popupsEnabled = <?php echo $booklet->are_popups_enabled() ? "true" : "false" ?>;
		var thumbnailsEnabled = <?php echo $booklet->are_thumbnails_enabled() ? "true" : "false" ?>;
		var arrowsEnabled = <?php echo $booklet->are_arrows_enabled() ? "true" : "false" ?>;
		var pageNumbersEnabled = <?php echo $booklet->are_page_numbers_enabled() ? "true" : "false"  ?>;
		
		var coverBehavior = "<?php echo $booklet->get_cover_behavior() ?>";
		
		
		/*----- Set up booklet -----*/
		
		var bookletSettings = {
			width:bookletWidth,
			height:bookletHeight,
			speed:speed,
			create: function(event, data) {
				
				if ( thumbnailsEnabled ) {
					bookletThumbsContainer.find(' .wp-booklet-carousel li:eq(0) a').addClass('selected');
				}
				
			},
			direction:bookletDirection,
			arrows: arrowsEnabled,
			pageNumbers: pageNumbersEnabled,
			start: function(event, data) { 
				
				if ( thumbnailsEnabled ) {

					var index = data.index;
					
					if ( coverBehavior != 'open' ) {
						index--;
					}
					
					if ( index < 0 ) {
						index = 0;
					}
					
					var carouselPage = booklet.find(".page").eq(index).attr("data-page");
					
					bookletThumbsContainer.find(' .wp-booklet-carousel li a').removeClass('selected');
					bookletThumbsContainer.find(' .wp-booklet-carousel li:eq('+carouselPage+') a').addClass('selected');
					bookletThumbsContainer.find(" .wp-booklet-carousel").wpbookletcarousel('scroll', carouselPage);
					
				}
				
			},
			pagePadding:pagePadding
		};
		
		if ( delay > 0 ) {
			bookletSettings.auto = true;
			bookletSettings.delay =  delay;
		}
		
		if ( coverBehavior == 'center-closed' ) {
			bookletSettings.autoCenter = true;
		}
		
		if ( coverBehavior != 'open' ) {
			bookletSettings.closed = true;
		}
		else {
			bookletSettings.closed = ( ( coverBehavior == 'center-closed' || coverBehavior == 'closed' ) ? true : false );
		}
		
		booklet.wpbooklet(bookletSettings);
		
		/*----- Set up carousel -----*/
		
		if ( thumbnailsEnabled ) {
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
				
				if ( coverBehavior == 'open' ) {
					var index = jQuery(e.currentTarget).parent().parent().find("li").index( jQuery(e.currentTarget).parent() );
					if ( index == 0 ) { index = 'start' }
				}
				else {
					var index = jQuery(e.currentTarget).parent().parent().find("li").index( jQuery(e.currentTarget).parent() ) + 1;
					if ( index == 1 ) { index = 'start' }
				}
				booklet.wpbooklet('gotopage',index);
			});

			
		}
		
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
		
		/*----- Initialize popups -----*/
		
		if ( popupsEnabled ) {
			
			booklet.find(".wp-booklet-popup-trigger").wpbookletImagePopup({
				overlayClass:'wpbooklet-image-popup-overlay-light',
				beforeOpen: function(popup, link) {
					
					booklet.wpbooklet( "option", "auto", false );
					
					if ( typeof link.attr("data-link") !== "undefined" ) {
					
						popup.find("img").wrap("<a>");
						popup.find("a").attr("href", link.attr("data-link"));
					
					}					
						
				},
				afterClose: function(popup, link) {
					
					if ( delay ) {
						booklet.wpbooklet( "option", "auto", true );
					}
					else {
						booklet.wpbooklet( "option", "auto", false );
					}
					
				}
			});
			
		}
		
	});
</script>
