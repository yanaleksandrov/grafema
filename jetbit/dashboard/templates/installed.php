<?php
/**
 * "Grafema is installed" information.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/installed.php
 *
 * @version     1.0.0
 */

use Grafema\I18n;

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

Form::register(
	'grafema/installed',
	[
		'class' => 'card card-border',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 'title',
					'type'        => 'header',
					'label'       => I18n::__( 'Grafema is installed' ),
					'instruction' => I18n::__( 'Grafema has been installed. Thank you, and enjoy!' ),
				],
				[
					'name'  => 'website-data',
					'type'  => 'divider',
					'label' => I18n::__( 'Credits for log in' ),
				],
				[
					'type'     => 'custom',
					'callback' => function () {
						ob_start();
						?>
						<div class="df">
							<span class="fw-500 mr-auto"><?php I18n::e( 'User login' ); ?></span>
							<span class="t-end"><?php User\User::current()->login ?? ''; ?></span>
						</div>
						<div class="df">
							<span class="fw-500 mr-auto"><?php I18n::e( 'Password' ); ?></span>
							<span class="t-end"><?php I18n::e( 'The password you have chosen' ); ?></span>
						</div>
						<div class="p-8 df jcsb g-2">
							<a href="/dashboard/" class="btn btn--primary"><?php I18n::e( 'Go to dashboard' ); ?></a>
						</div>
						<?php
						return ob_get_clean();
					},
				],
			]
		);
	}
);

echo Form::view( 'grafema/installed' );
