<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
spl_autoload_register(
	function ( $class ) {
		$filename = str_replace( ['\\', '/Grafema/'], ['/', '/grafema/'], GRFM_PATH . sprintf( '%s.php', $class ) );

		// TODO: is so bad, fix it!
		// try to find class in dashboard if not founded in core
		if ( ! is_file( $filename ) ) {
			$filename = str_replace( '\\', '/', GRFM_PATH . sprintf( 'dashboard/includes/%s.php', $class ) );
		}

		if ( is_file( $filename ) ) {
			require_once $filename;
		}
	}
);
