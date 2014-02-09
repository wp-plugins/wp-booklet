<div class="wrap">

	<h2>Settings</h2>

	<form method="post" action="options.php">

		<?php settings_fields('wp-booklet-settings'); ?>
		
		<h3 class="title">Server Environment</h3>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Can your server convert PDF files?</th>
					<td>
						<?php if ( $actual_test ) : ?>
							<span style="color:green;font-weight:bold;font-size:16px;">Yes. You're all set.</span>
						<?php else: ?>
							<span style="color:red;font-weight:bold;font-size:16px;">No. Please contact your server administrator. The information below may help in troubleshooting the problem.</span>
						<?php endif ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Ghostscript status</th>
					<td><?php echo $gs_status ?></td>
				</tr>
				<tr valign="top">
					<th scope="row">Imagemagick status</th>
					<td><?php echo $im_status ?></td>
				</tr>
				<tr valign="top">
					<th scope="row">Is uploads directory writable by web server?</th>
					<td><?php echo $writable ?></td>
				</tr>
			</tbody>
		</table>
		
		<h3 class="title">PDF Options</h3>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="wp-booklet-pdf-limit-status">PDF page limit</label></th>
					<td>
						<input name="wp-booklet-pdf-limit-status" type="checkbox" id="wp-booklet-pdf-limit-status"  <?php echo get_option('wp-booklet-pdf-limit-status') == 'on' ? "checked='checked'" : "" ?> />
						<label for="wp-booklet-pdf-limit-status">Limit converted PDF pages to 10.</label>
						<p class="description">PDF-to-image conversion is resource-intensive and may crash your server so only the first 10 pages of PDF files get converted. You can deactivate the limit. Remember that <u><b>your server is your sole responsibility</b></u>.</p>
					</td>
				</tr>
			</tbody>
			
		</table>
		
		</form>
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Changes" />
		</p>

	</form>

</div>