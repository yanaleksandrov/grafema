<?php
namespace Grafema;

/**
 * JSON encoder and decoder.
 */
final class Json {

	/**
	 * Converts value to JSON format.
	 * Use $pretty for easier reading and clarity,
	 * $ascii for ASCII output and $html_safe for HTML escaping,
	 * $force_objects enforces the encoding of non-associateve arrays as objects.
	 *
	 * @throws JsonException
	 */
	public static function encode( mixed $value, bool $pretty = false, bool $ascii = false, bool $html_safe = false, bool $force_objects = false ): string {
		$flags = ( $ascii ? 0 : JSON_UNESCAPED_UNICODE )
			| ( $pretty ? JSON_PRETTY_PRINT : 0 )
			| ( $force_objects ? JSON_FORCE_OBJECT : 0 )
			| ( $html_safe ? JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG : 0 );

		$flags |= JSON_UNESCAPED_SLASHES
			| ( defined( 'JSON_PRESERVE_ZERO_FRACTION' ) ? JSON_PRESERVE_ZERO_FRACTION : 0 ); // since PHP 5.6.6 & PECL JSON-C 1.3.7

		$json  = json_encode( $value, $flags );
//		$error = json_last_error();
//		if ( $error ) {
//			throw new JsonException( json_last_error_msg(), $error );
//		}

		return $json;
	}


	/**
	 * Parses JSON to PHP value. The $force_arrays enforces the decoding of objects as arrays.
	 *
	 * @throws JsonException
	 */
	public static function decode( string $json, bool $force_arrays = false ): mixed {
		$flags  = $force_arrays ? JSON_OBJECT_AS_ARRAY : 0;
		$flags |= JSON_BIGINT_AS_STRING;

		$value = json_decode( $json, flags: $flags );
		$error = json_last_error();

		if ( $error === JSON_ERROR_NONE ) {
			return $value;
		}

		return $json;
	}
}
