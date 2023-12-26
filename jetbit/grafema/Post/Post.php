<?php
namespace Grafema\Post;

use Grafema\Patterns\Registry;
use Grafema\DB;
use Grafema\Debug;
use Grafema\Errors;
use Grafema\I18n;
use Grafema\Esc;
use Grafema\Url;

/**
 * Core class used for interacting with post types.
 */
class Post {

	/**
	 * Add post.
	 *
	 * @param $type
	 * @param $args
	 * @return Errors|string|null
	 * @since 1.0.0
	 */
	public static function add( $type, $args ): Errors|string|null {
		if ( ! Type::exist( $type ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Post type is not registered.' ) );
		}

		$author  = trim( strval( $args['author'] ?? '' ) );
		$content = trim( strval( $args['content'] ?? '' ) );
		$title   = Esc::html( trim( $args['title'] ?? '' ), false );
		$status  = Esc::html( trim( $args['status'] ?? 'draft' ), false );
		$slug    = Esc::url( trim( $args['slug'] ?? '' ) );
		$args    = [
			'author'  => $author,
			'title'   => $title,
			'content' => $content,
			'status'  => $status,
			'slug'    => $slug,
		];

		$fields = $args['fields'] ?? [];
		if ( is_array( $fields ) ) {
			foreach ( $fields as $key => $value ) {
				Field::add( $type, 12, $key, $value );
			}
		}

		DB::insert( $type, $args );

		return DB::id();
	}

	/**
	 * Get post type by ID.
	 *
	 * @param  string     $type
	 * @param  string|int $value
	 * @param  string     $by
	 * @return array
	 * @since 1.0.0
	 */
	public static function get( $type, $value, $by = 'ID' ): array {
		if ( ! Type::exist( $type ) ) {
			return [];
		}

		$post = DB::get( $type, '*', [ $by => $value ] );

		// add data for media files
		$sizes = Registry::get( 'jb.images' );

		if ( $type === 'media' && is_array( $post ) && is_array( $sizes ) ) {
			$file_path = sprintf( '%s%s', GRFM_PATH, $post['slug'] ?? '' );
			foreach ( $sizes as $index => $size ) {
				$width  = intval( $size['width'] ?? 0 );
				$height = intval( $size['height'] ?? 0 );

				if ( ! $width || ! $height ) {
					continue;
				}

				$file_resized           = str_replace( '/i/original/', sprintf( '/i/%sx%s/', $width, $height ), $file_path );
				$sizes[ $index ]['url'] = Url::fromPath( $file_resized );
			}
		}

		return array_merge( $post, [ 'sizes' => $sizes ] );
	}

	/**
	 * Remove post type by field.
	 *
	 * @param $type
	 * @param $value
	 * @param string $by
	 * @return PDOStatement
	 * @since 1.0.0
	 */
	public static function delete( $type, $value, string $by = 'ID' ): PDOStatement {
		return DB::delete( $type, [ $by => $value ] );
	}
}
