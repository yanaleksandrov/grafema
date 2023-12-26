<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	define( 'GRFM_PATH', __DIR__ . '/' );
}

ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );

/**
 * Include required files: app configuration & bootstrap.
 *
 * @since 1.0.0
 */
$includes = ['config', 'autoloader', 'bootstrap'];

foreach ( $includes as $include ) {
	$include_path = sprintf( '%s%s.php', GRFM_PATH, $include );
	if ( file_exists( $include_path ) ) {
		require_once $include_path;
	}
}

// ready for test
// $count      = 10;
// $start_time = microtime( true );
// for ( $i = 1; $i <= $count; $i++ ) {
//	if ( $i === $count ) {
//
//	} else {
//
//	}
// }
// echo 'Time:  ' . number_format( ( microtime( true ) - $start_time ), 6 ) . " Seconds\n";
