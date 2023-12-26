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
 * @since 1.0.0
 */
class Url
{
	/**
	 * Add GET parameters to a URL.
	 *
	 * @param string $url    The URL to add parameters to
	 * @param array  $params An associative array of parameter names and values to add to the URL
	 *
	 * @return string the modified URL with the new parameters
	 */
	public function add_query_params( string $url, array $params ): string
	{
		$url   = parse_url( $url );
		$query = [];
		if ( isset( $url['query'] ) ) {
			parse_str( $url['query'], $query );
		}

		$url['query'] = http_build_query(
			array_merge(
				$query,
				$params
			)
		);

		return implode(
			'',
			[
				$url['scheme'] ?? 'http',
				'://',
				$url['host'] ?? '',
				isset( $url['port'] ) ? ':' . $url['port'] : '',
				$url['path'] ?? '',
				isset( $url['query'] ) ? '?' . $url['query'] : '',
				isset( $url['fragment'] ) ? '#' . $url['fragment'] : '',
			]
		);
	}

	/**
	 * Retrieves the URL for a given site where Grafema application files are accessible.
	 * Returns the 'site_url' option with the appropriate protocol, 'https' if
	 * is_ssl() and 'http' otherwise. If `$scheme` is 'http' or 'https', `is_ssl()` is overridden.
	 *
	 * @since 1.0.0
	 *
	 * @param string      $path   Optional. Path relative to the site URL. Default empty.
	 * @param string|null $scheme Optional. Scheme to give the site URL context. Accepts
	 *                            'http', 'https', 'login', 'login_post', 'admin', or
	 *                            'relative'. Default null.
	 *
	 * @return string site URL link with optional path appended
	 */
	public static function site( string $path = '', string $scheme = null ): string
	{
		$url = 'https://cms.codyshop.ru'; // TODO:: resolve conflict when install Option::get( 'site.url' );
		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}

		/*
		 * Filters the site URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string      $url     The complete site URL including scheme and path.
		 * @param string      $path    Path relative to the site URL. Blank string if no path is specified.
		 * @param string|null $scheme  Scheme to give the site URL context. Accepts 'http', 'https', 'login',
		 *                             'login_post', 'admin', 'relative' or null.
		 */
		return Hook::apply( 'site_url', $url, $path, $scheme );
	}

	public static function sign_in()
	{
		return self::site( 'dashboard/sign-in' );
	}

	public static function sign_up()
	{
		return self::site( 'dashboard/sign-up' );
	}

	public static function reset_password()
	{
		return self::site( 'dashboard/reset-password' );
	}

	public static function install()
	{
		return self::site( 'dashboard/install' );
	}

	public static function installed()
	{
		return self::site( 'dashboard/installed' );
	}

	public static function dashboard()
	{
		return self::site( 'dashboard' );
	}

	/**
	 * Extract all URLs from text.
	 */
	public static function extract( $text ): array
	{
		$urls = [];
		if ( is_string( $text ) ) {
			preg_match_all( '/\bhttps?:\/\/\S+/', $text, $matches );

			$urls = (array) ( $matches[0] ?? [] );
		}

		return array_unique( $urls );
	}

	/**
	 * Return url from any path.
	 */
	public static function fromPath( $path ): string
	{
		return str_replace( dirname( __DIR__ ), self::site(), $path );
	}
}
