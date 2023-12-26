<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\File;

use Grafema\Curl;
use Grafema\Debug;
use Grafema\Dir;
use Grafema\Errors;
use Grafema\Esc;
use Grafema\Helpers\Humanize;
use Grafema\I18n;
use Grafema\Is;
use Grafema\Url;
use Grafema\Validator;

/**
 * The File class is a PHP class that provides a convenient and
 * easy-to-use API for working with files and directories in PHP.
 * With this class, you can perform a wide range of file-related
 * operations, such as reading and writing to file, uploading &
 * grabbing, moving and copying files, and many more.
 *
 * @since      1.0.0
 */
class File
{
	/**
	 * Path to the file to be manipulated.
	 */
	public string $path = '';

	/**
	 * Path to target directory.
	 */
	public string $dirpath = GRFM_UPLOADS;

	/**
	 * Data about file.
	 */
	public array $data = [];

	/**
	 * Max. file size.
	 */
	public int $maxSize;

	/**
	 * Validation errors.
	 */
	public array $errors = [];

	/**
	 * Allowed mime types.
	 */
	public array $mimes = [
		// image formats
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'webp'         => 'image/webp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon',

		// vector formats
		'svg' => 'image/svg+xml',

		// video formats
		'asf|asx'      => 'video/x-ms-asf',
		'wmv'          => 'video/x-ms-wmv',
		'wmx'          => 'video/x-ms-wmx',
		'wm'           => 'video/x-ms-wm',
		'avi'          => 'video/avi',
		'divx'         => 'video/divx',
		'flv'          => 'video/x-flv',
		'mov|qt'       => 'video/quicktime',
		'mpeg|mpg|mpe' => 'video/mpeg',
		'mp4|m4v'      => 'video/mp4',
		'ogv'          => 'video/ogg',
		'webm'         => 'video/webm',
		'mkv'          => 'video/x-matroska',

		// text formats
		'txt' => 'text/plain',
		'csv' => 'text/csv',
		'tsv' => 'text/tab-separated-values',
		'ics' => 'text/calendar',
		'rtx' => 'text/richtext',

		// audio formats
		'mp3|m4a|m4b' => 'audio/mpeg',
		'ra|ram'      => 'audio/x-realaudio',
		'wav'         => 'audio/wav',
		'ogg|oga'     => 'audio/ogg',
		'mid|midi'    => 'audio/midi',
		'wma'         => 'audio/x-ms-wma',
		'wax'         => 'audio/x-ms-wax',
		'mka'         => 'audio/x-matroska',

		// misc application formats
		'rtf'     => 'application/rtf',
		'pdf'     => 'application/pdf',
		'swf'     => 'application/x-shockwave-flash',
		'class'   => 'application/java',
		'tar'     => 'application/x-tar',
		'zip'     => 'application/zip',
		'gz|gzip' => 'application/x-gzip',
		'rar'     => 'application/rar',
		'7z'      => 'application/x-7z-compressed',
		'exe'     => 'application/x-msdownload',

		// MS Office formats
		'doc'                          => 'application/msword',
		'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
		'wri'                          => 'application/vnd.ms-write',
		'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
		'mdb'                          => 'application/vnd.ms-access',
		'mpp'                          => 'application/vnd.ms-project',
		'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
		'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
		'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
		'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
		'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
		'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
		'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
		'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
		'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
		'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
		'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
		'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',

		// OpenOffice formats
		'odt' => 'application/vnd.oasis.opendocument.text',
		'odp' => 'application/vnd.oasis.opendocument.presentation',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		'odg' => 'application/vnd.oasis.opendocument.graphics',
		'odc' => 'application/vnd.oasis.opendocument.chart',
		'odb' => 'application/vnd.oasis.opendocument.database',
		'odf' => 'application/vnd.oasis.opendocument.formula',
	];

