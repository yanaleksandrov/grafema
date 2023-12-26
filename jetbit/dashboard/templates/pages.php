<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/pages.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

?>
<div class="grafema-main">
	<?php
	View::part(
		'templates/table/header',
		[
			'title' => I18n::__( 'Pages' ),
		]
	);

( new Tables\Pages() )->render();
?>
</div>

<div class="modal" id="jb-add-post" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
	<div class="modal__dialog modal__dialog--right" role="document">
		<div class="modal__content" @click.outside="$modal.close()">
			<div class="modal__header">
				<h6 class="modal__title t-muted fw-300">Page ID: <span class="t-dark fw-600">#123</span></h6>
				<i class="ph ph-copy" x-copy="123"></i>
				<button type="button" class="modal__close" @click="$modal.close()"></button>
			</div>
			<div class="modal__body bg-milky">
				<?php echo Form::view( 'jb-add-post' ); ?>
			</div>
			<div class="modal__footer bg-milky">
				<button type="button" class="btn btn--outline" @click="$modal.close()">Cancel</button>
				<button type="button" class="btn btn--primary" @click="$modal.close()">Publish</button>
			</div>
		</div>
	</div>
</div>
