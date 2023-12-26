<?php
use Grafema\Sanitizer;

/**
 * Comma-separated list of links
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/links.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

list( $key ) = array_values(
	( new Sanitizer() )->apply(
		$args['column'] ?? [],
		[
			'key' => 'key',
		]
	)
);
?>
<div class="<?php echo $key; ?>" x-init="console.log(item.<?php echo $key; ?>)">
	<template x-for="(badge, index) in item.<?php echo $key; ?>">
		<span class="badge badge--lg" :class="badge.class" x-text="badge.title"></span>
	</template>
</div>
