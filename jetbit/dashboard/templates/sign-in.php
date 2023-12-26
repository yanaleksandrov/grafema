<?php
use Grafema\I18n;
use Grafema\Url;

/*
 * User sing in page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/sign-in.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="mw-400">
	<div class="mb-8 df jcc">
		<img src="/dashboard/assets/images/logo.svg" width="72" height="72" alt="Grafema CMS">
	</div>
	<?php echo Form::view( 'user/sign-in' ); ?>
	<div class="fs-14 t-center t-muted mt-3">
		<?php
		printf(
			I18n::__( 'Don\'t have an account yet? %sSign up%s' ),
			'<a href="' . Url::site( 'dashboard/sign-up' ) . '">',
			'</a>'
		);
?>
	</div>
</div>
