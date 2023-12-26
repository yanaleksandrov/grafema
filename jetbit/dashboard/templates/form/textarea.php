<?php
use Grafema\Esc;
use Grafema\Helpers\Arr;
use Grafema\I18n;

/*
 * Textarea field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/textarea.php
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
<div class="dg g-1"<?php echo $conditions ? " x-show=\"{$conditions}\" x-cloak" : ''; ?>>
	<label class="dg g-1">
		<span class="<?php echo $class; ?>"><?php Esc::html( $label ); ?></span>
		<span class="field">
			<?php
			printf( '%s<textarea%s>%s</textarea>%s', $before, Arr::toHtmlAtts( $attributes ), $value, $after );
			if ( $copy ) {
				?>
				<i class="ph ph-copy" title="<?php Esc::attr( I18n::__( 'Copy' ) ); ?>" @click="$copy(<?php echo $name; ?>)"></i>
				<?php
			}
			if ( $tooltip ) {
				?>
				<i class="ph ph-info" x-tooltip.click.prevent="'<?php Esc::attr( $tooltip ); ?>'"></i>
				<?php
			}
			?>
		</span>
	</label>
	<?php if ( $instruction ) { ?>
		<div class="fs-13 t-muted lh-xs"><?php Esc::html( $instruction ); ?></div>
	<?php } ?>
</div>
