<div class="wrap">

	<h2>Settings</h2>

	<form method="post" action="options.php">

		<?php settings_fields('wp-booklet-settings'); ?>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Ghostscript status</th>
					<td>
						<?php
							echo $this->_command_exists('gs') || $this->_command_exists('gswin32c') ? "Installed" : "Ghostscript doesn't seem to be installed. Please contact your site administrator.";
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Imagemagick status</th>
					<td>
						<?php
							echo $this->_command_exists('convert') ? "Installed" : "Imagemagick doesn't seem to be installed. Please contact your site administrator.";
						?>
					</td>
				</tr>
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
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Changes" />
		</p>

	</form>

</div>