<?php
use Grafema\Helpers\Arr;
use Grafema\Esc;

/*
 * Media field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/media.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$uniqid, $type, $name, $label, $label_class, $class, $description, $attributes, $tooltip] = array_values(
	( new Grafema\Sanitizer() )->apply(
		$args ?? [],
		[
			'uniqid'      => 'key:' . substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyz' ), 0, 6 ),
			'type'        => 'key:text',
			'name'        => 'attribute|key',
			'label'       => 'trim',
			'label_class' => 'class:df aic jcsb fw-600',
			'class'       => 'class:dg g-1',
			'description' => 'trim',
			'attributes'  => 'array',
			'tooltip'     => 'trim|attribute',
		]
	)
);
?>
<div x-data="{<?php echo $name; ?>: []}" class="<?php echo $class; ?>">
	<div class="dg g-1" x-media>
		<?php if ( $label ) { ?>
			<span class="<?php echo $label_class; ?>"><?php Esc::html( $label ); ?></span>
		<?php } ?>
		<template x-for="(item, id) in <?php echo $name; ?>">
			<img class="" :src="item.url" x-init="console.log(item.url)" alt="" width="200" height="200">
		</template>
		<input<?php echo Arr::toHtmlAtts( $attributes ); ?>>
		<?php
		if ( $tooltip ) {
			?>
			<i class="ph ph-info" x-tooltip.click.prevent="'<?php Esc::attr( $tooltip ); ?>'"></i>
			<?php
		}
?>
	</div>
	<?php if ( $description ) { ?>
		<div class="fs-13 t-muted lh-xs"><?php Esc::html( $description ); ?></div>
	<?php } ?>
</div>
