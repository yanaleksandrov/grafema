<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Asset\Interfaces;

interface Provider
{
	/**
	 * @since 1.0.0
	 */
	public function add( string $id, string $src, array $args ): array;

	/**
	 * @since 1.0.0
	 */
	public function plug( array $asset ): string;

	/**
	 * @since 1.0.0
	 */
	public function minify( string $code ): string;
}
