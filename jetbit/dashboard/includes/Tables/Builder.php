<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Tables;

class Builder
{
	public function stylize( array $columns ): string
	{
		$result = [];

		if ( $columns ) {
			$previous = null;
			$repeat   = 1;

			foreach ( $columns as $column ) {
				$width    = trim( (string) ( ! empty( $column['width'] ) ? $column['width'] : '1fr' ) );
				$flexible = (bool) ( $column['flexible'] ?? false );

				$value = $flexible ? sprintf( 'minmax(%s, 1fr)', $width ) : $width;

				if ( $value === $previous ) {
					++$repeat;
				} else {
					if ( $previous !== null ) {
						$result[] = $repeat > 1 ? sprintf( 'repeat(%s, %s)', $repeat, $previous ) : $previous;
					}

					$previous = $value;
					$repeat   = 1;
				}
			}

			$result[] = $repeat > 1 ? sprintf( 'repeat(%s, %s)', $repeat, $previous ) : $previous;
		}

		return sprintf( '--grafema-grid-template-columns: %s', implode( ' ', $result ) );
	}
}
