<?php

namespace Grafema\App;

use DB\DB;
use Medoo\Medoo;

/**
 * The main class for working with the application system.
 *
 * @since 1.0.0
 */
class App {

	/**
	 * Defines a named constant.
	 *
	 * @since 1.0.0
	 */
	public function define( string $name, $value ): void {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Check application system
	 *
	 * @param string $parameter
	 * @param string|int $value
	 * @return bool
	 */
	public static function check( string $parameter, string|int $value ): bool {
		return match ( $parameter ) {
			'php'        => version_compare( $value, strval( phpversion() ), '<=' ),
			'mysql'      => version_compare( $value, strval( DB::version() ), '<=' ),
			'connection' => DB::check() instanceof Medoo,
			'memory'     => intval( ini_get( 'memory_limit' ) ) >= intval( $value ),
			default      => false,
		};
	}

	/**
	 * Check application extensions
	 *
	 * @param string $extension
	 * @return bool
	 */
	public static function has( string $extension ): bool {
		return extension_loaded( $extension );
	}
}
