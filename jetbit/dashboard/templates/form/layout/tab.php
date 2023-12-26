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

[ $name, $property, $caption, $description, $content, $class_content ] = array_values(
	( new Sanitizer() )->apply(
		$args,
		[
			'name'          => 'key',
			'property'      => 'key:tab',
			'caption'       => 'trim',
			'description'   => 'trim',
			'content'       => 'trim',
			'class_content' => 'class',
		]
	)
);

if ( empty( $content ) ) {
	return;
}
?>

<!-- tab "<?php echo $name; ?>" start -->
<div class="tab__content <?php echo $class_content; ?>" x-show="<?php echo $property; ?> === '<?php echo $name; ?>'" x-cloak>
	<?php if ( $caption || $description ) : ?>
		<div class="dg mb-8">
			<?php if ( $caption ) : ?>
				<h4><?php echo $caption; ?></h4>
				<?php
			endif;
			if ( $description ) :
				?>
				<div class="t-muted fs-13 mt-1 mw-800"><?php echo $description; ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="dg g-8">
		<?php echo $content; ?>
	</div>
</div>
