<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Emails.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/emails.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main" x-data="{emails: []}">
	<template x-if="emails.length">
		<?php
		View::part(
			'templates/table/header',
			[
				'title' => I18n::__( 'Emails' ),
			]
		);
?>
		<div class="tables" x-data="table">
			<div class="tables__head">
				<div class="df aic">
					<div class="mr-2">
						<h4><?php I18n::e( 'Pages' ); ?></h4>
					</div>
					<div class="ml-auto df aic g-4">
						<div class="df aic g-1">
							15 items
							<span class="btn btn--outline btn--icon" disabled>
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" width="7" height="10" viewBox="0 0 7 10">
									<path stroke="#000" stroke-width="1.5" d="M6 1 2 5l4 4"/>
								</svg>
							</span>
							<label for="paged">
								<input type="number" id="paged" class="short reverse" name="paged" value="1" min="1" max="15" step="1">
							</label>
							<span>of 15</span>
							<span class="btn btn--outline btn--icon">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" width="7" height="10" viewBox="0 0 7 10">
									<path stroke="#000" stroke-width="1.5" d="m1 1 4 4-4 4"/>
								</svg>
							</span>
						</div>
						<button class="btn btn--outline" hidden @click="$modal.open('jb-add-post')"><i class="ph ph-folder-simple-plus"></i> <?php I18n::e( 'Add new' ); ?></button>
					</div>
				</div>
				<div class="tables__row" style="grid-template-columns: 1rem 2.5rem minmax(18rem, 1fr) repeat(3, minmax(6rem, 1fr))">
					<div class="cb">
						<input type="checkbox" x-bind="trigger">
					</div>
					<div></div>
					<div class="hover">
						<span class="df aic g-1">Title <i class="ph ph-sort-ascending hover--show"></i></span>
					</div>
					<div>Author</div>
					<div>Categories</div>
					<div>Date</div>
				</div>
			</div>
			<template x-for="email in emails">
				<div class="tables__row" style="grid-template-columns: 1rem 2.5rem minmax(18rem, 1fr) repeat(3, minmax(6rem, 1fr))">
					<div class="cb">
						<input type="checkbox" name="post[]" :value="post.ID" x-bind="switcher">
					</div>
					<div class="avatar" :style="'background-image: url(https://i.pravatar.cc/150?img=' + (post.ID - 50120) + ')'"></div>
					<div class="hover">
						<a href="#" class="fw-600" x-text="post.title" @click="$modal.open('jb-add-post')"></a>
						<div class="df aic g-2 mt-1 fs-13 hover--show">
							<a href="#">View</a> <a href="#">Duplicate</a> <a class="t-reddish" href="#">Trash</a>
						</div>
					</div>
					<div><a href="#">Yan Alexandrov</a></div>
					<div><a href="#">Phones</a>, <a href="#">Tablets</a></div>
					<div x-text="post.created">Nov 24, 2021 at 12:30</div>
				</div>
			</template>
		</div>
	</template>
	<template x-if="!emails.length">
		<?php
View::part(
	'templates/states/undefined',
	[
		'title'       => I18n::__( 'Emails templates is not found' ),
		'description' => I18n::__( 'Add <a href="/dashboard/emails" @click.prevent="$modal.open(\'jb-add-email\')">new email template</a> manually' ),
	]
);
?>
	</template>
</div>

<div class="modal" id="jb-add-email" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
	<div class="modal__dialog modal__dialog--right modal__dialog--xl" role="document">
		<div class="modal__content" @click.outside="$modal.close()">
			<div class="modal__header">
				<h6 class="modal__title t-muted fw-300">Page ID: <span class="t-dark fw-600">#123</span></h6>
				<i class="ph ph-copy" x-copy="123"></i>
				<button type="button" class="modal__close" @click="$modal.close()"></button>
			</div>
			<div class="modal__body modal__body--columns bg-muted-lt">
				<div class="modal__side">
					<?php echo Form::view( 'jb-add-email' ); ?>
				</div>
				<div class="df jcc">
					<?php
			View::output(
				GRFM_DASHBOARD . 'templates/mails/wrappers.php',
				[
					'body_template' => GRFM_DASHBOARD . 'templates/mails/reset-password.php',
				]
			);
?>
				</div>
			</div>
			<div class="modal__footer bg-milky">
				<button type="button" class="btn btn--outline" @click="$modal.close()">Cancel</button>
				<button type="button" class="btn btn--primary" @click="$dispatch('submit')">Publish</button>
			</div>
		</div>
	</div>
</div>
