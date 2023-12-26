<?php
use Grafema\Sanitizer;

/**
 * Table image cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/image.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

list( $key ) = array_values(
	( new Sanitizer() )->apply(
		$args['column'] ?? [],
		[
			'key' => 'key',
		]
	)
);
?>
<div class="<?php echo $key; ?>">
	<span class="avatar" :style="'background-image: url(https://placehold.jp/150x150.png)'"></span>
</div>
