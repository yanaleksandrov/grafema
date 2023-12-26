<?php
use Grafema\Sanitizer;

/**
 * Table raw text cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/text.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

list( $key, $cell, $title, $sortable, $filterable ) = array_values(
	( new Sanitizer() )->apply(
		$args['column'] ?? [],
		[
			'key'        => 'key',
			'cell'       => 'key',
			'title'      => 'trim',
			'sortable'   => 'bool',
			'filterable' => 'bool',
		]
	)
);
?>
<div class="<?php echo $key; ?>">
	<div x-text="item.<?php echo $key; ?>"></div>
</div>
