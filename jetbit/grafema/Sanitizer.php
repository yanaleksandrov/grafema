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
 * @since   1.0.0
 */
class Sanitizer
{
	/**
	 * Sanitized data.
	 */
	public array $data = [];

	/**
	 * Incoming fields and their values.
	 */
	protected array $fields = [];

	/**
	 * Sanitizer methods list.
	 */
	protected array $methods = [];

	/**
	 * List for custom methods for extend sanitize.
	 */
	protected array $extensions = [];

	/**
	 * Setup validation.
	 */
	public function __construct()
	{
		return $this;
	}

	/**
	 * Apply sanitizer.
	 */
	public function apply( array $data = [], array $methods = [] ): array
	{
		if ( ! empty( $data ) ) {
			$this->fields = $data;
		}

		if ( ! empty( $methods ) ) {
			$this->methods = $methods;
		}

		foreach ( $this->methods as $field => $methods_list ) {
			$methods = explode( '|', $methods_list );

			foreach ( $methods as $rules ) {
				[$method, $default_or_comparison_value] = array_pad( explode( ':', $rules, 2 ), 2, null );

				if ( ! is_callable( [$this, $method] ) ) {
					continue;
				}

				$incoming_value = $this->data[$field] ?? ( $this->fields[$field] ?? '' );
				$value          = call_user_func( [$this, $method], $incoming_value, $default_or_comparison_value );

				// set default value
				if ( $method !== 'bool' ) {
					if ( empty( $value ) ) {
						$value = call_user_func( [$this, $method], $default_or_comparison_value, null );
					}
				} elseif ( $incoming_value === '' ) {
					$value = call_user_func( [$this, $method], $default_or_comparison_value, null );
				}

				$this->data[$field] = $value;
			}
		}

		return $this->data;
	}

	/**
	 * Checks for the presence of an element in the array.
	 *
	 * @param mixed       $value            Value to change
	 * @param string|null $comparison_value Value to compare
	 */
	public static function exist( mixed $value, ?string $comparison_value ): string
	{
		$comparison = [];
		if ( $comparison_value ) {
			$comparison = array_map(
				'trim',
				explode( ',', $comparison_value )
			);
		}

		return in_array( (string) $value, $comparison, true ) ? (string) $value : '';
	}

	/**
	 * Leads a variable to a string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function string( mixed $value ): string
	{
		return (string) $value;
	}

	/**
	 * Leads a variable to an array.
	 *
	 * @param mixed $value Value to change
	 */
	public static function array( mixed $value ): array
	{
		return is_array( $value ) ? $value : ( ! empty( $value ) ? (array) $value : [] );
	}

	/**
	 * Leads a variable to number.
	 *
	 * @param mixed $value Value to change
	 */
	public static function int( mixed $value ): int
	{
		return (int) $value;
	}

	/**
	 * Leads a variable to float number.
	 *
	 * @param mixed $value Value to change
	 */
	public static function float( mixed $value ): float
	{
		return (float) $value;
	}

	/**
	 * Leads a variable to not negative number.
	 *
	 * @param mixed $value Value to change
	 */
	public static function absint( mixed $value ): int
	{
		return abs( (int) $value );
	}

	/**
	 * Leads a variable to boolean.
	 *
	 * @param mixed $value Value to change
	 */
	public static function bool( mixed $value ): bool
	{
		return ! in_array( $value, ['false', false, '0', 0, '', null], true );
	}

	/**
	 * Leads a variable to json string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function json( mixed $value ): string
	{
		return Json::encode( self::array( $value ) );
	}

	/**
	 * Sanitize html.
	 *
	 * @param mixed $value Value to change
	 */
	public static function html( mixed $value ): string
	{
		return ( new Kses() )->apply( $value ?? '' );
	}

	/**
	 * Sanitize attribute.
	 *
	 * @param mixed $value Value to change
	 */
	public static function attribute( mixed $value ): string
	{
		return trim( htmlspecialchars( (string) $value, ENT_QUOTES ) );
	}

