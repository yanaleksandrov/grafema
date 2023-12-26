<?php
use Grafema\Esc;
use Grafema\Helpers\Arr;

/*
 * Radio buttons
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/radio.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$id          = trim( strval( $args['ID'] ?? '' ) );
$attributes  = [
	'type' => 'radio',
	'name' => $id,
] + ( $args['attributes'] ?? [] );
$variation   = trim( strval( $args['variation'] ?? 'simple' ) );
$class       = trim( strval( $args['class'] ?? 'dg g-1' ) );
$label       = trim( strval( $args['label'] ?? '' ) );
$tooltip     = trim( strval( $args['tooltip'] ?? '' ) );
$description = trim( strval( $args['description'] ?? '' ) );
$value       = trim( strval( $args['value'] ?? '' ) );
$width       = intval( $args['width'] ?? 200 );
$options     = $args['options'] ?? [];
?>
<div class="<?php echo $class; ?>">
	<div class="df aic jcsb fw-600"><?php Esc::html( $label ); ?></div>
	<div class="dg g-4" style="grid-template-columns: repeat(auto-fill, minmax(<?php Esc::attr( $width ); ?>px, 1fr))">
		<?php
		foreach ( $options as $v => $option ) :
			$image   = trim( strval( $option['image'] ?? '' ) );
			$title   = trim( strval( $option['title'] ?? '' ) );
			$content = trim( strval( $option['content'] ?? '' ) );
			$hidden  = trim( strval( $option['hidden'] ?? '' ) );

			$attributes['value'] = $v;

			if ( $v === $value ) {
				$attributes['checked'] = $v === $value;
			} else {
				unset( $attributes['checked'] );
			}
			?>
			<label class="radio radio--<?php Esc::attr( $variation ); ?>">
				<?php
				if ( $variation !== 'image' ) {
					printf( '<input%s>', Arr::toHtmlAtts( $attributes ) );
				}
				switch ( $variation ) {
					case 'image':
						?>
						<img src="<?php Esc::attr( $image ); ?>" alt="<?php Esc::attr( $title ); ?>">
						<?php
						printf( '<input%s>', Arr::toHtmlAtts( $attributes ) );
						break;
					case 'described':
						?>
						<div>
							<div class="fw-600"><?php echo $title; ?></div>
							<div class="fs-13 t-muted"><?php echo $content; ?></div>
							<div><?php echo $hidden; ?></div>
						</div>
						<?php
						break;
					default:
						?>
						<div><?php echo $title; ?></div>
						<?php
						break;
				}
				?>
			</label>
		<?php endforeach; ?>
	</div>
	<?php if ( $description ) : ?>
		<div class="fs-13 t-muted lh-xs"><?php Esc::html( $description ); ?></div>
	<?php endif; ?>
</div>
