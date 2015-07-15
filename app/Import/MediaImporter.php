<?php 

namespace SocialCurator\Import;

use SocialCurator\Listeners\LogImageImportError;

/**
* Import a remote image into the media library
*/
class MediaImporter 
{
	/**
	* The Post ID the Image is being imported to
	*/
	private $post_id;

	/**
	* Error Logger
	*/
	private $error_logger;

	public function __construct()
	{
		$this->error_logger = new LogImageImportError;
	}

	/**
	* Import the Image to the Library
	* @return int attachment id
	*/
	public function runImport($image_url, $post_id = null)
	{
		$this->post_id = $post_id;

		$tmp = download_url( $image_url );
		$name = strtok($image_url, '?');

		$file_array = array(
			'name' => basename( $name ),
			'tmp_name' => $tmp
		);

		$this->logError(__('Image could not be downloaded.', 'socialcurator'));

		// Check for download errors
		if ( is_wp_error( $tmp ) ) {
			@unlink( $file_array[ 'tmp_name' ] );
			$this->logError(__('Image could not be downloaded.', 'socialcurator'));
			return $tmp;
		}

		$id = media_handle_sideload( $file_array, 0 );
		// Check for handle sideload errors.
		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			$this->logError(__('Image could not be sideloaded.', 'socialcurator'));
			return $id;
		}
		return $id;
	}

	/**
	* Log an error
	*/
	private function logError($error)
	{
		$this->error_logger->log($this->post_id, $error);
	}

}