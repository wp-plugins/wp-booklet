<p>
	<label>Actual page width</label><br/>
	<input size="18" type="text" name="wp-booklet-metas[wp-booklet-width]" value="<?php echo $booklet->get_width()  ?>"/> pixels
</p>
<p>
	<label>Actual page height</label><br/>
	<input size="18" type="text" name="wp-booklet-metas[wp-booklet-height]" value="<?php echo $booklet->get_height() ?>"/> pixels
</p>
<p>
	<label>Page Padding</label><br/>
	<input size="18" type="text" name="wp-booklet-metas[wp-booklet-padding]" value="<?php echo $booklet->get_padding() ?>"/> pixels<br/>
</p>
<p>
	<label>Flip speed</label><br/>
	<input size="18" type="text" name="wp-booklet-metas[wp-booklet-speed]" value="<?php echo $booklet->get_speed() ?>"/> milliseconds 
</p>
<p>
	<label>Automatic flip delay</label><br/>
	<input size="18" type="text" name="wp-booklet-metas[wp-booklet-delay]" value="<?php echo $booklet->get_delay() ?>"/> milliseconds<br/>
	<span style="font-size:10px;font-style:italic">Set to 0 for manual flipping.</span>
</p>
<p>
	<label>Page direction</label>
	<select class="widefat" name="wp-booklet-metas[wp-booklet-direction]">
		<option <?php if ( $booklet->get_direction() == 'LTR' ) : ?> selected="selected" <?php endif ?> value="LTR">LTR</option>
		<option <?php if ( $booklet->get_direction() == 'RTL' ) : ?> selected="selected" <?php endif ?> value="RTL">RTL</option>
	</select>
</p>
<p>
	<label>Show navigation arrows?</label>
	<select class="widefat" name="wp-booklet-metas[wp-booklet-arrows]">
		<option <?php if ( $booklet->are_arrows_enabled() ) : ?> selected="selected" <?php endif ?> value="true">Yes</option>
		<option <?php if ( !$booklet->are_arrows_enabled() ) : ?> selected="selected" <?php endif ?> value="false">No</option>
	</select>
</p>
<p>
	<label>Show page numbers?</label>
	<select class="widefat" name="wp-booklet-metas[wp-booklet-pagenumbers]">
		<option <?php if ( $booklet->are_page_numbers_enabled() ) : ?> selected="selected" <?php endif ?> value="true">Yes</option>
		<option <?php if ( !$booklet->are_page_numbers_enabled() ) : ?> selected="selected" <?php endif ?> value="false">No</option>
	</select>
</p>
<p>
	<label>Show thumbnails?</label>
	<select class="widefat" name="wp-booklet-metas[wp-booklet-thumbnails]">
		<option <?php if ( $booklet->are_thumbnails_enabled() ) : ?> selected="selected" <?php endif ?> value="true">Yes</option>
		<option <?php if ( !$booklet->are_thumbnails_enabled() ) : ?> selected="selected" <?php endif ?> value="false">No</option>
	</select>
</p>
<p>
	<label>Booklet cover behavior</label>
	<select class="widefat" name="wp-booklet-metas[wp-booklet-closed]">
		<option <?php if( $booklet->get_cover_behavior() == 'open' ): ?> selected="selected" <?php endif ?> value="false">Opened always</option>
		<option<?php if( $booklet->get_cover_behavior() == 'closed' ): ?> selected="selected" <?php endif ?> value="true">Closable - Either side</option>
		<option <?php if( $booklet->get_cover_behavior() == 'center-closed' ): ?> selected="selected" <?php endif ?> value="closable-centered">Closable - Centered</option>
	</select>
</p>
