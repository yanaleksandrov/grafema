<?php
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;

/*
 * Submit button
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/submit.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$name, $label, $attributes] = array_values(
	( new Sanitizer() )->apply(
		$args,
		[
			'name'       => 'key|camelcase',
			'label'      => 'html',
			'attributes' => 'array',
		]
	)
);
?>
<div class="dg g-1">
	<button type="submit"<?php echo Arr::toHtmlAtts( $attributes ); ?>><?php echo $label; ?></button>
</div>
