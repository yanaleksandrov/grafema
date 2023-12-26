<?php
/**
 * Not found part.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/states/undefined.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
?>
<div class="m-auto t-center p-4 mw-320">
	<svg width="196" height="196" viewBox="0 0 196 196" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect width="196" height="196" rx="98" fill="#8E8E8E" fill-opacity=".07"/>
		<path opacity=".99" fill-rule="evenodd" clip-rule="evenodd" d="M63 69.01V98H53V74c0-3 2-5 5-4.99h5ZM132 78.01l6-.01c2 0 4 2 4 4v5h-10v-8.99Z" fill="#B3B8C2"/>
		<rect x="68" y="56" width="64" height="54" rx="1" fill="#E8ECED"/>
		<rect x="63" y="61" width="64" height="49" rx="1" fill="#fff"/>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M71 69h48v2H71v-2ZM71 79h48v2H71v-2ZM71 89h48v2H71v-2Z" fill="#E8ECED"/>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M142 87c5 0 4 6 4 6l-3.66 43.25c-.57 2.74-2.34 4.75-5.34 4.75H58c-3 0-4.95-2.27-5.28-5.32-.84-10.37-1.74-22.39-2.72-32.68 0 0-1-6 4-6h24c2 0 2.3-.89 3.13-1.53l6.93-7.41S89 87 90 87l52-.01Z" fill="#D3D9DD"/>
	</svg>
	<?php if ( $title ) { ?>
		<h4 class="mt-4"><?php echo $title; ?></h4>
		<?php
	}
	if ( $description ) {
		?>
		<p class="t-muted"><?php echo $description; ?></p>
	<?php } ?>
</div>