	/**
	 * @since 1.0.0
	 */
	public function __construct( string $filepath = '' )
	{
		$this->maxSize = Humanize::toBytes( ini_get( 'upload_max_filesize' ) );
		if ( file_exists( $filepath ) ) {
			$this->path = $filepath;
		}

		return $this;
	}

	/**
	 * Set target directory.
	 *
	 * @return $this
	 */
	public function to( string $dirpath ): File
	{
		$directory = ( new Dir\Dir( $dirpath ) )->create()->getPath();
		if ( is_dir( $directory ) ) {
			$this->dirpath = $directory;
		}

		return $this;
	}

	/**
	 * Write content to file.
	 */
	public function write( string $content, bool $after = true ): File
	{
		if ( is_writable( $this->path ) ) {
			$fp = fopen( $this->path, $after ? 'a' : 'w' );
			if ( ! $fp ) {
				$this->errors[] = new Errors( 'file-manipulation', sprintf( I18n::__( "I can't open the file '%s'" ), $this->path ) );
			} else {
				// writing $content to open file
				if ( fwrite( $fp, $content ) === false ) {
					$this->errors[] = new Errors( 'file-manipulation', sprintf( I18n::__( "I can't write to the file '%s'" ), $this->path ) );
				}

				fclose( $fp );
			}
		}

		return $this;
	}

	/**
	 * Rewrite some strings in file.
	 */
	public function rewrite( array $content ): File
	{
		if ( file_exists( $this->path ) && is_readable( $this->path ) && filesize( $this->path ) > 0 ) {
			$file_content = file_get_contents( $this->path );

			foreach ( $content as $field => $value ) {
				$file_content = str_replace( $field, $value, $file_content );
			}

			file_put_contents( $this->path, $file_content );
		}

		return $this;
	}

	/**
	 * Move files.
	 *
	 * @param string $source name of file or directory with path
	 * @param string $target Target directory
	 * @param array  $files  Files to be moved
	 */
	public function move( string $source, string $target, array $files ): File
	{
		$this->mkDir( $target );

		foreach ( $files as $value ) {
			if ( file_exists( $source . $value ) ) {
				rename( $source . $value, $target . $value );
			}
		}

		return $this;
	}

	/**
	 * Copy file.
	 */
	public function copy( string $new_name = '', string $postfix = '' ): File
	{
		if ( file_exists( $this->path ) ) {
			$dirname   = pathinfo( $this->path, PATHINFO_DIRNAME );
			$filename  = pathinfo( $this->path, PATHINFO_FILENAME );
			$extension = pathinfo( $this->path, PATHINFO_EXTENSION );

			if ( ! empty( $new_name ) ) {
				$filename = $new_name;
			}

			// TODO: add a check that the name of the new file was not the same as the old one
			$new_filepath = sprintf( '%s/%s.%s', $dirname, $filename . $postfix, $extension );
			if ( ! file_exists( $new_filepath ) ) {
				copy( $this->path, $new_filepath );
			}

			// rewrite path to file
			if ( file_exists( $new_filepath ) ) {
				$this->path = $new_filepath;
			}
		}

		return $this;
	}

	/**
	 * Delete files.
	 */
	public function delete(): File
	{
		if ( file_exists( $this->path ) ) {
			unlink( $this->path );
		}

		return $this;
	}

