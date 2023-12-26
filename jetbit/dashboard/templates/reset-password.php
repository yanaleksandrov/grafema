<?php
/**
 * Reset user password form.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/reset-password.php
 *
 * @version     1.0.0
 */

use Grafema\I18n;
use Grafema\Url;

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="mw-400">
	<div class="mb-8 df jcc">
		<img src="/dashboard/assets/images/logo.svg" width="72" height="72" alt="Grafema CMS">
	</div>
	<?php echo Form::view( 'reset/password' ); ?>
	<div class="t-center t-muted mt-3">
		<?php
		printf(
			I18n::__( 'I remembered the password, %ssend%s me to the sign in page' ),
			'<a href="' . Url::site( 'dashboard/sign-in' ) . '">',
			'</a>'
		);
?>
	</div>
</div>
