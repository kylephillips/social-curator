<?php 

namespace SocialCurator\Import;

/**
* Import a remote image into the media library
*/
class MediaImporter 
{

	/**
	* Import the Image to the Library
	* @return int attachment id
	*/
	function runImport($image_url)
	{
		$tmp = download_url( $image_url );
		$file_array = array(
			'name' => basename( $image_url ),
			'tmp_name' => $tmp
		);

		// Check for download errors
		if ( is_wp_error( $tmp ) ) {
			@unlink( $file_array[ 'tmp_name' ] );
			return $tmp;
		}

		$id = media_handle_sideload( $file_array, 0 );
		// Check for handle sideload errors.
		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			return $id;
		}
		return $id;
	}

}