	/**
	 * Upload file to server.
	 *
	 * @return File data about current upload or errors list
	 *
	 * @since  1.0.0
	 */
	public function upload( array $file, bool $skip_if_exist = true ): File
	{
		$mimes      = implode( ',', array_values( $this->mimes ) );
		$extensions = str_replace( '|', ',', implode( ',', array_keys( $this->mimes ) ) );
		$validator  = ( new Validator(
			$file,
			[
				'type'     => 'type:' . $mimes,
				'tmp_name' => 'required',
				'size'     => 'maxSize:' . Humanize::fromBytes( Humanize::toBytes( ini_get( 'upload_max_filesize' ) ) ),
				'name'     => 'extension:' . $extensions,
				'error'    => 'equals:0',
			]
		) )->extend(
			'error:equals',
			I18n::__( 'An error occurred while uploading the file, please try again.' )
		)->apply();

		$filename   = $file['name'] ?? '';
		$this->path = sprintf( '%s%s', $this->dirpath, $filename );
		if ( file_exists( $this->path ) ) {
			/*
			 * If the downloaded file is completely identical,
			 * then we return the data to the existing file, and do not download it again
			 */
			if ( $skip_if_exist && $this->signature() === $this->signature( $file ) ) {
				$this->setData();

				return $this;
			}

			// make sure that the file name in the folder is unique
			$this->path = sprintf( '%s%s', $this->dirpath, $this->setName( $filename ) );
		}

		// if the incoming data has been checked for validity, save it
		if ( ! $validator instanceof Validator ) {
			$status = move_uploaded_file( $file['tmp_name'], $this->path );
			if ( ! $status ) {
				$this->errors[] = new Errors( Debug::get_backtrace(), I18n::__( 'Can\'t upload file.' ) );
			}
			$this->setData();
		}

		return $this;
	}

	/**
	 * Upload file via URL.
	 *
	 * @since  1.0.0
	 */
	public function grab( string $url ): File
	{
		$upload_dir = Esc::url( preg_replace( '/\\?.*/', '', $this->dirpath . basename( $url ) ) );
		$url        = Esc::url( $url );
		if ( ! Is::url( $url ) ) {
			$this->errors[] = new Errors( Debug::get_backtrace(), I18n::__( 'Invalid URL.' ) );
		}

		$extension = pathinfo( $url, PATHINFO_EXTENSION );
		if ( empty( $extension ) ) {
			$this->errors[] = new Errors( Debug::get_backtrace(), I18n::__( 'The file cannot be grabbed because it does not contain an extension.' ) );
		}

		$fp = fopen( $upload_dir, 'wb' );

		$curl = new Curl();
		$curl->setOpt( CURLOPT_URL, $url );
		$curl->setOpt( CURLOPT_FILE, $fp );
		$curl->setOpt( CURLOPT_HEADER, 0 );
		$curl->exec();

		fclose( $fp );

		$success = $curl->isSuccess();
		$errors  = $curl->getErrorMessage();

		$curl->close();
		if ( ! $success ) {
			$this->errors[] = $errors;
		}

		if ( empty( $this->errors ) && file_exists( $upload_dir ) ) {
			$this->path = $upload_dir;
			$this->setData();
		}

		return $this;
	}

	/**
	 * Download file.
	 *
	 * @since  1.0.0
	 */
	public function download(): void
	{
		if ( file_exists( $this->path ) ) {
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename="' . $this->basename() . '"' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );
			header( 'Content-Length: ' . $this->size() );

			// read the file and output it to the browser
			ob_clean();
			flush();
			readfile( $this->path );
			exit;
		}
	}

	/**
	 * Set maximum file size.
	 *
	 * @since 1.0.0
	 *
	 * @param string $size String designation of the file size, e.g.: 300kb, 20Mb, 0.3Gb, 3Tb
	 */
	public function setMaxSize( string $size ): File
	{
		if ( $size ) {
			$this->maxSize = Humanize::toBytes( $size );
		}

		return $this;
	}

	/**
	 * Sanitize & normalize file name: remove special chars & spaces.
	 * Check for a file with the same name, add a prefix until we find a free name.
	 *
	 * @since 1.0.0
	 */
	public function setName( string $filename ): string
	{
		$extension = pathinfo( $filename, PATHINFO_EXTENSION );
		$filename  = pathinfo( $filename, PATHINFO_FILENAME );

		// remove not allowed symbols & letters
		// TODO sanitize file name
		$filename = mb_convert_encoding( htmlspecialchars( mb_strtolower( $filename ) ) );
		$formats  = mb_convert_encoding(
			'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª',
			'ISO-8859-1',
			'UTF-8'
		);
		$replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyrr                                 ';

		// remove whitespaces
		$filename = str_replace(
			['-----', '----', '---', '--'],
			'-',
			str_replace( ' ', '-', trim( strtr( $filename, $formats, $replace ) ) )
		);

		// find existing files with same name & add prefix
		$new_filename = sprintf( '%s.%s', $filename, $extension );
		$new_filename = str_replace( ' ', '-', $new_filename );
		$filepath     = sprintf( '%s%s', $this->dirpath, $new_filename );
		if ( file_exists( $filepath ) ) {
			$i      = 1;
			$prefix = '-' . $i;

			while ( is_file( $this->dirpath . $filename . $prefix . '.' . $extension ) ) {
				$prefix = '-' . ++$i;
			}
			$new_filename = sprintf( '%s%s.%s', $filename, $prefix, $extension );
		}

		return $new_filename;
	}

