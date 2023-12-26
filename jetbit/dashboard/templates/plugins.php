<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

( new Tables\Plugins() )->render();
