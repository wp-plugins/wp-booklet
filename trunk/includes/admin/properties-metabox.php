<p>
	<label>Actual page width</label><br/>
	<input size="18" type="text" name="wp-booklet-metas[wp-booklet-width]" value="<?php echo $properties['wp-booklet-width'] ? $properties['wp-booklet-width'] : 600  ?>"/> pixels
</p>
<p>
	<label>Actual page height</label><br/>
	<input size="18" type="text" name="wp-booklet-metas[wp-booklet-height]" value="<?php echo $properties['wp-booklet-height'] ? $properties['wp-booklet-height'] : 400 ?>"/> pixels
</p>
<p>
	<label>Page Padding</label><br/>
	<input size="18" type="text" name="wp-booklet-metas[wp-booklet-padding]" value="<?php echo $properties['wp-booklet-padding'] > -1 ? $properties['wp-booklet-padding'] : 10 ?>"/> pixels<br/>
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
		<option <?php if ( $properties["wp-booklet-pagenumbers"] == "true" || !$properties["wp-booklet-pagenumbers"] ) { echo "selected='selected'"; } ?> value="true">Yes</option>
		<option <?php if ( $properties["wp-booklet-pagenumbers"] == "false" ) { echo "selected='selected'"; } ?> value="false">No</option>
	</select>
</p>
<p>
	<label>Show thumbnails?</label>
	<select class="widefat" name="wp-booklet-metas[wp-booklet-thumbnails]">
		<option <?php if ( $properties["wp-booklet-thumbnails"] == "true" ) { echo "selected='selected'"; } ?> value="true">Yes</option>
		<option <?php if ( $properties["wp-booklet-thumbnails"] == "false" || !$properties["wp-booklet-thumbnails"] ) { echo  "selected='selected'"; } ?> value="false">No</option>
	</select>
</p>
<p>
	<label>Enable popup on click?</label>
	<select class="widefat" name="wp-booklet-metas[wp-booklet-popup]">
		<option <?php if ( $properties["wp-booklet-popup"] == "true" ) { echo "selected='selected'"; } ?> value="true">Yes</option>
		<option <?php if ( $properties["wp-booklet-popup"] == "false" || !$properties["wp-booklet-popup"] ) { echo  "selected='selected'"; } ?> value="false">No</option>
	</select>
</p>
<p>
	<label>Booklet cover behavior</label>
	<select class="widefat" name="wp-booklet-metas[wp-booklet-closed]">
		<option <?php if ( $properties["wp-booklet-closed"] == "false" || !$properties["wp-booklet-closed"] ) { echo  "selected='selected'"; } ?> value="false">Opened always</option>
		<option <?php if ( $properties["wp-booklet-closed"] == "true" ) { echo "selected='selected'"; } ?> value="true">Closable - Either side</option>
		<option <?php if ( $properties["wp-booklet-closed"] == "closable-centered" ) { echo "selected='selected'"; } ?> value="closable-centered">Closable - Centered</option>
	</select>
</p>
