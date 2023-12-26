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
<div class="<?php echo $key; ?>" x-data="{show: false}">
	<template x-for="(link, index) in item.<?php echo $key; ?>.slice(0, show ? item.<?php echo $key; ?>.length : 3)">
		<span class="table__link"><a :href="link.href" x-text="link.title"></a></span>
	</template>
	<span class="badge" x-text="`+${item.<?php echo $key; ?>.length - 3} more`" @click="show = !show" x-show="item.<?php echo $key; ?>.length > 3 && !show"></span>
</div>
