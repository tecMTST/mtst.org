<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php


class CBXPetitionBlueimpFileUploadHandlerCustom extends CBXPetitionBlueimpFileUploadHandler {

	protected function trim_file_name( $file_path, $name, $size, $type, $error, $index, $content_range ) {
		// Remove path information and dots around the filename, to prevent uploading
		// into different directories or replacing hidden system files.
		// Also remove control characters and spaces (\x00..\x20) around the filename:

		//$name = trim( $this->basename( stripslashes( $name ) ), ".\x00..\x20" );
		$name = md5( date( 'Y-m-d H:i:s:u' ) );

		// Use a timestamp for empty filenames:
		if ( ! $name ) {
			$name = str_replace( '.', '-', microtime( true ) );
		}

		return $name;
	}//end method trim_file_name
}//end class CBXPetitionBlueimpFileUploadHandlerCustom