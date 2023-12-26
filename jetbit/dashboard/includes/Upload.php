<?php

use Post\Post;
use File\Image;

/**
 *
 *
 * @package Grafema
 */
class Upload {

	/**
	 * Check the compliance of the server with the minimum requirements
	 *
	 * @return array|File\File|Errors
	 */
	public static function file(): array|File\File|Errors {
		$files = $_FILES ?? [];
		if ( $files ) {
			foreach ( $files as $file ) {
				return ( new File\File() )->to( GRFM_UPLOADS . 'i/' )->upload( $file );
			}
		}
		return [];
	}

	/**
	 * Upload ne files to media storage
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function media(): array {
		$posts = [];

		if ( ! is_array( $_FILES ) || empty( $_FILES ) ) {
			return $posts;
		}

		foreach ( $_FILES as $file ) {
			$sizes    = Patterns\Registry::get( 'images' );
			$file     = ( new File\File() )->to( GRFM_UPLOADS . 'i/' )->upload( $file );
			$basename = $file->data['basename'] ?? '';

			if ( ! file_exists( $file->path ) || ! is_array( $sizes ) ) {
				continue;
			}

			// crop image to different sizes
			foreach ( $sizes as $size ) {
				[ 'mime' => $mime, 'width' => $width, 'height' => $height ] = (
					new \Sanitizer\Sanitizer(
						$size,
						[
							'name'   => 'text',
							'mime'   => 'mime',
							'width'  => 'absint',
							'height' => 'absint',
						]
					)
				)->apply();

				if ( ! $mime || ! $width || ! $height ) {
					continue;
				}

				$filepath = sprintf( GRFM_UPLOADS . 'i/%s/%s', implode( 'x', [ $width, $height ] ), $basename );

				( new Image() )->fromFile( $file->path )->thumbnail( $width, $height )->toFile( $filepath, $mime );
			}

			$post_id = Post::add(
				'media',
				[
					'status' => 'publish',
					'title'  => $basename,
					'slug'   => str_replace( GRFM_UPLOADS, '', $file->path ),
					'fields' => [
						'mime' => $file->data['mime'] ?? '',
					],
				]
			);

			if ( $post_id ) {
				$posts[] = Post::get( 'media', $post_id );
			}
		}

		return $posts;
	}
}
