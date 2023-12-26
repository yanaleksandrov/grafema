<?php
use Grafema\Hook;
use Grafema\I18n;

/*
 * Grafema install wizard.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/install.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo locale_get_default(); ?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php I18n::e( 'Install Grafema' ); ?></title>
		<link rel="icon" href="/dashboard/assets/favicon/favicon.svg" type="image/svg+xml">
		<link rel="apple-touch-icon" sizes="180x180" href="/dashboard/assets/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/dashboard/assets/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/dashboard/assets/favicon/favicon-16x16.png">
		<?php

		/**
		 * Prints scripts or data before the closing body tag on the dashboard.
		 *
		 * @since 1.0.0
		 */
		Hook::apply( 'dashboard_dashboard_head' );
		?>
	</head>
	<body>
		<div class="df aic jcc p-8">
			<div class="mw-400">
				<div class="mb-8 df jcc">
					<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="none" viewBox="0 0 72 72">
						<g clip-path="url(#a)">
							<rect width="72" height="72" fill="#11131E" rx="36"/>
							<path fill="#fff" d="M16 61C1 61-8 51-8 36S3 11 19 11c15 0 25 7 25 19H32c0-6-4-9-13-9C8 21 4 25 4 36s4 14 14 14c12 0 15-1 15-7H17v-8h27v25H34V50h-1c-1 6-6 11-17 11Z"/>
							<circle cx="61" cy="53" r="8" fill="#0C62E5"/>
						</g>
						<defs>
							<clipPath id="a">
								<rect width="72" height="72" fill="#fff" rx="36"/>
							</clipPath>
						</defs>
					</svg>
				</div>
				<?php echo Form::view( 'install/grafema' ); ?>
			</div>
		</div>
		<?php

		/**
		 * Prints scripts or data before the closing body tag on the dashboard.
		 *
		 * @since 1.0.0
		 */
		Hook::apply( 'grafema_dashboard_footer' );
		?>
	</body>
</html>
