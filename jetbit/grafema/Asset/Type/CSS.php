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

class CSS implements Provider
{
	/**
	 * List of type 'css' style tag.
	 *
	 * @since 1.0.0
	 */
	private array $whitelist = ['rel', 'id', 'href', 'media'];

	/**
	 * @since 1.0.0
	 */
	public function add( string $id, string $src, array $args ): array
	{
		$helpers = new Helpers();

		return array_merge(
			[
				'rel'   => 'stylesheet',
				'type'  => 'css',
				'href'  => $src,
				'id'    => sprintf( '%s-css', $id ),
				'uuid'  => $id,
				'media' => '',
				'url'   => $helpers->parseSrc( $src ),
			],
			$args
		);
	}

	public function plug( array $asset ): string
	{
		$helpers = new Helpers();

		return sprintf( "\n<link%s/>", $helpers->format( $asset, $this->whitelist ) );
	}

	/**
	 * @since 1.0.0
	 */
	public function minify( string $code ): string
	{
		// remove comments
		$code = preg_replace( '/\/\*[\s\S]*?\*\//', '', $code, -1 );
		$code = preg_replace( '/\/\/.*$/m', '', $code );

		// remove unnecessary spaces and line breaks
		$code = preg_replace( '/\s+/', ' ', $code );
		$code = str_replace( ["\r\n", "\r", "\n", "\t"], '', $code );

		// remove unnecessary spaces before symbols
		return preg_replace( '/\s*([=:;,+\-*\/<>\(\)\{\}\[\]])\s*/', '$1', $code );
	}
}
