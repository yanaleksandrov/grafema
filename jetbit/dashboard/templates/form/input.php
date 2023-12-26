<?php
use Grafema\Esc;
use Grafema\I18n;
use Grafema\Helpers\Arr;

/**
 * Input field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/input.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $name, $value, $placeholder, $class, $reset, $before, $after, $instruction, $tooltip, $copy, $attributes, $conditions] = array_values(
	( new Grafema\Sanitizer() )->apply(
		$args,
		[
			'label'       => 'trim',
			'name'        => 'key',
			'value'       => 'attribute|trim',
			'placeholder' => 'trim',
			'class'       => 'class:df aic jcsb fw-600',
			'reset'       => 'bool:false',
			'before'      => 'trim',
			'after'       => 'trim',
			'instruction' => 'trim',
			'tooltip'     => 'trim|attribute',
			'copy'        => 'bool:false',
			'attributes'  => 'array',
			'conditions'  => 'array',
			'options'     => 'array',
		]
	)
);
?>
<div class="dg g-1">
	<label class="dg g-1">
		<?php if ( $label ) : ?>
			<span class="<?php echo $class; ?>"><?php Esc::html( $label ); ?></span>
		<?php endif; ?>
		<span class="field">
			<?php
			printf( '%s<input%s>%s', $before, Arr::toHtmlAtts( $attributes ), $after );
			if ( $copy ) :
				?>
				<i class="ph ph-copy" title="<?php Esc::attr( I18n::__( 'Copy' ) ); ?>" @click="$copy(<?php echo $name; ?>.value)"></i>
				<?php
			endif;
			if ( $tooltip ) :
				?>
				<i class="ph ph-info" x-tooltip.click.prevent="'<?php echo $tooltip; ?>'"></i>
				<?php
			endif;
			?>
		</span>
	</label>
	<?php if ( $instruction ) : ?>
		<div class="fs-13 t-muted lh-xs"><?php Esc::html( $instruction ); ?></div>
	<?php endif; ?>
</div>