	/**
	 * Value to price.
	 *
	 * @param mixed $value Value to change
	 */
	public static function price( mixed $value ): float
	{
		$sanitized = 0;
		if ( is_scalar( $value ) ) {
			$sanitized = self::trim( $value );
			$sanitized = preg_replace( '/[^0-9.,]/', '', $sanitized );
			$sanitized = str_replace( ',', '.', $sanitized );
		}

		return floatval( $sanitized );
	}

	/**
	 * Sanitizes a string from user input or from the database.
	 *
	 * - Checks for invalid UTF-8,
	 * - Converts single `<` characters to entities
	 * - Strips all tags
	 * - Removes line breaks, tabs, and extra whitespace
	 * - Strips percent-encoded characters
	 *
	 * @param mixed $value         Value to change
	 * @param bool  $keep_newlines
	 */
	public static function text( mixed $value, $keep_newlines = false ): string
	{
		$sanitized = '';
		if ( is_scalar( $value ) ) {
		}

		return $sanitized;
	}

	/**
	 * Sanitizes a multiline string from user input or from the database.
	 *
	 * The function is like sanitize_text_field(), but preserves
	 * new lines (\n) and other whitespace, which are legitimate
	 * input in textarea elements.
	 *
	 * @param mixed $value Value to change
	 */
	public static function textarea( mixed $value ): string
	{
		$sanitized = '';
		if ( is_scalar( $value ) ) {
		}

		return $sanitized;
	}

	/**
	 * Clears the string to use it as a key. The keys are used as different internal IDs.
	 * Removes everything from the string except a-z0-9_- and converts the string to lowercase.
	 */
	public static function key( mixed $value ): string
	{
		return preg_replace( '/[^a-z0-9_\-]/', '', self::lowercase( $value ) );
	}

	/**
	 * Remove whitespaces from string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function trim( mixed $value ): string
	{
		return is_array( $value ) || is_object( $value ) ? '' : trim( (string) $value );
	}

	/**
	 * To uppercase.
	 *
	 * @param mixed $value Value to change
	 */
	public static function uppercase( mixed $value ): string
	{
		return mb_strtoupper( self::trim( $value ) );
	}

	/**
	 * Lowercase string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function lowercase( mixed $value ): string
	{
		return mb_strtolower( self::trim( $value ) );
	}

	/**
	 * Capitalize string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function capitalize( mixed $value ): string
	{
		return ucwords( self::trim( $value ) );
	}

	/**
	 * Pascalcase string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function pascalcase( mixed $value ): string
	{
		$value = self::trim( $value );
		$value = str_replace( ['_', '-'], ' ', $value );
		$value = ucwords( $value );
		$value = str_replace( ' ', '', $value );

		return ucfirst( $value );
	}

	/**
	 * Camelcase string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function camelcase( mixed $value ): string
	{
		$value = self::trim( $value );
		$value = str_replace( ['_', '-'], ' ', $value );
		$value = ucwords( $value );
		$value = str_replace( ' ', '', $value );

		return lcfirst( $value );
	}

	/**
	 * Snakecase string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function snakecase( mixed $value ): string
	{
		$value = self::trim( $value );

		// replacing spaces with underscores
		$str = preg_replace( ['/\s+/', '-'], '_', $value );
		// insert underscores before each capital letter
		$str = preg_replace( '/(.)(?=[A-Z])/u', '$1_', $str );

		return strtolower( $str );
	}

	/**
	 * Kebabcase string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function kebabcase( mixed $value ): string
	{
		$value = self::trim( $value );

		// replacing spaces with hyphens
		$str = preg_replace( ['/\s+/', '_'], '-', $value );
		// insert underscores before each capital letter
		$str = preg_replace( '/(.)(?=[A-Z])/u', '$1-', $str );

		return strtolower( $str );
	}

	/**
	 * Flatcase string.
	 *
	 * @param mixed $value Value to change
	 */
	public static function flatcase( mixed $value ): string
	{
		$value = self::trim( $value );

		$str = preg_replace( ['-', '_', ' '], '', $value );

		return strtolower( $str );
	}

