<div class="wrap">

	<h2>Importer</h2>
	
	<div class="importer-page-message updated success">
		<p><strong>Import successful.</strong></p>
	</div>
	<div class="importer-page-message updated error">
		<p><strong>Import unsuccessful. Please try again.</strong></p>
	</div>
	
	<p>Import your WP Booklet 1.x booklets to WP Booklet 2. Click the button below to get started. Please keep this page open until the process completes.</p>

	<form method="post" id="wp-booklet2-import-form">
		<table class="form-table">
		
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="wp-booklet-reimport">Remaining booklets</label></th>
					<td>
						<span class="remaining-count"><?php echo $remaining_booklets ?></span>
					</td>
				</tr>
			</tbody>
			
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="Import" />
		</p>
	</form>
	
</div>

<script type="text/javascript">
	jQuery(document).ready( function() {

		jQuery("#wp-booklet2-import-form").submit( function(e) {
			
			e.preventDefault();
			
			do_import();
			
			
		});
		
		function do_import() {
			
			var data = {
				'action':'import_booklets'
			};
			
			jQuery.post(ajaxurl,data,function(result) {
				
				jQuery(".remaining-count").text( result.remaining );
				
				if ( parseInt( result.remaining ) > 0 ) {
					setTimeout( function() {
						do_import();
					},1);
				}
				else {
					jQuery(".importer-page-message.success").show();
				}
				
			},'json');
			
		}
		
	});
</script>