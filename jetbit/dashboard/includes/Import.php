<?php
use Post\Post;

/**
 *
 *
 * @package Grafema
 */
class Import {

	/**
	 *
	 *
	 * @return array
	 */
	public static function posts(): array {
		$imported = [];
		$filename = $_POST['filename'] ?? '';
		$map      = $_POST['map'] ?? [];
		$status   = $_POST['status'] ?? '';
		$author   = $_POST['author'] ?? '';
		$type     = $_POST['type'] ?? '';

		if ( file_exists( $filename ) ) {
			$rows = \File\Csv::import( $filename );
			foreach ( $rows as $row ) {
				$args = array_filter(
					array_combine( $map, $row ),
					function( $key ) {
						return ! empty( $key );
					},
					ARRAY_FILTER_USE_KEY
				);

				$status = trim( strval( $row['status'] ?? $status ) );
				$author = trim( strval( $row['author'] ?? $author ) );
				if ( ! empty( $status ) ) {
					$args['status'] = $status;
				}

				if ( ! empty( $author ) ) {
					$args['author'] = $author;
				}

				$post_id = Post::add( $type, $args );
				if ( $post_id ) {
					$imported[] = $post_id;
				}
			}
		}
		return $imported;
	}
}