	/**
	 * Filters string for valid email characters.
	 *
	 * @param string $value
	 */
	public static function email( mixed $value ): string
	{
		return filter_var( self::trim( $value ), FILTER_SANITIZE_EMAIL );
	}

	/**
	 * Filters string for valid URL characters.
	 *
	 * @param mixed $value Value to change
	 */
	public static function url( mixed $value ): string
	{
		return filter_var( self::trim( $value ), FILTER_SANITIZE_URL );
	}

	/**
	 * Filters string for valid path syntax, with optional trailing slash.
	 *
	 * @param mixed $value Value to change
	 */
	public static function path( mixed $value ): string
	{
		// Remove whitespace, spaces, leading and trailing slashes
		$path = self::trim( preg_replace( '/\s+/', '', (string) $value ) );

		// Convert all invalid slashes to one single forward slash
		return strtr(
			$path,
			[
				'//'   => '/',
				'\\'   => '/',
				'\\\\' => '/',
			]
		);
	}

	/**
	 * Sanitizes an HTML classname to ensure it only contains valid characters.
	 *
	 * Strips the string down to A-Z,a-z,0-9,_,-. If this results in an empty
	 * string then it will return the alternative value supplied.
	 *
	 * @param mixed $value The classname to be sanitized
	 *
	 * @return string The sanitized value
	 */
	public static function class( mixed $value ): string
	{
		$sanitized = [];
		$classes   = explode( ' ', (string) $value );

		foreach ( $classes as $class ) {
			// Strip out any %-encoded octets.
			$class = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $class );

			// Limit to A-Z, a-z, 0-9, '_', '-'.
			$class = preg_replace( '/[^A-Za-z0-9_-]/', '', $class );

			$sanitized[] = self::trim( $class );
		}