	/**
	 * Set allowed mime types.
	 *
	 * @since 1.0.0
	 *
	 * @param array $mimes
	 */
	public function setAllowedMimeTypes( $mimes ): File
	{
		if ( is_array( $mimes ) ) {
			$this->mimes = $mimes;
		}

		return $this;
	}

	/**
	 * Change the mode of a file.
	 */
	public function setMode( int $mode ): File
	{
		if ( ! chmod( $this->path, $mode ) ) {
			$this->errors[] = new Errors( 'file-manipulations', I18n::__( 'Failed to update file access rights' ) );
		}

		return $this;
	}

	/**
	 * Set data about file.
	 *
	 * @since 1.0.0
	 */
	private function setData(): array
	{
		$filesize   = $this->size();
		$this->data = [
			'filename'      => $this->filename(),
			'basename'      => $this->basename(),
			'dirname'       => $this->dirname(),
			'signature'     => $this->signature(),
			'mime'          => $this->mime(),
			'type'          => $this->type(),
			'extension'     => $this->extension(),
			'exists'        => $this->exists(),
			'modified'      => $this->modified(),
			'size'          => $filesize,
			'sizeHumanized' => Humanize::fromBytes( $filesize ),
			'url'           => Url::site( $this->basename() ),
			'sizes'         => [
				'full' => [
					'height'      => '',
					'width'       => '',
					'orientation' => '',
					'url'         => '',
				],
			],
		];

		return $this->data;
	}

	/**
	 * Get the file's last modification time.
	 */
	private function modified(): int
	{
		return filemtime( $this->path );
	}

	/**
	 * Get the MIME type of file.
	 */
	private function mime(): string
	{
		return finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $this->path );
	}

	/**
	 * Get the type of file.
	 */
	private function type(): string
	{
		return filetype( $this->path );
	}

	/**
	 * Get the extension of a file.
	 */
	private function extension(): string
	{
		return pathinfo( $this->path, PATHINFO_EXTENSION );
	}

	/**
	 * Determine if a file exists.
	 */
	private function exists(): bool
	{
		return file_exists( $this->path );
	}

	/**
	 * Get the size of a file.
	 */
	private function size(): int
	{
		return filesize( $this->path );
	}

	/**
	 * Create unique signature of the file.
	 *
	 * @since 1.0.0
	 *
	 * @param ?array $file
	 */
	private function signature( ?array $file = [] ): string
	{
		$basename = $file['name'] ?? $this->basename();
		$mime     = $file['type'] ?? $this->mime();
		$filesize = $file['size'] ?? $this->size();

		return hash_hmac( 'ripemd160', implode( '', [$basename, $mime, $filesize] ), GRFM_HASH_KEY );
	}

	/**
	 * Get the file name from a path.
	 */
	private function filename(): string
	{
		return pathinfo( $this->path, PATHINFO_FILENAME );
	}

	/**
	 * Get the base name from a path.
	 */
	private function basename(): string
	{
		return pathinfo( $this->path, PATHINFO_BASENAME );
	}

	/**
	 * Get the directory name from a path.
	 */
	private function dirname(): string
	{
		return pathinfo( $this->path, PATHINFO_DIRNAME );
	}
}
