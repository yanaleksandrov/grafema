<?php
use Grafema\I18n;

/*
 * Import posts from CSV file.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/import.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

Form::register(
	'import/posts',
	[
		'class'           => 'card card-border',
		'@submit.prevent' => '$ajax(\'import/posts\').then(response => {completed = response;$wizard.goNext()})',
		'x-data'          => '{fields:"",completed:""}',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'     => 'title',
					'type'     => 'custom',
					'callback' => function () {
						ob_start();
						?>
						<div class="progress" :style="'--grafema-progress:' + $wizard.progress().progress"></div>
						<div class="p-8 pt-7 pb-7 df aic jcsb">
							<span x-text="$wizard.current().title">Upload CSV file</span>
							<span class="t-muted">
								step <strong x-text="$wizard.progress().current">1</strong> from <strong x-text="$wizard.progress().total">2</strong>
							</span>
						</div>
						<div class="card-hr"></div>
						<?php
						return ob_get_clean();
					},
				],
				[
					'type'       => 'step',
					'attributes' => [
						'class'          => 'pl-8 pr-8',
						'x-wizard:step'  => 'fields.trim()',
						'x-wizard:title' => I18n::__( 'Upload CSV file' ),
					],
					'fields' => [
						[
							'name'        => 'title',
							'type'        => 'header',
							'class'       => 'p-8 t-center',
							'label'       => I18n::__( 'Import posts from a CSV file' ),
							'instruction' => I18n::__( 'This tool allows you to import (or merge) posts data to your website from a CSV or TXT file. Choose a file from your computer:' ),
						],
						[
							'name'        => 'uploader',
							'type'        => 'uploader',
							'instruction' => I18n::__( 'Click to upload or drag & drop' ),
							'attributes'  => [
								'accept'  => '.csv,.txt',
								'@change' => '$ajax("upload/file").then(response => {fields = response;$wizard.goNext()})',
							],
						],
					],
				],
				[
					'type'       => 'step',
					'attributes' => [
						'class'          => 'pl-8 pr-8',
						'x-cloak'        => true,
						'x-wizard:title' => I18n::__( 'Column mapping' ),
					],
					'fields' => [
						[
							'name'        => 'title',
							'type'        => 'header',
							'label'       => I18n::__( 'Map CSV fields to posts' ),
							'instruction' => I18n::__( 'Select fields from your CSV file that you want to map to fields in the posts, or that you want to ignore during import' ),
						],
						[
							'type'     => 'custom',
							'callback' => fn () => '<div class="dg g-6" x-html="fields"></div>',
						],
					],
				],
				[
					'type'       => 'step',
					'attributes' => [
						'class'          => 'pl-8 pr-8',
						'x-cloak'        => true,
						'x-wizard:title' => I18n::__( 'Import is completed' ),
					],
					'fields' => [
						[
							'type'     => 'custom',
							'callback' => fn () => '<div class="dg" x-html="completed"></div>',
						],
					],
				],
				[
					'type'     => 'custom',
					'callback' => function () {
						ob_start();
						?>

						<!-- buttons -->
						<div class="p-8 df jcsb g-2">
							<button type="button" class="btn btn--outline" :disabled="$wizard.cannotGoBack()" x-show="$wizard.isNotLast()" @click="$wizard.goBack()" disabled><?php I18n::e( 'Back' ); ?></button>
							<button type="button" class="btn btn--primary" :disabled="$wizard.cannotGoNext()" x-show="$wizard.isFirst()" @click="$wizard.goNext()" disabled><?php I18n::e( 'Continue' ); ?></button>
							<button type="submit" class="btn btn--primary" x-show="$wizard.isStep(1)" x-cloak><?php I18n::e( 'Run the importer' ); ?></button>
						</div>
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
		<?php echo Form::view( 'import/posts' ); ?>
	</div>
</div>
