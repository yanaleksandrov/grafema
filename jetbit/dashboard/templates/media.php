<?php
use Grafema\I18n;
use Grafema\Json;
use Grafema\Patterns;
use Grafema\Url;
use Grafema\View;
use Grafema\Query\Query;

/*
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/storage.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$media = Query::apply(
	[
		'type'     => 'media',
		'page'     => 1,
		'per_page' => 30,
	],
	function ( $posts ) {
		if ( ! is_array( $posts ) ) {
			return $posts;
		}

		foreach ( $posts as $i => $post ) {
			$thumbnail = Patterns\Registry::get( 'images.thumbnail' );

			if ( ! is_array( $post ) || ! is_array( $thumbnail ) ) {
				continue;
			}

			[$name, $mime, $width, $height] = array_values(
				( new Grafema\Sanitizer() )->apply(
					$thumbnail,
					[
						'name'   => 'text',
						'mime'   => 'mime',
						'width'  => 'absint',
						'height' => 'absint',
					]
				)
			);

			if ( ! $mime || ! $width || ! $height ) {
				continue;
			}

			$filepath          = sprintf( '%s%s%s', GRFM_UPLOADS, sprintf( 'i/%sx%s/', $width, $height ), $post['title'] ?? '' );
			$allowed_extension = [
				'image/jpeg' => IMAGETYPE_JPEG,
				'image/png'  => IMAGETYPE_PNG,
				'image/webp' => IMAGETYPE_WEBP,
			];
			if ( in_array( $mime, array_keys( $allowed_extension ), true ) ) {
				$extension = image_type_to_extension( $allowed_extension[$mime] );
				$filepath  = preg_replace( '/\.[^.]+$/', $extension, $filepath );
			}
			$posts[$i]['thumbnail'] = Url::fromPath( $filepath );
		}

		return str_replace( '"', "'", Json::encode( $posts ) );
	}
);
?>
<div class="grafema-filter">
	<?php echo Form::view( 'grafema-filter-posts' ); ?>
</div>
<div class="grafema-main" x-data="{showUploader: false, files: <?php echo $media; ?>}">
<?php
View::part(
	'templates/table/header',
	[
		'title' => I18n::__( 'Media' ),
	]
);
?>
	<div class="df aic p-8 sm:p-5">
		<div class="mr-2">
			<h4>Media Library</h4>
			<div class="t-muted fs-12 mw-600">data about all registered users</div>
		</div>
		<div class="ml-auto">
			<button class="btn btn--outline" @click="showUploader = !showUploader"><i class="ph ph-folder-simple-plus"></i> Add new file</button>
		</div>
	</div>
	<div class="dg g-3 p-8 sm:p-5 pt-0 pb-0" x-show="showUploader" x-cloak>
		<?php
	View::part(
		'templates/form/uploader',
		[
			'description' => I18n::__( 'Click to upload or drag & drop' ),
			'attributes'  => [
				'required' => false,
				'multiple' => true,
				'x-ref'    => 'uploader',
				'@change'  => '[...$refs.uploader.files].map(file => $ajax("upload/media").then(response => files.unshift(response[0])))',
			],
		]
	);
View::part(
	'templates/form/textarea',
	[
		'name'       => 'urls',
		'label'      => I18n::__( 'Or upload from URL' ),
		'tooltip'    => I18n::__( 'Each URL must be from a new line' ),
		'attributes' => [
			'required'    => false,
			'placeholder' => I18n::__( 'Input file URL(s)' ),
			'@change'     => '$ajax("grab/files").then(response => files = response)',
			'x-textarea'  => 99,
		],
	]
);
?>
	</div>

	<template x-if="files.length">
		<div class="storage">
			<template x-for="file in files">
				<div class="storage__item" @click="$modal.open('jb-add-post')">
					<img class="storage__image" :src="file.thumbnail" alt="" width="200" height="200">
					<div class="storage__meta">
						<div class="storage__data" x-text="file.size"></div>
					</div>
				</div>
			</template>
		</div>
	</template>
	<template x-if="!files.length">
		<?php
		View::part(
			'templates/states/undefined',
			[
				'title'       => I18n::__( 'Media files is not found' ),
				'description' => I18n::__( 'The files were not found, probably because you did not download them or they do not match the specified filter parameters' ),
			]
		);
?>
	</template>
</div>
