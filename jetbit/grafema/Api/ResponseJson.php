<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Api;

use Exception;

/**
 * @since 1.0.0
 */
class ResponseJson
{
	/**
	 * Convert array to JSON.
	 *
	 * @throws Exception
	 */
	public static function convert( array $response ): string
	{
		$json = json_encode( $response );

		if ( $json === false ) {
			throw new Exception( 'Error encoding array to JSON: ' . json_last_error_msg() );
		}

		return $json;
	}
}
