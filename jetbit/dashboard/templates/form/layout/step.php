<?php
use Grafema\Helpers\Arr;

/**
 * Form step
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/step.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$attributes = $args['attributes'] ?? [];
$content    = trim( strval( $args['content'] ?? '' ) );
$step       = trim( strval( $args['step'] ?? 1 ) );
?>
<!-- step <?php echo $step; ?> -->
<div <?php echo Arr::toHtmlAtts( $attributes ); ?>>
	<?php echo $content; ?>
</div>

