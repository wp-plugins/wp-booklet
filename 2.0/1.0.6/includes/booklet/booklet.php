<div id="wp-booklet-<?php echo $id ?>-container" class="wp-booklet-container-default">
<div id="wp-booklet-<?php echo $id ?>" class="wp-booklet-default">
	<?php foreach ( $pages as $key=>$page ) : ?>
		<div class="page">
			<?php 
				$image = wp_get_attachment_image_src( $page, 'large' );
				$link = $pages_properties[$key]['wp-booklet-page-link'];
			?>
			<?php if ( $link != "" ) : ?>
			<a href="<?php echo $pages_properties[$key]['wp-booklet-page-link'] ?>">
			<?php endif ?>
				<img src="<?php echo $image[0] ?>" alt=""/>
			<?php if ( $link != "" ) : ?>
			</a>
			<?php endif ?>
		</div>
	<?php endforeach ?>
</div>
<?php if ( $properties['wp-booklet-thumbnails'] == 'true' ) : ?>
	<div id="wp-booklet-<?php echo $id ?>-thumbs" class="wp-booklet-thumbs-default">
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
		<a class="wp-booklet-carousel-prev" href="#" data-wpbookletcarouselcontrol="true">&lsaquo;</a>
		<a class="wp-booklet-carousel-next" href="#" data-wpbookletcarouselcontrol="true">&rsaquo;</a>
	</div>
<?php endif ?>
<script type="text/javascript">
	jQuery(document).ready( function() {
		
		var booklet = "#wp-booklet-<?php echo $id ?>";
		var bookletThumbs = "#wp-booklet-<?php echo $id ?>-thumbs";
		
		var pagePadding = <?php echo $properties['wp-booklet-padding'] ? $properties['wp-booklet-padding'] : 10  ?>;
		var bookletWidth = <?php echo $properties['wp-booklet-width'] ? ( $properties['wp-booklet-width'] * 2 ) : 560?> + (4 * pagePadding);
		var bookletHeight = <?php echo $properties['wp-booklet-height'] ? $properties['wp-booklet-height'] : 380 ?> + (2 * pagePadding);
	
		jQuery(booklet).wpbooklet({
			width:bookletWidth,
			height:bookletHeight,
			speed:<?php echo $properties['wp-booklet-speed'] ? $properties['wp-booklet-speed'] : 1000 ?>,
			<?php if ( $properties['wp-booklet-delay'] > 0 ) : ?>
			auto:true,
			delay:<?php echo $properties['wp-booklet-delay'] ?>,
			<?php endif ?>
			create: function(event, data) {
				<?php if ( $properties['wp-booklet-thumbnails'] == 'true' ) : ?>
					jQuery(bookletThumbs+' .wp-booklet-carousel li:eq(0) a').addClass('selected');
				<?php endif  ?>
			},
			direction:'<?php echo $properties['wp-booklet-direction'] ?>',
			arrows:<?php echo $properties['wp-booklet-arrows'] ?>,
			pageNumbers:<?php echo $properties['wp-booklet-pagenumbers']  ?>,
			change: function(event, data) { 
				<?php if ( $properties['wp-booklet-thumbnails'] == 'true' ) : ?>
					<?php if ( $properties['wp-booklet-closed'] != "false" ) : ?>
						data.index--;
					<?php endif ?>
					jQuery(bookletThumbs+' .wp-booklet-carousel li a').removeClass('selected');
					jQuery(bookletThumbs+' .wp-booklet-carousel li:eq('+data.index+') a').addClass('selected');
					jQuery(bookletThumbs+" .wp-booklet-carousel").wpbookletcarousel('scroll', data.index);
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
		
		jQuery("#wp-booklet-<?php echo $id ?>-container").width( bookletWidth );
		
		<?php if ( $properties['wp-booklet-thumbnails'] == 'true' ) : ?>
			jQuery(bookletThumbs+' .wp-booklet-carousel').wpbookletcarousel();
			jQuery(bookletThumbs+' .wp-booklet-carousel-prev').wpbookletcarouselControl({ target: '-=1' });
			jQuery(bookletThumbs+' .wp-booklet-carousel-next').wpbookletcarouselControl({ target: '+=1' }); 
			jQuery(bookletThumbs+' .wp-booklet-carousel a').on('click', function(e) {
				jQuery(bookletThumbs+' .wp-booklet-carousel li a').removeClass('selected');
				jQuery(e.currentTarget).addClass('selected');
				
				<?php if ( $properties['wp-booklet-closed'] == "false" ) : ?>
					var index = jQuery(bookletThumbs+' .wp-booklet-carousel li').index( jQuery(e.currentTarget).parent() );
					if ( index == 0 ) { index = 'start' }
				<?php else: ?>
					var index = jQuery(bookletThumbs+' .wp-booklet-carousel li').index( jQuery(e.currentTarget).parent() ) + 1;
					if ( index == 1 ) { index = 'start' }
				<?php endif ?>
				jQuery(booklet).wpbooklet('gotopage',index);
			});
			
			jQuery("#wp-booklet-<?php echo $id ?> a").hover(
				function(e) {
					jQuery(e.currentTarget).animate({opacity:0.7},250);
				},
				function(e) {
					jQuery(e.currentTarget).animate({opacity:1},250);
				}
			);
		<?php endif ?>
	});
</script>
</div>