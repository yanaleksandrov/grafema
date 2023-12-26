<?php
use Grafema\View;
use Grafema\I18n;

/**
 * Users list.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/users.php
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
			'title' => I18n::__( 'Users' ),
		]
	);
	?>
	<table class="table" x-data="table">
		<thead>
		<tr>
			<th class="is-fit"><input type="checkbox" x-bind="trigger"></th>
			<th class="img is-fit"></th>
			<th class="name">Name</th>
			<th class="role is-fit">Role</th>
			<th class="status is-fit">Last visit</th>
			<th class="actions is-fit"></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="cb is-fit"><input type="checkbox" name="post[]" x-bind="switcher"></td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=1)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Izabella Tabakova</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Admin</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=2)" title="Jakob Batallone is online"><span class="badge bg-herbal"></span></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Leslee Moss</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=3)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Mo Chun</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=4)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Briana Shae</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=5)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Myranda Phyllis</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=6)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Goldie Livy</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=7)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Cal Jaymes</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=8)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Nigella Innocent</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=9)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Charlie Korbin</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=10)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Brenton Darryl</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		<tr>
			<td class="cb is-fit">
				<input type="checkbox" name="post[]" x-bind="switcher">
			</td>
			<td class="img is-fit">
				<span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=11)"></span>
			</td>
			<td class="name">
				<a class="fs-15 fw-600" href="#">Deven Scottie</a>
				<div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
			</td>
			<td class="role is-fit">
				<span class="badge bg-sky-lt">Subscriber</span>
			</td>
			<td class="status is-fit">
				<span class="badge bg-dark-lt">3 days ago</span>
			</td>
			<td class="actions is-fit">
				<span class="btn btn--icon t-reddish"><i class="ph ph-trash"></i></span>
			</td>
		</tr>
		</tbody>
	</table>
</div>
