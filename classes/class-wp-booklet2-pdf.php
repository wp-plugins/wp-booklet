<?php

class WP_Booklet2_PDF {

	protected $_file;

	/**
	 * The constructor
	 *
	 * @param $file string - path to the PDF file
	 * 
	 * @return WP_Booklet2_PDF
	 */
	function __construct( $file ) {

		$this->_file = $file;

		if ( !$this->is_file_valid() ) {
			throw new Exception("Invalid PDF");
		}

	}

	/** 
	 * Get PDF page count
	 *
	 * return int
	 */
	protected function _get_pdf_page_count() {

		$file = $this->_file;

		if(!file_exists($file))return null;
		if (!$fp = @fopen($file,"r"))return null;
		$pages=0;
		while(!feof($fp)) {
			$line = fgets($fp,255);
			if (preg_match('/\/Count [0-9]+/', $line, $matches)){
				preg_match('/[0-9]+/',$matches[0], $matches2);
				if ($pages<$matches2[0]) $pages=$matches2[0];
			}
		}
		fclose($fp);
		return (int)$pages;
	}

	/**
	 * Checks that PDF is valid
	 *
	 * @return bool
	 */
	function is_file_valid() {
		$filetype = wp_check_filetype($this->_file);

		if ( $filetype['type'] == 'application/pdf' ) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Checks if file can be converted into an image
	 *
	 * @return bool
	 */
	function is_convertible_to_image() {
		$wp_upload_dir = wp_upload_dir();
		$upload_path = $wp_upload_dir['path'];
		
		$pdf = $this->_file;
		$target = $upload_path . '/wp-booklet-test-' . uniqid() . '.jpg';
		$command = new WP_Booklet2_Command("convert", "-limit memory 32MiB -limit map 64MiB {$pdf} {$target}");
		$result = $command->run_command();
	
		if ( !$result['error'] ) {
			$file_exists = file_exists( $target );
			@unlink( $target );
			return $file_exists;
		}
		else {
			return false;
		}
	}


	/** 
	 * Converts PDF images into Wordpress attachments
	 *
	 * @param $page_count int - number of pages to be converted into photos. Always starts at the first page of the PDF.
	 *
	 * @return mixed - array of attachments, false on error
	 */
	function get_pages_as_photos() {

		$pdf_path = $this->_file;
		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['path'];
		$image_group = uniqid();
		$actual_page_count =  $this->_get_pdf_page_count( $pdf_path );
		$attachment_page_count = ( get_option('wp-booklet-pdf-limit-status') == 'on' && $actual_page_count > 10 ) ? 10 : $actual_page_count;
		$conversion_page_count = ( get_option('wp-booklet-pdf-limit-status') == 'on' && $actual_page_count > 10 ) ? "[0-9]" : "";
		
		//Check that upload directory is writable by server
		if ( $upload_dir['error'] || !is_writable($upload_path) ) {
			return false;
		}
		
		//Use Imagemagick and Ghostscript to convert PDF pages into jpegs
		$command = new WP_Booklet2_Command("convert", "-density 200 -verbose -limit memory 32MiB -limit map 64MiB {$pdf_path}{$conversion_page_count} {$upload_path}/{$image_group}.jpg");
		$operation = $command->run_command();
		
		if ( $operation['error'] ) {
			return false;
		}

		//Fill this array with new attachments
		$images = array();

		for ( $ctr = 0; $ctr < $attachment_page_count; $ctr++ ) {
			
			//Prepare attachment
			$filename = $upload_path."/".$image_group."-".$ctr.".jpg";
			$wp_filetype = wp_check_filetype(basename($filename), null );
			$attachment = array(
				'guid' => $upload_dir['url'] . '/' . basename( $filename ), 
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			//Insert attachment
			$attach_id = wp_insert_attachment( $attachment, $filename);

			//Create attachment metadata
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			$meta = wp_update_attachment_metadata( $attach_id, $attach_data );
			$size = getimagesize($filename);
			
			$images[] = array(
				'id'=>$attach_id,
				'src'=>$upload_dir['url'] . '/' . basename( $filename ),
				'width'=>$size[0],
				'height'=>$size[1]
			);

		}

		return $images;
	}

}