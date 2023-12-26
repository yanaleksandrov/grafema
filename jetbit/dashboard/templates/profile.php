<?php
use Grafema\I18n;
use Grafema\View;

/*
 * User profile page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/profile.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<div class="p-8 bg-telegrey-lt mw" style="padding-bottom: 4rem; margin-bottom: -2.6rem;">
		<?php
		View::part(
			'templates/form/image',
			[
				'type'        => 'image',
				'name'        => 'avatar',
				'label'       => I18n::__( 'Upload avatar' ),
				'label_class' => '',
				'class'       => '',
				'description' => I18n::__( 'Click to upload or drag & drop' ),
				'tooltip'     => I18n::__( 'This is tooltip' ),
				'attributes'  => [
					'required' => false,
					'@change'  => '[...$refs.uploader.files].map(file => $ajax("upload/media").then(response => files.unshift(response[0])))',
				],
			]
		);
?>
	</div>
	<?php echo Form::view( 'jb-profile' ); ?>
</div>
