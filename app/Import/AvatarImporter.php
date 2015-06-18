<?php 

namespace SocialCurator\Import;

/**
* Import a remote Image directly into a directory
*/
class AvatarImporter 
{

	/**
	* Do the import
	* @param string $directory - relative to uploads/
	* @param string $image_url - remove image url
	* @param string $new_file_name - name of new local copy
	*/
	public function run($directory, $image_url, $new_file_name)
	{
		$file = $this->fetchImage($image_url);

		$extension = pathinfo( strtok($image_url, '?') , PATHINFO_EXTENSION);
		$uploads = wp_upload_dir();
		$upload_directory = $uploads['basedir'] . '/' . $directory;
		if (!file_exists($upload_directory)) wp_mkdir_p($upload_directory);

		$filename = wp_unique_filename( $upload_directory . '/', $new_file_name . '.' . $extension );	
		$fullpathfilename = $upload_directory . '/' . $filename;
		$fileSaved = file_put_contents($fullpathfilename, $file);
		return $filename;
	}

	/**
	* Fetch the Image
	*/
	private function fetchImage($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$image = curl_exec($ch);
		curl_close($ch);
		return $image;
	}

}