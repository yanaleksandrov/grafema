<?php
use Grafema\Esc;
use Grafema\Sanitizer;

/**
 * Progress bar
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/progress.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

list( $uniqid, $name, $label, $label_class, $class, $description, $max, $min, $value, $speed ) = array_values(
	( new Sanitizer() )->apply(
		$args,
		[
			'uniqid'      => 'key:' . substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyz' ), 0, 6 ),
			'name'        => 'attribute|key',
			'label'       => 'trim',
			'label_class' => 'class:df aic jcsb fw-600 mb-1',
			'class'       => 'class:dg g-1',
			'description' => 'trim',
			'max'         => 'absint',
			'min'         => 'absint:0',
			'value'       => 'absint',
			'speed'       => 'absint',
		]
	)
);
?>
<div class="<?php echo $class; ?>">
	<div class="<?php echo $label_class; ?>">
		<?php Esc::html( $label ); ?>
		<span class="ml-auto">Set limits</span>
	</div>
	<div class="progress" x-progress.<?php printf( '%d.%d.%d.%d', $max, $min, $value, $speed ); ?>ms></div>
	<?php if ( $description ) : ?>
		<div class="mt-1 fw-600 lh-xs"><?php Esc::html( $description ); ?></div>
	<?php endif; ?>
</div>
