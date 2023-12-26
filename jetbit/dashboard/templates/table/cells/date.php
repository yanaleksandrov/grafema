<?php
use Grafema\Sanitizer;

/**
 * Publish date
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/date.php
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
	Published
	<div x-text="item.<?php echo $key; ?>"></div>
</div>
