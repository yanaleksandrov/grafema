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
 * General template tags that can go anywhere in a template.
 *
 * @since 1.0.0
 */
class Part
{
	use \Grafema\Patterns\Singleton;

	/**
	 * List of sources for include files.
	 *
	 * @param array $paths
	 *
	 * @since 1.0.0
	 */
	private static array $paths = [];

	public static function register( string $source, array $paths ): void
	{
		self::$paths[$source] = $paths;
	}

	/**
	 * Loads a template part into a template.
	 *
	 * Provides a simple mechanism to overload reusable sections of code.
	 * The template is included using require, not require_once, so you
	 * may include the same template part multiple times.
	 *
	 * TODO: add object cache
	 * TODO: include 404 error part if nothing found (possible no needs)
	 * TODO: potential problem, - if the same file override several different plugins, which one should I use?
	 *
	 * @since 1.0.0
	 *
	 * @param string $filepath the path to the view file
	 * @param array  $args     Optional. Additional arguments passed to the template. Default empty array.
	 */
	public static function view( string $filepath, array $args = [] ): void
	{
		$backtrace   = debug_backtrace();
		$source_path = Sanitizer::trim( $backtrace[0]['file'] ?? '' );

		foreach ( self::$paths as $key => $paths ) {
			$path = Sanitizer::path( GRFM_PATH . $key . DIRECTORY_SEPARATOR );

			if ( str_starts_with( $source_path, $path ) ) {
				foreach ( $paths as $find_path ) {
					$find_filepath = Sanitizer::path( sprintf( '%s%s%s.php', $find_path, DIRECTORY_SEPARATOR, $filepath ) );

					/**
					 * Override template file.
					 *
					 * @since 1.0.0
					 *
					 * @param string $find_filepath the application doing the redirect
					 */
					$find_filepath = Hook::apply( 'grafema_part_view', $find_filepath, $path, $paths );

					if ( file_exists( $find_filepath ) ) {
						extract( $args, EXTR_SKIP );
						require $find_filepath;
					}
				}
			}
		}
	}

	/**
	 * Get the output of a view file as a string.
	 *
	 * This method captures the output of a view file and returns it as a string.
	 *
	 * @param string $filepath The path to the view file
	 * @param array  $args     An optional array of arguments to pass to the view file
	 *
	 * @return string the output of the view file as a string
	 */
	public static function get( string $filepath, array $args = [] ): string
	{
		ob_start();
		self::view( $filepath, $args );

		return ob_get_clean();
	}
}
