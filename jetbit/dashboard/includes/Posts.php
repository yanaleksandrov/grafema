<?php
use Query\Query;

/**
 *
 *
 * @package Grafema
 */
class Posts {

	/**
	 *
	 *
	 * @return array
	 */
	public static function get(): array {
		return Query::apply(
			[
				'type'     => 'pages',
				'page'     => 1,
				'per_page' => 30,
			],
			function( $posts ) {
				if ( ! is_array( $posts ) ) {
					return $posts;
				}
				return str_replace( '"', "'", json_encode( $posts ) );
			}
		);
	}
}
