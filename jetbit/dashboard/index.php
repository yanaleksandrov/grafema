<?php
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Is;
use Grafema\Option;
use Grafema\Part;
use Grafema\Sanitizer;
use Grafema\User;

/*
 * Remove the duplicate access to the console at two addresses:
 * "dashboard" and "dashboard/index", leave only the first one.
 *
 * @since 1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	$dashboard_url = trim( $_SERVER['SCRIPT_URI'] ?? '' );
	if ( $dashboard_url ) {
		header( 'Location: ' . $dashboard_url . 'profile' );
	}
	exit;
}

$slug       = Sanitizer::trim( $args['slug'] ?? '' );
$start_time = microtime( true );
// $randoms    = [
//	'price',
//	'height',
//	'width',
//	'length',
//	'dimension',
//	'test',
//	'last',
//	'hello',
//	'world',
//	'can',
//	'use',
//	'date',
//	'list',
//	'arrow',
//	'jetpack',
//	'allow',
//	'color',
//	'type',
// ];
// $fields     = [];
// $count      = 200000;
// $count      = 1;
// $post       = 1;
// for ( $i = 1; $i <= $count; $i++ ) {
//	$fields[] = [
//		'post'  => $post,
//		'name'  => $randoms[ array_rand( $randoms ) ],
//		'value' => $i,
//	];
//
//	if ( $post === 10000 ) {
//		$post = 0;
//	} else {
//		$post = $post + 1;
//	}
//
//	if ( $i === $count ) {
//
//	} else {
//
//	}
// }
// print_r( Field::get( 'pages', 3 ) );
// echo 'Time:  ' . number_format( ( microtime( true ) - $start_time ), 5 ) . " Seconds\n";
// exit;

// print_r(
//	Query::apply(
//		[
//			'type'           => [ 'pages', 'media' ],
//			'status'         => null,
//			'page'           => 1,
//			'per_page'       => 99,
//			'offset'         => 2,
//			'order'          => 'DESC',
//			'orderby'        => 'rand',
//			's'              => 'hello exception',
//			'sentence'       => false,
//			'boolean_mode'   => true,
//			'slug'           => 'hello-world',
//			'slug_strict'    => true,
//			'title'          => 'Title 6',
//			'status'         => 'draft',
//			'nicename'       => 'alexandrov',
//			'author__in'     => [ 2, 3, 4, 5 ],
//			'author__not_in' => [ 4 ],
//			'post__in'       => [ 2, 3, 4, 5 ],
//			'post__not_in'   => [ 4 ],
//			'parent__in'     => [ 2, 3, 4, 5 ],
//			'parent__not_in' => [ 4 ],
//			'discussion'     => 'closed',
//			'comments'       => [
//				'value'   => 8,
//				'compare' => '<',
//			],
//			'views'          => [
//				'value'   => 0,
//				'compare' => '>=',
//			],
//			'dates'          => [
//				'relation' => 'AND',
//				[
//					'human_date_time' => '-23 days',
//					'compare'         => '<=',
//				],
//				[
//					'column'  => 'modified',
//					'compare' => 'BETWEEN',
//					'year'    => [ 2015, 2016 ],
//				],
//			],
//			'fields'   => [
//				'relation' => 'AND',
//				[
//					'key'     => 'price',
//					'value'   => '32434',
//					'compare' => '<=',
//					'type'    => 'NUMERIC',
//				],
//				[
//					'key'     => 'price',
//					'value'   => '30006',
//					'compare' => '>=',
//				],
//			],
//		]
//	)
// );
// echo 'Time:  ' . number_format( ( microtime( true ) - $start_time ), 4 ) . " Seconds\n";
// exit;
?>
<!DOCTYPE html>
<html lang="<?php I18n::locale(); ?>">
<head>
	<meta charset="<?php Option::attr( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Menu</title>
	<?php Hook::apply( 'dashboard_dashboard_head' ); ?>
</head>
<body x-data="{query:false}">
	<?php if ( Is::installed() && User::logged() ) { ?>
		<div class="grafema">
			<div class="grafema-panel">
				<div class="grafema-sidebar">
					<img src="/dashboard/assets/images/logo.svg" width="28" height="28" alt="Grafema Logo">
					<?php Part::view( 'templates/menu-panel' ); ?>
					<div class="mt-auto df fdc aic">
						<div class="avatar avatar--sm avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=3)">
							<i class="badge bg-herbal" title="Online"></i>
						</div>
					</div>
				</div>
			</div>
			<div class="grafema-prime">
				<div class="grafema-bar">
					<ul class="menu">
						<li class="menu__item">
							<a class="menu__link" href="home.html"><i class="ph ph-house-line"></i> Grafema</a>
						</li>
						<li class="menu__item">
							<a class="menu__link" href="index.html"><i class="ph ph-clock-clockwise"></i> 0</a>
						</li>
						<li class="menu__item">
							<a class="menu__link" href="index.html"><i class="ph ph-chats"></i> 2</a>
						</li>
						<li class="menu__item parent">
							<a class="menu__link" href="page.html"><i class="ph ph-plus"></i> New</a>
							<ul class="menu__sub">
								<li class="menu__item">
									<a href="product.html">Page</a>
								</li>
							</ul>
						</li>
						<li class="menu__item" id="query" x-init="query = !query"></li>
					</ul>
				</div>

				<?php
				Part::view( 'templates/menu' );

				Part::view( 'templates/' . $slug );
				?>
			</div>
		</div>

		<div class="notice" x-data>
			<template x-for="(item, id) in $store.notice.items">
				<div class="notice__item" :class="item.classes()">
					<div class="notice__msg" x-html="item.message"></div>
					<div class="notice__close" x-show="item.closable" :style="`--anim:${item.anim}`" @click="$store.notice.close(id)"></div>
				</div>
			</template>
		</div>
	<?php } else { ?>
		<div class="df aic jcc p-8">
			<?php Part::view( 'templates/' . $slug ); ?>
		</div>
		<?php
	}

	/*
	 * Prints scripts or data before the closing body tag on the dashboard.
	 *
	 * @since 1.0.0
	 */
	Hook::apply( 'grafema_dashboard_footer' );
?>
</body>
</html>
