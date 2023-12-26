<?php
use Grafema\Helpers\Arr;

/*
 * Select field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/select.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $name, $value, $class, $reset, $instruction, $tooltip, $attributes, $conditions, $options] = array_values(
	( new Grafema\Sanitizer() )->apply(
		$args,
		[
			'label'       => 'trim',
			'name'        => 'key',
			'value'       => 'attribute',
			'class'       => 'class:df aic jcsb fw-600',
			'reset'       => 'bool:false',
			'instruction' => 'trim',
			'tooltip'     => 'attribute',
			'attributes'  => 'array',
			'conditions'  => 'array',
			'options'     => 'array',
		]
	)
);

$reset_value = sprintf( "'%s'", $value ?? '' );
?>
<div class="dg g-1">
	<span class="<?php echo $class; ?>">
		<?php
		Grafema\Esc::html( $label );
		if ( $reset ) {
			?>
			<span class="ml-auto t-reddish" @click="<?php echo $name; ?> = <?php echo $reset_value; ?>" x-show="<?php echo $name; ?> !== <?php echo $reset_value; ?>" x-cloak><?php I18n::e( 'Reset' ); ?></span>
		<?php } ?>
	</span>
	<select<?php echo Arr::toHtmlAtts( $attributes ); ?>>
		<?php
		$get_attributes = function ( $key, $value, $option ) {
			$attributes = [
				'value'    => trim( (string) $key ),
				'selected' => $key === $value,
			];

			if ( is_array( $option ) ) {
				unset( $option['content'] );

				foreach ( $option as $attribute => $value ) {
					$attributes['data-' . $attribute] = trim( (string) $value );
				}
			}

			return $attributes;
		};

		foreach ( $options as $option_key => $option ) {
			$optgroup_options = $option['options'] ?? [];
			if ( $optgroup_options ) {
				$label = Grafema\Sanitizer::trim( $option['label'] ?? '' );
				?>
				<optgroup label="<?php Grafema\Esc::attr( $label ); ?>">
					<?php
					foreach ( $optgroup_options as $i => $optgroup_option ) {
						$content = trim( is_scalar( $optgroup_option ) ? $optgroup_option : strval( $optgroup_option['content'] ?? '' ) );
						$atts    = $get_attributes( $i, $value, $optgroup_option );
						?>
						<option<?php echo Arr::toHtmlAtts( $atts ); ?>><?php Grafema\Esc::html( $content ); ?></option>
						<?php
					}
				?>
				</optgroup>
				<?php
			} else {
				$content = trim( is_scalar( $option ) ? $option : strval( $option['content'] ?? '' ) );
				$atts    = $get_attributes( $option_key, $value, $option );
				?>
				<option<?php echo Arr::toHtmlAtts( $atts ); ?>><?php Grafema\Esc::html( $content ); ?></option>
				<?php
			}
		}
		?>
	</select>
	<?php if ( $instruction ) { ?>
		<div class="fs-13 t-muted lh-xs"><?php echo $instruction; ?></div>
	<?php } ?>
</div>
