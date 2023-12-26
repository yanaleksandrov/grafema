<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Comments list.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/comments.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-filter">
	<form class="dg g-7 p-8" action="." method="get" autocomplete="off" x-sticky>
		<div class="dg g-1">
			<div class="fs-12 t-muted">Search comments</div>
			<input type="search" name="search" placeholder="e.g. image.png">
		</div>
		<div class="dg g-1">
			<div class="df aic fs-12 t-muted mb-1">
				<span>Types</span><span class="ml-auto">Select all</span>
			</div>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Comments</span><span class="badge ml-auto bg-sky-lt">56</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Reviews</span><span class="badge ml-auto bg-sky-lt">1245</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Support</span><span class="badge ml-auto bg-sky-lt">21</span>
			</label>
		</div>
		<div class="dg g-1">
			<div class="df aic fs-12 t-muted mb-1">
				<span>Comments</span><span class="ml-auto">Deselect all</span>
			</div>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Mine</span><span class="badge ml-auto bg-sky-lt">21</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Pending</span><span class="badge ml-auto bg-sky-lt">0</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Approved</span><span class="badge ml-auto bg-sky-lt">254</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Spam</span><span class="badge ml-auto bg-sky-lt">5</span>
			</label>
			<label class="df aic">
				<input type="checkbox" name="remember">
				<span>Trash</span><span class="badge ml-auto bg-sky-lt">3</span>
			</label>
		</div>
		<button type="submit" class="btn btn--outline">Find comments <i class="ph ph-magnifying-glass"></i></button>
	</form>
</div>
<div class="grafema-main">
	<?php
	View::part(
		'templates/table/header',
		[
			'title' => I18n::__( 'Comments' ),
		]
	);
?>
	<div class="p-0">
		<table class="table" x-data="table">
			<thead>
				<tr>
					<th class="cb"><input type="checkbox" x-bind="trigger"></th>
					<th></th>
					<th>Title</th>
					<th>Author</th>
					<th>Categories</th>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">JakeAlexandrovpetrenko</a></td>
					<td><a href="#">VeryLongCategoryOrTag</a>, <a href="#">WordPress</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">WordPress Team</a></td>
					<td><a href="#">—</td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Helen</a></td>
					<td><a href="#">WordPress</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
