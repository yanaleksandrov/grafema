<?php
use Grafema\I18n;
use Grafema\View;
use Grafema\Sanitizer;

/**
 * Table header
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/header.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

list( $title ) = array_values(
	( new Sanitizer() )->apply(
		$args,
		[
			'title' => 'trim',
		]
	)
);
?>
<!-- table head start -->
<div class="table__header">
	<div class="df aic jcsb">
		<?php if ( $title ) : ?>
			<div class="df aic g-4 mr-2">
				<h4><?php echo $title; ?></h4>
			</div>
		<?php endif; ?>
		<div class="df aic g-1">
			<?php
			View::part(
				'templates/form/number',
				[
					'name'        => 'page',
					'label'       => '',
					'description' => '',
					'attributes'  => [
						'value' => 3,
					],
				]
			);
			?>
			<a href="/dashboard/plugins-install" class="btn btn--outline">Install <span class="badge badge--sm">+2k</span></a>
			<button class="btn btn--outline"><i class="ph ph-funnel-simple"></i> Filters <span class="badge badge--sm">8</span></button>
			<?php
			View::part(
				'templates/form/details',
				[
					'label'       => '<i class="ph ph-magic-wand"></i>' . I18n::__( 'Bulk actions' ),
					'instruction' => I18n::__( 'Test content' ),
					'class'       => 'btn btn--outline',
					'content'     => Form::view( 'items/actions' ),
				]
			);
			View::part(
				'templates/form/details',
				[
					'label'       => '<i class="ph ph-dots-three-outline-vertical"></i>',
					'instruction' => I18n::__( 'Test content' ),
					'class'       => 'btn btn--outline btn--icon',
					'content'     => Form::view( 'items/options' ),
				]
			);
			?>
		</div>
	</div>
</div>
