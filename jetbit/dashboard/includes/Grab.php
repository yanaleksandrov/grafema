<?php
/**
 *
 *
 * @package Grafema
 */
class Grab {

	/**
	 * Upload files from external url
	 *
	 * @return array
	 */
	public static function files(): array {
		$files = [];
		$urls  = Url::extract( $_POST['urls'] ?? '' );
		if ( $urls ) {
			foreach ( $urls as $url ) {
				$files[] = ( new File\File() )->to( GRFM_UPLOADS . 'i/' )->grab( $url );
			}
		}
		return $files;
	}
}