		return implode( ' ', array_filter( $sanitized ) );
	}

	/**
	 * Normalize EOL characters and strip duplicate whitespace.
	 *
	 * @param mixed $value Value to change
	 *
	 * @return string the normalized string
	 *
	 * @since  1.0.0
	 */
	public static function whitespace( mixed $value ): string
	{
		return preg_replace( ['/\n+/', '/[ \t]+/'], ["\n", ' '], str_replace( "\r", "\n", self::trim( $value ) ) );
	}

	/**
	 * Properly strip all HTML tags including script and style.
	 *
	 * This differs from strip_tags() because it removes the contents of
	 * the `<script>` and `<style>` tags. E.g. `strip_tags( '<script>something</script>' )`
	 * will return 'something'. `Sanitize::tags()` will return ''
	 *
	 * @since  1.0.0
	 *
	 * @param string $string        string containing HTML tags
	 * @param bool   $remove_breaks Optional. Whether to remove left over line breaks and white space chars
	 *
	 * @return string the processed string
	 */
	public static function tags( string $string, bool $remove_breaks = false ): string
	{
		$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
		$string = strip_tags( $string );

		if ( $remove_breaks ) {
			$string = preg_replace( '/[\r\n\t ]+/', ' ', $string );
		}

		return self::trim( $string );
	}

	/**
	 * Sanitize a mime type.
	 *
	 * @since  1.0.0
	 *
	 * @param mixed $value mime type
	 *
	 * @return string sanitized mime type
	 */
	public static function mime( string $value ): string
	{
		return preg_replace( '/[^-+*.a-zA-Z0-9\/]/', '', self::trim( $value ) );
	}

	/**
	 * Sanitizes a hex color with or without a hash.
	 *
	 * @since  1.0.0
	 *
	 * @param string $color     color in HEX format
	 * @param bool   $with_hash return with hash or not
	 *
	 * @return string|null 3 or 6 digit hex color with or without #
	 */
	public static function hex( string $color, bool $with_hash = true ): ?string
	{
		$color = self::trim( $color );
		if ( ! str_contains( $color, '#' ) ) {
			$color = '#' . $color;
		}

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			if ( $with_hash ) {
				return $color;
			}

			return ltrim( $color, '#' );
		}

		return null;
	}

	/**
	 * Clearing the slug that is used as part of the url.
	 *
	 * @param string $slug    Value of slug
	 * @param string $context Context
	 */
	public static function slug( string $slug, string $context = 'display' ): string
	{
		$slug = strip_tags( $slug );
		// Preserve escaped octets.
		$slug = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $slug );
		// Remove percent signs that are not part of an octet.
		$slug = str_replace( '%', '', $slug );
		// Restore octets.
		$slug = preg_replace( '|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $slug );

		$slug = strtolower( $slug );

		if ( $context === 'save' ) {
			// Convert &nbsp, &ndash, and &mdash to hyphens.
			$slug = str_replace( ['%c2%a0', '%e2%80%93', '%e2%80%94'], '-', $slug );
			// Convert &nbsp, &ndash, and &mdash HTML entities to hyphens.
			$slug = str_replace( ['&nbsp;', '&#160;', '&ndash;', '&#8211;', '&mdash;', '&#8212;'], '-', $slug );
			// Convert forward slash to hyphen.
			$slug = str_replace( '/', '-', $slug );

			// Strip these characters entirely.
			$slug = str_replace(
				[
					// Soft hyphens.
					'%c2%ad',
					// &iexcl and &iquest.
					'%c2%a1',
					'%c2%bf',
					// Angle quotes.
					'%c2%ab',
					'%c2%bb',
					'%e2%80%b9',
					'%e2%80%ba',
					// Curly quotes.
					'%e2%80%98',
					'%e2%80%99',
					'%e2%80%9c',
					'%e2%80%9d',
					'%e2%80%9a',
					'%e2%80%9b',
					'%e2%80%9e',
					'%e2%80%9f',
					// Bullet.
					'%e2%80%a2',
					// &copy, &reg, &deg, &hellip, and &trade.
					'%c2%a9',
					'%c2%ae',
					'%c2%b0',
					'%e2%80%a6',
					'%e2%84%a2',
					// Acute accents.
					'%c2%b4',
					'%cb%8a',
					'%cc%81',
					'%cd%81',
					// Grave accent, macron, caron.
					'%cc%80',
					'%cc%84',
					'%cc%8c',
					// Non-visible characters that display without a width.
					'%e2%80%8b', // Zero width space.
					'%e2%80%8c', // Zero width non-joiner.
					'%e2%80%8d', // Zero width joiner.
					'%e2%80%8e', // Left-to-right mark.
					'%e2%80%8f', // Right-to-left mark.
					'%e2%80%aa', // Left-to-right embedding.
					'%e2%80%ab', // Right-to-left embedding.
					'%e2%80%ac', // Pop directional formatting.
					'%e2%80%ad', // Left-to-right override.
					'%e2%80%ae', // Right-to-left override.
					'%ef%bb%bf', // Byte order mark.
					'%ef%bf%bc', // Object replacement character.
				],
				'',
				$slug
			);

			// Convert non-visible characters that display with a width to hyphen.
			$slug = str_replace(
				[
					'%e2%80%80', // En quad.
					'%e2%80%81', // Em quad.
					'%e2%80%82', // En space.
					'%e2%80%83', // Em space.
					'%e2%80%84', // Three-per-em space.
					'%e2%80%85', // Four-per-em space.
					'%e2%80%86', // Six-per-em space.
					'%e2%80%87', // Figure space.
					'%e2%80%88', // Punctuation space.
					'%e2%80%89', // Thin space.
					'%e2%80%8a', // Hair space.
					'%e2%80%a8', // Line separator.
					'%e2%80%a9', // Paragraph separator.
					'%e2%80%af', // Narrow no-break space.
				],
				'-',
				$slug
			);

			// Convert &times to 'x'.
			$slug = str_replace( '%c3%97', 'x', $slug );
		}

		// Remove HTML entities.
		$slug = preg_replace( '/&.+?;/', '', $slug );
		$slug = str_replace( '.', '-', $slug );

		$slug = preg_replace( '/[^%a-z0-9 _-]/', '', $slug );
		$slug = preg_replace( '/\s+/', '-', $slug );
		$slug = preg_replace( '|-+|', '-', $slug );

		return self::trim( $slug, '-' );
	}
}
