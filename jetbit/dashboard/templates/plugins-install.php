<?php
use Grafema\I18n;
use Grafema\Json;
use Grafema\View;

/*
 * Extensions list for install.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/plugins-install.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$plugins = [
	[
		'title'           => 'Classic Editor',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '2 months ago',
		'reviews'         => 23,
		'rating'          => 4,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'eCommerce',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/woocommerce/assets/icon-256x256.gif',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 5,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Wordfence Security – Firewall, Malware Scan, and Login Security',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wordfence/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Tutor LMS – eLearning and online course solution',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Contact Form by JBForms – Drag & Drop Form Builder for Grafema',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Contact Form by JBForms – Drag & Drop Form Builder for Grafema',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Contact Form by JBForms – Drag & Drop Form Builder for Grafema',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Contact Form by JBForms – Drag & Drop Form Builder for Grafema',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Contact Form by JBForms – Drag & Drop Form Builder for Grafema',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Contact Form by JBForms – Drag & Drop Form Builder for Grafema',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Contact Form by JBForms – Drag & Drop Form Builder for Grafema',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
	[
		'title'           => 'Contact Form by JBForms – Drag & Drop Form Builder for Grafema',
		'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
		'image'           => 'https://ps.w.org/wpforms-lite/assets/icon.svg',
		'author'          => 'Grafema Team',
		'author_link'     => 'https://grafema.com',
		'installed'       => true,
		'active'          => false,
		'installations'   => '5+ Million Installations',
		'updated'         => '4 months ago',
		'reviews'         => 139,
		'rating'          => 3,
		'grafema_version' => 1.0,
	],
];
$plugins = str_replace( '"', "'", Json::encode( $plugins ) );
?>
<div class="grafema-filter">
	<?php echo Form::view( 'grafema-filter-plugins' ); ?>
</div>
<div class="grafema-main">
	<?php
	View::part(
		'templates/table/header',
		[
			'title' => I18n::__( 'Add Plugins' ),
		]
	);
?>

	<!-- plugins list start -->
	<div class="plugin p-6 sm:p-5 dg g-5" x-data="{plugins: <?php echo $plugins; ?>}">
		<template x-for="plugin in plugins">
			<div class="card card-border card-hover jcsb">
				<div class="df aifs p-5 g-5">
					<div class="avatar avatar--md avatar--rounded" :style="`background-image: url(${plugin.image})`"></div>
					<div class="dg g-2 fs-13">
						<h4 class="fw-600 fs-16 df jcsb">
							<span x-text="plugin.title"></span>
							<span class="btn btn--sm bg-herbal-lt">Installed</span>
						</h4>
						<div class="t-muted lh-sm" x-text="plugin.description"></div>
						<div>by <a target="_blank" :href="`${plugin.author_link}`" x-text="plugin.author">Our Team</a></div>
					</div>
				</div>
				<div class="dg g-1 fs-13 p-5 pt-4 pb-4 bg-muted-lt">
					<div class="df jcsb">
						<span x-text="plugin.installations"></span> <span><strong>Last Updated</strong>: <span x-text="plugin.updated"></span></span>
					</div>
					<div class="df jcsb">
						<div class="df g-2">
							<span class="df aic g-1 t-melon">
								<template x-for="index in [1, 2, 3, 4, 5]">
									<i class="ph ph-star" :class="plugin.rating < index && 't-muted'"></i>
								</template>
							</span>
							<span x-text="`[${plugin.reviews}]`"></span>
						</div>
						<div><strong>Compatible</strong> with your JB version</div>
					</div>
				</div>
			</div>
		</template>
	</div>
	<!-- plugins list end -->
</div>
