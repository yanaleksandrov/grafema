<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

/**
 * Allows assets (CSS, JS, etc.) to be included throughout the
 * application, and then outputted later based on dependencies.
 * This makes sure all assets will be included in the correct
 * order, no matter what order they are defined in.
 *
 * @version   1.0.0
 */
final class Asset
{
	/**
	 * Assets list.
	 */
	public static array $assets = [];
	
	/**
	 * Correctly add JS scripts and CSS styles to the page.
	 *
	 * To connect files, it is better to use this function than to specify the path to the file directly.
	 * This will allow you to combine JS or CSS files into one without any problems.
	 * Get rid of script conflicts when the dependent script is connected to the main one.
	 *
	 * @param string $id      Name of the script. Should be unique.
	 * @param string $src     full URL of the script, or path of the script relative to the Grafema root directory
	 * @param array  $args    list of attributes
	 * @param string $version version of file
	 *
	 * @return Asset
	 */
	public static function enqueue( string $id, string $src, array $args = [], string $version = '' ): Asset
	{
		$helpers = new Asset\Helpers();

		$id  = $helpers->sanitizeID( $id );
		$src = $helpers->parseSrc( $src );
		
		if ( ! empty( $id ) && ! empty( $src ) ) {
			$extension = pathinfo( $src, PATHINFO_EXTENSION ) ?? '';
			$src .= ( $version ? '?' . http_build_query( ['v' => $version] ) : '' );
			$uuid = sprintf( '%s-%s', $id, $extension );
			
			/**
			 * Add to assets
			 *
			 * @since 1.0.0
			 */
			self::$assets[ $uuid ] = match ( $extension ) {
				'js'  => ( new Asset\Type\JS() )->add( $id, $src, $args ),
				'css' => ( new Asset\Type\CSS() )->add( $id, $src, $args ),
			};
		}

		return new self();
	}
	
	/**
	 * Remove a previously enqueued source.
	 *
	 * @param string $id The unique id of the asset which to be deleted
	 *
	 * @since 1.0.0
	 */
	public static function remove( string $id ): Asset
	{
		if ( isset( self::$assets[$id] ) ) {
			unset( self::$assets[$id] );
		}

		return new self();
	}
	
	/**
	 * Searches for all files and connects them, use this function if the sequence of files does not matter.
	 */
	public static function find( string $dirpath ): Asset {
		
		return new self();
	}
	
	/**
	 * @param  string $pattern
	 * @return void
	 * @since  1.0.0
	 */
	public static function plug( string $pattern = '' ): void {
		$helpers = new Asset\Helpers();

		$assets = $helpers->sort( self::$assets );
		foreach ( $assets as $asset ) {
			$type = $asset['type'] ?? '';
			$url  = $asset['url'] ?? '';
			
			if ( ! empty( $pattern ) && ! fnmatch($pattern, $url ) ) {
				continue;
			}

			echo match ( $type ) {
				'js'    => ( new Asset\Type\JS() )->plug( $asset ),
				'css'   => ( new Asset\Type\CSS() )->plug( $asset ),
				default => '',
			};
		}
	}
}
