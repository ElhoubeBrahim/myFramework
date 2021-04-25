<?php


	namespace app\core\filesystem;

	use Mimey\MimeTypes;

	/**
	 * Class FS
	 * This class is used to deal with File System
	 * @package app\core\filesystem
	 */
	class FS
	{
		/**
		 * @var MimeTypes $detector
		 */
		private $detector;
		/**
		 * Upload directory path
		 * @var string $upload_dir
		 */
		private $upload_dir;
		/**
		 * Get file upload status
		 * @var bool $uploaded
		 */
		public $uploaded = true;

		/**
		 * FS constructor.
		 * @param $up_dir
		 */
		public function __construct($up_dir) {
			// Create instance of MimeTypes class
			$this->detector = new MimeTypes();
			// Get the upload directory
			$this->upload_dir = $up_dir . '/';
		}

		/**
		 * Get the extension by the mimeType
		 * @param $mimeType
		 * @return array|string|null
		 */
		public function get_extension($mimeType) {
			return $this->detector->getExtension($mimeType);
		}

		/**
		 * Get path info of a given path
		 * @param $path
		 * @return array
		 */
		public function path($path) {
			// If the path is invalid return []
			if (empty($path) or !is_string($path)) return [];
			// Else
			// Return info
			return [
				'dir' => pathinfo($path, PATHINFO_DIRNAME),
				'filename' => pathinfo($path, PATHINFO_FILENAME),
				'basename' => pathinfo($path, PATHINFO_BASENAME),
				'extension' => pathinfo($path, PATHINFO_EXTENSION),
				'.extension' => '.' . pathinfo($path, PATHINFO_EXTENSION)
			];
		}

		/**
		 * Generate a random name format "timestamp-ab-append"
		 * @param null $append
		 * @return string
		 */
		public function rname($append = null) {
			// Get the current timestamp
			$time = time();
			// Get random number between 10 and 99
			$rd = rand(10, 99);
			// Return the random generated name
			return ($append === null) ? "$time-$rd" : "$time-$rd-$append";
		}

		/**
		 * Upload a file to the server
		 * @param $file
		 * @param null $name
		 * @return bool
		 */
		public function upload($file, $name = null) {
			// If the given file is not valid
			if (!is_array($file) or !isset($file['tmp_name'])) {
				// Exit
				$this->uploaded = false;
				return false;
			}

			// If the name is not given, get the original name of the file
			if ($name === null) $name = $this->path($file['name'])['basename'];

			// if the file was not uploaded
			if (!move_uploaded_file($file['tmp_name'], $this->upload_dir . $name)) {
				// Exit
				$this->uploaded = false;
				return false;
			}

			// if the file was uploaded successfully
			$this->uploaded = true;
			return true;
		}

	}