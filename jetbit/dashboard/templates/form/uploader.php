<?php
use Grafema\Esc;
use Grafema\I18n;
use Grafema\Helpers\Arr;

/**
 * Files uploader.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/uploader.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$label       = trim( strval( $args['label'] ?? '' ) );
$description = trim( strval( $args['description'] ?? '' ) );
$attributes  = (array) ( $args['attributes'] ?? [] );
$max_size    = ini_get( 'upload_max_filesize' );
?>
<div class="uploader dg g-3">
	<label class="dg g-1">
		<?php if ( $label ) : ?>
			<span class="df aic jcsb fw-600"><?php Esc::html( $label ); ?></span>
		<?php endif; ?>
		<span class="uploader__container">
			<?php if ( $description ) : ?>
				<span class="fw-700"><?php Esc::html( $description ); ?></span>
			<?php endif; ?>
			<span class="fs-13 t-muted"><?php printf( I18n::__( 'Maximum upload file size is %s' ), $max_size ); ?></span>
		</span>
		<input type="file"<?php echo Arr::toHtmlAtts( $attributes ); ?>>
	</label>
</div>
