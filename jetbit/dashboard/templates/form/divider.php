<?php
/**
 * Form divider
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/divider.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$label = Sanitizer\Sanitizer::trim( $args['label'] ?? '' );
?>
<div class="card-hr"><?php echo $label; ?></div>
