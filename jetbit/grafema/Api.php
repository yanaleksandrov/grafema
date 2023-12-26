<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

use Exception;
use Grafema\Csrf\Csrf;
use Grafema\Csrf\Exceptions\InvalidCsrfTokenException;
use Grafema\Csrf\Providers\NativeHttpOnlyCookieProvider;
use ReflectionClass;
use ReflectionException;

/**
 * @since 1.0.0
 */
final class Api
{
	public static array $resources = [];

	/**
	 * @param string $path Path to controls with endpoints
	 * @param string $root Root of the api
	 */
	public static function create( string $path, string $root ): void
	{
		self::scan( $path );

		$route = new Route();
		$route->mount( $root, function () use ( $route ) {
			$route->get( '/(.*)', fn ( $slug ) => self::run( $slug ) );
			$route->post( '/(.*)', fn ( $slug ) => self::run( $slug ) );
		} );
		$route->run();
	}

	/**
	 * @throws ReflectionException
	 */
	private static function run( $slug ): void
	{
		[$endpoint, $method] = explode( '/', $slug, 2 );

		$resource = self::$resources[$endpoint] ?? '';
		if ( empty( $resource ) ) {
			// TODO: return error
		}

		$method   = Sanitizer::camelcase( $method );
		$filepath = Sanitizer::path( $resource['filepath'] ?? '' );
		$class    = Sanitizer::pascalcase( $resource['class'] ?? '' );

		if ( ! file_exists( $filepath ) ) {
			// TODO: return error
		} else {
			require_once $filepath;
		}

		if ( ! method_exists( $class, $method ) ) {
			// TODO: return error
		}

		$provider = new NativeHttpOnlyCookieProvider();
		$csrf     = new Csrf( $provider );

		try {
			$csrf->check( 'token', $_COOKIE['grafema_token'] ?? '' );
		} catch ( InvalidCsrfTokenException $e ) {
			$data = [
				'status'    => 200,
				'benchmark' => Debug::timer( 'getall' ),
				'error'     => I18n::__( 'Ajax queries not allows without nonce!' ),
			];
			var_dump( $e->getMessage() );
			var_dump( $data );
			exit;
		}
		$csrf->generate( 'token' );

		$reflector   = new ReflectionClass( $class );
		$classMethod = $reflector->getMethod( $method );
		$data        = $classMethod->isStatic() ? $class::$method() : $class->{$method}();

		/**
		 * Interceptor for overriding the server response.
		 *
		 * @since 1.0.0
		 */
		$data = Hook::apply( 'grafema_api_response', $data, $slug );
		$data = Json::encode( $data );

		header( 'Content-Type: application/json' );
		exit( $data );
	}

	/**
	 * Extract all implements on Grafema\Api\Handler.
	 *
	 * Adds a plugin to the list of registered plugins.
	 *
	 * @param string $path Path to directory with API controllers
	 */
	private static function scan( string $path ): void
	{
		$files = array_diff( scandir( $path ), ['.', '..'] );

		foreach ( $files as $file ) {
			$filepath = $path . '/' . $file;

			if ( is_file( $filepath ) && pathinfo( $file, PATHINFO_EXTENSION ) === 'php' ) {
				$content = file_get_contents( $filepath );

				if ( preg_match( '/namespace\s+([^\s;]+)/', $content, $matches ) ) {
					$namespace = $matches[1] ?? '';
				}

				if ( preg_match( '/class\s+(\w+)\s+extends\s+\\\Grafema\\\Api\\\Handler/', $content, $match ) ) {
					$class = $match[1] ?? '';
				}

				if ( preg_match( '/\$endpoint\s*=\s*\'([^\']+)\'/', $content, $match ) ) {
					$endpoint = $match[1] ?? '';
				}

				if ( empty( $class ) || empty( $endpoint ) ) {
					continue;
				}

				self::$resources[$endpoint] = [
					'namespace' => $namespace ?? '',
					'class'     => sprintf( '%s%s', ! empty( $namespace ) ? $namespace . '\\' : '', $class ),
					'filepath'  => $filepath,
				];
			} elseif ( is_dir( $filepath ) ) {
				self::scan( $filepath );
			}
		}
	}
}
