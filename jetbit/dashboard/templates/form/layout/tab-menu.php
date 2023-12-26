<?php
use Grafema\Sanitizer;

/**
 * Form tab markup
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/tab.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$fields = (array) ( $args['fields'] ?? [] );
$fields = array_filter( $fields, fn( $field ) => $field['type'] === 'tab' );

if ( count( $fields ) === 0 ) {
	return;
}
?>
<ul class="tab__nav" x-sticky>
	<?php
	foreach ( $fields as $field ) :
		[ $type, $name, $property, $label, $caption, $icon, $class_button ] = array_values(
			( new Sanitizer() )->apply(
				$field,
				[
					'type'         => 'key',
					'name'         => 'key',
					'property'     => 'key:tab',
					'label'        => 'trim',
					'caption'      => 'trim',
					'icon'         => 'trim',
					'class_button' => 'class',
				]
			)
		);
		?>
		<li class="tab__title <?php echo $class_button; ?>" @click="<?php echo $property; ?> = '<?php echo $name; ?>'" :class="{'active': <?php echo $property; ?> === '<?php echo $name; ?>'}">
			<div class="df aic g-2">
				<?php if ( $icon ) : ?>
					<i class="<?php echo $icon; ?>"></i>
					<?php
				endif;
				echo $label;
				?>
			</div>
			<?php if ( $caption ) : ?>
				<div class="t-muted fw-400 fs-13 pl-6"><?php echo $caption; ?></div>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
