<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Asset\Type;

use Grafema\Asset\Helpers;
use Grafema\Asset\Interfaces\Provider;

class JS implements Provider
{
	/**
	 * List of type 'js' script tag.
	 *
	 * @since 1.0.0
	 */
	private array $whitelist = ['id', 'src', 'async', 'defer', 'media'];

	/**
	 * @since 1.0.0
	 */
	public function add( string $id, string $src, array $args ): array
	{
		$helpers = new Helpers();

		return array_merge(
			[
				'id'           => sprintf( '%s-js', $id ),
				'uuid'         => $id,
				'src'          => $src,
				'type'         => 'js',
				'async'        => false,
				'defer'        => false,
				'dependencies' => [],
				'data'         => [],
				'media'        => '',
				'url'          => $helpers->parseSrc( $src ),
			],
			$args
		);
	}

	public function plug( array $asset ): string
	{
		$helpers = new Helpers();
		$key     = $helpers->sanitizeKey( $asset['uuid'] ?? '' );
		$data    = $asset['data'] ?? [];
		$return  = '';
		if ( ! empty( $data ) && is_array( $data ) ) {
			$return = sprintf( "\n<script>const %s = %s</script>", $key, json_encode( $data, JSON_HEX_TAG ) );
		}

		return $return . sprintf( "\n<script%s></script>", $helpers->format( $asset, $this->whitelist ) );
	}

	/**
	 * @since 1.0.0
	 */
	public function minify( string $code ): string
	{
		// remove comments
		$code = preg_replace( '/\/\*[\s\S]*?\*\//', '', $code, -1, $count );
		$code = preg_replace( '/\/\/.*$/m', '', $code );

		// remove unnecessary spaces and line breaks
		$code = preg_replace( '/\s+/', ' ', $code );
		$code = str_replace( ["\r\n", "\r", "\n", "\t"], '', $code );

		// remove unnecessary spaces before symbols
		return preg_replace( '/\s*([=:;,+\-*\/<>\(\)\{\}\[\]])\s*/', '$1', $code );
	}
}
