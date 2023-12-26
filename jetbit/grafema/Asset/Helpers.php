<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Asset;

class Helpers
{
	/**
	 * @param string $src
	 * @return string
	 */
	public function parseSrc( string $src ): string
	{
		return $this->sanitizeUrl( parse_url( $src, PHP_URL_PATH ) ?? '' );
	}

	/**
	 * Sanitize id of asset.
	 */
	public function sanitizeID( string $uuid ): string
	{
		$uuid = str_replace( ['_', '.', ','], '-', $uuid );

		return trim( preg_replace( '/\W-/', '', $uuid ) );
	}

	/**
	 * Sanitize key of asset.
	 */
	public function sanitizeKey( string $key ): string
	{
		$key = str_replace( ['_', '-'], ' ', $key );

		return lcfirst( str_replace( ' ', '', ucwords( $key ) ) );
	}

	/**
	 * Sanitize url.
	 */
	public function sanitizeUrl( string $url ): string
	{
		// remove leading and trailing whitespace
		$url = trim( $url );

		// remove characters other than letters, numbers, hyphens, underscores, dots, and slashes
		$url = preg_replace( '/[^a-zA-Z0-9-_.\/?]/', '', $url );

		// remove double and triple slashes
		$url = preg_replace( '/\/{2,}/', '/', $url );

		// normalize slashes
		return str_replace( ';//', '://', $url );
	}

	/**
	 * Convert array to attributes.
	 */
	public function format( array $attributes, array $whitelist ): string
	{
		$result = [];

		foreach ( $attributes as $attribute => $value ) {
			if ( ! in_array( $attribute, $whitelist, true ) ) {
				continue;
			}

			$attribute = trim( htmlspecialchars( $attribute, ENT_QUOTES, 'UTF-8' ) );
			$value     = trim( htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' ) );
			if ( $attribute ) {
				if ( in_array( $attribute, ['async', 'defer'], true ) && ! empty( $value ) ) {
					$result[] = $attribute;
				}

				if ( $value ) {
					$result[] = sprintf( '%s="%s"', $attribute, $value );
				}
			}
		}

		return $result ? ' ' . implode( ' ', $result ) : '';
	}

	/**
	 * Sort sources by dependencies.
	 *
	 * @since 1.0.0
	 */
	public function sort( array $assets ): array
	{
		$dependents = array_filter( $assets, fn ( $item ) => ! empty( $item['dependencies'] ) );
		$assets     = array_filter( $assets, fn ( $item ) => empty( $item['dependencies'] ) );

		foreach ( $dependents as $key => $dependent ) {
			$dependencies = $dependent['dependencies'] ?? [];
			if ( empty( $dependencies ) || ! is_array( $dependencies ) ) {
				continue;
			}

			$index    = null;
			$filtered = array_intersect_key( $assets, array_flip( $dependencies ) );
			if ( $filtered ) {
				$index = max( array_keys( $filtered ) );
			}

			if ( $index ) {
				$splitIndex = array_search( $index, array_keys( $assets ), true );
				$primary    = array_slice( $assets, 0, $splitIndex + 1, true );
				$secondary  = array_slice( $assets, $splitIndex + 1, null, true );
				$assets     = $primary + [$key => $dependent] + $secondary;
			}
		}

		return $assets;
	}
}
