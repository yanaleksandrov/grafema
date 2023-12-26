<?php
/**
 * 404 page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/404.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */

use Grafema\I18n;

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<div class="df fdc aic jcc t-center t-muted">
		<h1 class="fs-64">404</h1>
		<p><?php I18n::e( 'Oops! Page not found.' ); ?></p>
	</div>
</div>
