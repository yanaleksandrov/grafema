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
 * Template class.
 *
 * @since 1.0.0
 */
class View
{
	public static function redirect( $location, $status = 302, $x_redirect_by = 'Grafema' )
	{
		/**
		 * Filters the redirect location.
		 *
		 * @since 2.1.0
		 *
		 * @param string $location the path or URL to redirect to
		 * @param int    $status   the HTTP response status code to use
		 */
		$location = Hook::apply( 'grafema_redirect', $location, $status );

		/**
		 * Filters the redirect HTTP response status code to use.
		 *
		 * @since 2.3.0
		 *
		 * @param int    $status   the HTTP response status code to use
		 * @param string $location the path or URL to redirect to
		 */
		$status = Hook::apply( 'grafema_redirect_status', $status, $location );

		if ( ! $location ) {
			return false;
		}

		if ( $status < 300 || 399 < $status ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'HTTP redirect status code must be a redirection code, 3xx.' ) );
		}

		/**
		 * Filters the X-Redirect-By header.
		 *
		 * Allows applications to identify themselves when they're doing a redirect.
		 *
		 * @since 5.1.0
		 *
		 * @param string $x_redirect_by the application doing the redirect
		 * @param int    $status        status code to use
		 * @param string $location      the path to redirect to
		 */
		$x_redirect_by = Hook::apply( 'x_redirect_by', $x_redirect_by, $status, $location );
		if ( is_string( $x_redirect_by ) ) {
			header( "X-Redirect-By: {$x_redirect_by}" );
		}

		header( "Location: {$location}", true, $status );

		return true;
	}

	/**
	 * Echo template.
	 *
	 * @since 1.0.0
	 */
	public static function output( string $template_file, array $args = [] )
	{
		if ( file_exists( $template_file ) ) {
			extract( $args, EXTR_SKIP );

			require $template_file;
		}
	}

	/**
	 * Include template.
	 *
	 * @since 1.0.0
	 */
	public static function include( string $template_file, array $args = [] )
	{
		ob_start();
		self::output( $template_file, $args );

		return ob_get_clean();
	}

	/**
	 * Load template
	 * Using: View::part( 'templates/content', [
	 *          'key' => 'value'
	 *        ] );.
	 *
	 * @since 1.0.0
	 */
	public static function part( string $template_file, array $args = [], $require_once = false )
	{
		if ( Is::dashboard() || Is::install() ) {
			$template_file = sprintf( '%s/dashboard/%s.php', dirname( __DIR__ ), $template_file );
		} else {
			$template_file = Sanitizer::path( GRFM_THEMES . ( $theme_domain ?? Option::get( 'theme' ) ) ) . $template_file;
		}

		if ( file_exists( $template_file ) ) {
			extract( $args, EXTR_SKIP );
			if ( $require_once ) {
				require_once $template_file;
			} else {
				require $template_file;
			}
		}
	}
}
