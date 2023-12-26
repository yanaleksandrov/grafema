<?php
use Grafema\I18n;
use Grafema\Url;

/*
 * Export website content page
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/export.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

Form::register(
	'jb-exporter',
	[
		'class'  => 'card card-border p-8 pt-0 g-8',
		'action' => '/api/v1/export-posts',
		'x-data' => '{posts:[]}',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 'title',
					'type'        => 'header',
					'class'       => 'pt-8 px-8 t-center',
					'label'       => I18n::__( 'Map fields for export' ),
					'instruction' => I18n::__( 'Select fields from your CSV file that you want to map to fields in the posts, or that you want to ignore during import' ),
				],
				[
					'name'        => 'types[]',
					'type'        => 'select',
					'label'       => I18n::__( 'Post types' ),
					'instruction' => I18n::__( 'Choose which types of posts you want to export' ),
					'value'       => 'pages',
					'attributes'  => [
						'x-select' => '{"showSearch":1}',
						'multiple' => true,
					],
					'options' => [
						'pages' => I18n::__( 'Pages' ),
						'media' => I18n::__( 'Media' ),
					],
				],
				[
					'name'        => 'format',
					'type'        => 'radio',
					'variation'   => 'described', // can be: simple, image, described
					'label'       => I18n::__( 'File format' ),
					'instruction' => I18n::__( 'Select the appropriate format for subsequent work' ),
					'value'       => 'csv',
					'width'       => 180,
					'options'     => [
						'csv' => [
							'image'   => Url::site( 'dashboard/assets/images/dashboard-light.svg' ),
							'title'   => I18n::__( 'CSV file' ),
							'content' => I18n::__( '25 GB of Storage' ),
							'hidden'  => I18n::__( 'Hello world!' ),
						],
						'json' => [
							'image'   => Url::site( 'dashboard/assets/images/dashboard-dark.svg' ),
							'title'   => I18n::__( 'JSON file' ),
							'content' => I18n::__( '35 GB of Storage' ),
							'hidden'  => I18n::__( 'This is hidden content!' ),
						],
					],
				],
				[
					'type'     => 'custom',
					'callback' => function () {
						ob_start();
						?>
						<button type="submit" class="btn btn--primary btn--lg"><?php I18n::e( 'Export posts' ); ?></button>
						<?php
						return ob_get_clean();
					},
				],
			]
		);
	}
);
?>
<div class="grafema-main p-8 bg-telegrey-lt t-dark">
	<div class="mw-600 m-auto">
		<?php echo Form::view( 'jb-exporter' ); ?>
	</div>
</div>
