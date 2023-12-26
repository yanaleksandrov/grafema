<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

class Esc
{
	/**
	 * Escaping for html attributes.
	 *
	 * @param string $string Attribute
	 * @param bool   $echo   Output or return
	 *
	 * @since 1.0.0
	 */
	public static function attr( string $string, bool $echo = true ): string
	{
		$string = trim( htmlspecialchars( $string, ENT_QUOTES ) );
		if ( $echo ) {
			echo $string;
		}

		return $string;
	}

	/**
	 * Escaping for html blocks.
	 *
	 * @since 1.0.0
	 */
	public static function html( string $string, bool $echo = true ): string
	{
		$string = trim( htmlspecialchars( $string, ENT_QUOTES ) );
		if ( $echo ) {
			echo $string;
		}

		return $string;
	}

	/**
	 * Sanitizes a string into a slug, which can be used in URLs, user nickname or HTML attributes.
	 *
	 * By default, converts accent characters to ASCII characters and further
	 * limits the output to alphanumeric characters, underscore (_) and dash (-)
	 *
	 * If `$title` is empty and `$fallback_title` is set, the latter will be used.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string      the string to be sanitized
	 * @param string $replacement Whitespace replacement
	 *
	 * @return string the sanitized string
	 */
	public static function slug( string $string, string $replacement = '_' ): string
	{
		return trim( str_replace( ' ', $replacement, self::accents( $string ) ) );
	}

	/**
	 * Sanitize the value according to the column data type when inserting data into the database.
	 *
	 * @since 1.0.0
	 */
	public static function sql( string $value, string $type ): string
	{
		switch ( strtolower( $type ) ) {
			case 'int':
			case 'int unsigned':
			case 'year':
			case 'bigint':
			case 'bigint unsigned':
			case 'tinyint':
			case 'tinyint unsigned':
			case 'smallint':
			case 'smallint unsigned':
			case 'mediumint':
			case 'mediumint unsigned':
			case 'bool':
			case 'boolean':
				$filter = FILTER_SANITIZE_NUMBER_INT;
				break;
			case 'dec':
			case 'float':
			case 'fixed':
			case 'decimal':
			case 'numeric':
			case 'double':
			case 'real':
			case 'double precision':
				$filter = FILTER_SANITIZE_NUMBER_FLOAT;
				break;
			default:
				$filter = FILTER_SANITIZE_SPECIAL_CHARS;
				break;
		}

		return filter_var( trim( $value ), $filter, FILTER_NULL_ON_FAILURE );
	}

	/**
	 * Escapes url for use inside href attribute.
	 *
	 * @since 1.0.0
	 */
	public static function url( string $url ): string
	{
		if ( $url === '' ) {
			return $url;
		}

		$url = str_replace( ' ', '%20', ltrim( $url ) );
		$url = preg_replace( '|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', $url );

		if ( $url === '' ) {
			return $url;
		}

		if ( stripos( $url, 'mailto:' ) !== 0 ) {
			$url = str_replace( ['%0d', '%0a', '%0D', '%0A'], '', $url );
		}

		// normalize slashes
		$url = str_replace( ';//', '://', $url );

		if ( preg_match( '~^(?:(?:https?|ftp)://[^@]+(?:/.*)?|(?:mailto|tel|sms):.+|[/?#].*|[^:]+)$~Di', $url ) ) {
			return trim( $url );
		}

		return '';
	}

	/**
	 * Removes accent from strings.
	 * Esc::accents("ªºÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿØ");
	 *         echo: ooAAAAAACEEEEIIIINOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyyO.
	 */
	public static function accents( string $string, string $encoding = 'utf-8' ): string
	{
		if ( ! preg_match( '/[\x80-\xff]/', $string ) ) {
			return $string;
		}

		// converting accents in HTML entities
		$string = htmlentities( $string, ENT_NOQUOTES, $encoding );

		// replacing the HTML entities to extract the first letter
		// examples: "&ecute;" => "e", "&Ecute;" => "E", "à" => "a" ...
		$string = preg_replace(
			'#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml|caron|lig|rdf|rdm);#',
			'\1',
			$string
		);

		// replacing ligatures, e.g.: "œ" => "oe", "Æ" => "AE"
		$string = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $string );

		// removing the remaining bits
		return preg_replace( '#&[^;]+;#', '', $string );
	}
}
