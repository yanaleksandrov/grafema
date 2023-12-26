<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
namespace Grafema;

use Grafema\Helpers\Arr;

/**
 * A class for displaying various tree-like structures.
 * Use it for output a tree of menu items, comments, taxonomies & many more.
 *
 * @since 1.0.0
 */
class Tree
{
	use \Grafema\Patterns\Multiton;

	/**
	 * Tree structures list.
	 *
	 * @since 1.0.0
	 */
	public array $list;

	/**
	 * Tree structure name.
	 *
	 * @since 1.0.0
	 */
	public string $name;

	/**
	 * TODO: The maximum number of items to crawl.
	 *
	 * $depth = -1 means flatly display every element (including child elements).
	 * $depth = 0  means display all levels.
	 * $depth > 0  specifies the number of display levels.
	 *
	 * @since 1.0.0
	 */
	public int $depth = 99;

	/**
	 * Register new tree structure.
	 */
	public static function attach( string $name, callable $function = null ): void
	{
		$tree = self::init( $name );
		// TODO:: wrong escaping, use sanitize
		$name = Esc::html( $name, false );

		if ( empty( $tree->list[$name] ) ) {
			$tree->list[$name] = [];
		}

		$tree->name = $name;
		if ( is_callable( $function ) ) {
			$function( $tree );
		}
	}

	/**
	 * Output html of menu.
	 *
	 * @since 1.0.0
	 */
	public static function view( string $name, callable $function ): void
	{
		$tree  = self::init( $name );
		$items = $tree->parse( $tree->list[$name] ?? [] );

		if ( is_callable( $function ) ) {
			$function( $items, $tree );
		}
	}

	/**
	 * Add a top-level menu page.
	 * This function takes a capability that is used to determine whether
	 * a page is included in the menu or not.
	 *
	 * The function which is hooked in to handle the output of the page must check
	 * that the user has the required capability as well.
	 *
	 * @since 1.0.0
	 */
	public function addItem( array $item ): void
	{
		$item_id = trim( (string) ( $item['id'] ?? '' ) );
		if ( ! $item_id ) {
			new Errors( Debug::get_backtrace(), I18n::__( 'Tree item ID is required.' ) );
		}

		$item = array_replace(
			[
				'id'        => '',
				'position'  => 0,
				'parent_id' => '',
			],
			$item
		);

		$this->list[$this->name][] = $item;
	}

	/**
	 * Bulk add tree items.
	 */
	public function addItems( array $items ): void
	{
		foreach ( $items as $item ) {
			$this->addItem( $item );
		}
	}

	/**
	 * The method takes over the routine work of forming dependencies and sorting tree elements.
	 *
	 * Parses a one-dimensional array with elements and forms a multidimensional one, taking into account nesting.
	 * Sorts array elements in ascending order of the value of the `position` field.
	 *
	 * @param array       $elements  List of tree
	 * @param string|null $parent_id Parent ID
	 * @param int         $depth     depth of parsing
	 *
	 * @since  1.0.0
	 */
	public function parse( array $elements, ?string $parent_id = '', int $depth = 0 ): array
	{
		$tree = [];

		foreach ( $elements as $element ) {
			$element_id = trim( $element['id'] ?? '' );
			if ( $element['parent_id'] === $parent_id ) {
				$element['depth'] = $depth;

				$children = $this->parse( $elements, $element_id, $depth + 1 );

				if ( $children ) {
					$element['children'] = $children;
				}

				$tree[] = $element;
			}
		}

		return Arr::sort( $tree, 'position' );
	}

	/**
	 * Like native vsprintf, but accepts $args keys instead of order index.
	 * Both numeric and strings matching /[a-zA-Z0-9_-]+/ are allowed.
	 *
	 * Example: vsprintf( 'y = %y$d, x = %x$1.1f', [ 'x' => 1, 'y' => 2 ] )
	 * Result:  'y = 2, x = 1.0'
	 *
	 * $args also can be object, then it's properties are retrieved using get_object_vars().
	 * '%s' without argument name works fine too. Everything vsprintf() can do is supported.
	 */
	public function vsprintf( string $str, $args ): string
	{
		if ( is_object( $args ) ) {
			$args = get_object_vars( $args );
		} elseif ( ! is_array( $args ) ) {
			return '';
		}

		$map = array_flip( array_keys( $args ) );

		$new_str = preg_replace_callback(
			'/(^|[^%])%([a-zA-Z0-9_-]+)\$/',
			function ( $m ) use ( $map ) {
				return $m[1] . '%' . ( ( $map[$m[2]] ?? 0 ) + 1 ) . '$';
			},
			$str
		);

		return vsprintf( $new_str, $args );
	}
}
