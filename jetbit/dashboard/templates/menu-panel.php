<?php
use Grafema\Tree;

/*
 * Grafema dashboard menu.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/menu-panel.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

Tree::view(
	'grafema-panel-menu',
	$test = function ( $items, $tree ) use ( &$test ) {
		if ( empty( $items ) || ! is_array( $items ) ) {
			return false;
		}
		?>
		<ul class="panel">
			<?php
			foreach ( $items as $item ) {
				ob_start();
				?>
				<li class="panel__item">
					<a class="panel__link" x-tooltip.hover.right="'%title$s'" href="%url$s">
						<i class="%icon$s"></i>
					</a>
				</li>
				<?php
				echo $tree->vsprintf( ob_get_clean(), $item );
			}
		?>
		</ul>
		<?php
	}
);
