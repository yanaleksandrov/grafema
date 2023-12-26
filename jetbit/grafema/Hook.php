<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

/**
 * The Hook Class.
 *
 * @since 1.0.0
 */
final class Hook
{
	/**
	 * Holds list of filters.
	 *
	 * @since 1.0.0
	 */
	public static array $filters = [];

	/**
	 * Merged filters.
	 *
	 * @since 1.0.0
	 */
	public static array $merged_filters = [];

	/**
	 * Holds the name of the current filter.
	 *
	 * @since 1.0.0
	 */
	public static array $current_filter = [];

	/**
	 * Hooks a function or method to a specific filter action.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $tag             the name of the filter to hook the $function_to_add to
	 * @param callable $function_to_add the name of the function to be called when the filter is applied
	 * @param int      $priority        Optional. Used to specify the order in which the functions associated with a particular action are executed ( default: 10 ). Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.
	 * @param int      $accepted_args   Optional. The number of arguments the function accept ( default 1 ).
	 *
	 * @return bool true
	 */
	public static function add( $tag, $function_to_add, $priority = 10, $accepted_args = 1 )
	{
		$idx                                  = self::_filter_build_unique_id( $tag, $function_to_add, $priority );
		self::$filters[$tag][$priority][$idx] = [
			'function'      => $function_to_add,
			'accepted_args' => $accepted_args,
		];
		unset( self::$merged_filters[$tag] );

		return true;
	}

	/**
	 * Removes a function from a specified filter hook.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $tag                the filter hook to which the function to be removed is hooked
	 * @param callable $function_to_remove the name of the function which should be removed
	 * @param int      $priority           Optional. The priority of the function ( default: 10 ).
	 *
	 * @return bool whether the function existed before it was removed
	 */
	public static function remove( $tag, $function_to_remove, $priority = 10 )
	{
		$function_to_remove = self::_filter_build_unique_id( $tag, $function_to_remove, $priority );

		$r = isset( self::$filters[$tag][$priority][$function_to_remove] );

		if ( $r === true ) {
			unset( self::$filters[$tag][$priority][$function_to_remove] );
			if ( empty( self::$filters[$tag][$priority] ) ) {
				unset( self::$filters[$tag][$priority] );
			}
			unset( self::$merged_filters[$tag] );
		}

		return $r;
	}

	/**
	 * Remove all the hooks from a filter.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag      the filter to remove hooks from
	 * @param int    $priority the priority number to remove
	 *
	 * @return bool true when finished
	 */
	public static function remove_all( $tag, $priority = false )
	{
		if ( isset( self::$filters[$tag] ) ) {
			if ( $priority !== false && isset( self::$filters[$tag][$priority] ) ) {
				unset( self::$filters[$tag][$priority] );
			} else {
				unset( self::$filters[$tag] );
			}
		}

		if ( isset( self::$merged_filters[$tag] ) ) {
			unset( self::$merged_filters[$tag] );
		}

		return true;
	}

	/**
	 * Check if any filter has been registered for a hook.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $tag               the name of the filter hook
	 * @param callable $function_to_check optional
	 *
	 * @return mixed If $function_to_check is omitted, returns boolean for whether the hook has anything registered.
	 *               When checking a specific function, the priority of that hook is returned, or false if the function is not attached.
	 *               When using the $function_to_check argument, this function may return a non-boolean value that evaluates to false
	 *               (e.g.) 0, so use the === operator for testing the return value.
	 */
	public static function has_function( $tag, $function_to_check = false )
	{
		$has = ! empty( self::$filters[$tag] );
		if ( $function_to_check === false || $has === false ) {
			return $has;
		}

		if ( ! $idx = self::_filter_build_unique_id( $tag, $function_to_check, false ) ) {
			return false;
		}

		foreach ( (array) array_keys( self::$filters[$tag] ) as $priority ) {
			if ( isset( self::$filters[$tag][$priority][$idx] ) ) {
				return $priority;
			}
		}

		return false;
	}

	/**
	 * Call the functions added to a hook.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag   the name of the hook
	 * @param mixed  $value the value on which the filters hooked to <tt>$tag</tt> are applied on
	 *
	 * @return mixed the filtered value after all hooked functions are applied to it
	 */
	public static function apply( string $tag, $value = null )
	{
		$args = [];
		// Do 'all' actions first
		if ( isset( self::$filters['all'] ) ) {
			self::$current_filter[] = $tag;
			$args                   = func_get_args();
			self::_call_all_hook( $args );
		}

		if ( ! isset( self::$filters[$tag] ) ) {
			if ( isset( self::$filters['all'] ) ) {
				array_pop( self::$current_filter );
			}

			return $value;
		}

		if ( ! isset( self::$filters['all'] ) ) {
			self::$current_filter[] = $tag;
		}

		// Sort
		if ( ! isset( self::$merged_filters[$tag] ) ) {
			ksort( self::$filters[$tag] );
			self::$merged_filters[$tag] = true;
		}

		reset( self::$filters[$tag] );

		if ( empty( $args ) ) {
			$args = func_get_args();
		}

		do {
			foreach ( (array) current( self::$filters[$tag] ) as $the_ ) {
				if ( ! is_null( $the_['function'] ) ) {
					$args[1] = $value;
					$value   = call_user_func_array( $the_['function'], array_slice( $args, 1, (int) $the_['accepted_args'] ) );
				}
			}
		} while ( next( self::$filters[$tag] ) !== false );

		array_pop( self::$current_filter );

		return $value;
	}

	/**
	 * Execute functions hooked on a specific filter hook, specifying arguments in an array.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag  the name of the hook
	 * @param array  $args The arguments supplied to the functions hooked to <tt>$tag</tt>
	 *
	 * @return mixed the filtered value after all hooked functions are applied to it
	 */
	public static function apply_ref_array( $tag, $args )
	{
		// Do 'all' actions first
		if ( isset( self::$filters['all'] ) ) {
			self::$current_filter[] = $tag;
			$all_args               = func_get_args();
			self::_call_all_hook( $all_args );
		}

		if ( ! isset( self::$filters[$tag] ) ) {
			if ( isset( self::$filters['all'] ) ) {
				array_pop( self::$current_filter );
			}

			return $args[0];
		}

		if ( ! isset( self::$filters['all'] ) ) {
			self::$current_filter[] = $tag;
		}

		// Sort
		if ( ! isset( self::$merged_filters[$tag] ) ) {
			ksort( self::$filters[$tag] );
			self::$merged_filters[$tag] = true;
		}

		reset( self::$filters[$tag] );

		do {
			foreach ( (array) current( self::$filters[$tag] ) as $the_ ) {
				if ( ! is_null( $the_['function'] ) ) {
					$args[0] = call_user_func_array( $the_['function'], array_slice( $args, 0, (int) $the_['accepted_args'] ) );
				}
			}
		} while ( next( self::$filters[$tag] ) !== false );

		array_pop( self::$current_filter );

		return $args[0];
	}

	/**
	 * Retrieve the name of the current filter or action.
	 *
	 * @since 1.0.0
	 *
	 * @return string name of the current hook
	 */
	public static function current()
	{
		return end( self::$current_filter );
	}

	/**
	 * Retrieve the name of a filter currently being processed.
	 *
	 * The function current_filter() only returns the most recent filter or action
	 * being executed. did_action() returns true once the action is initially
	 * processed. This function allows detection for any filter currently being
	 * executed ( despite not being the most recent filter to fire, in the case of
	 * hooks called from hook callbacks ) to be verified.
	 *
	 * @since 1.0.0.2
	 *
	 * @param string|null $filter Optional. Filter to check. Defaults to null, which checks if any filter is currently being run.
	 *
	 * @return bool Whether the filter is currently in the stack
	 */
	public static function doing_filter( $filter = null )
	{
		if ( $filter === null ) {
			return ! empty( self::$current_filter );
		}

		return in_array( $filter, self::$current_filter, true );
	}

	/**
	 * Build Unique ID for storage and retrieval.
	 *
	 * @param string   $tag      Used in counting how many hooks were applied
	 * @param callable $function Used for creating unique id
	 * @param bool|int $priority Used in counting how many hooks were applied. If === false and $function is an object reference, we return the unique id only if it already has one, false otherwise.
	 *
	 * @return bool|string unique ID for usage as array key or false if $priority === false and $function is an object reference, and it does not already have a unique id
	 */
	private static function _filter_build_unique_id( $tag, $function, $priority )
	{
		static $filter_id_count = 0;

		if ( is_string( $function ) ) {
			return $function;
		}

		if ( is_object( $function ) ) {
			$function = [$function, '']; // Closures are currently implemented as objects
		} else {
			$function = (array) $function;
		}

		if ( is_object( $function[0] ) ) {
			// Object Class Calling
			if ( function_exists( 'spl_object_hash' ) ) {
				return spl_object_hash( $function[0] );
			}
			$obj_idx = get_class( $function[0] );
			if ( ! isset( $function[0]->filter_id ) ) {
				if ( $priority === false ) {
					return false;
				}
				$obj_idx .= isset( self::$filters[$tag][$priority] ) ? count( (array) self::$filters[$tag][$priority] ) : $filter_id_count;
				$function[0]->filter_id = $filter_id_count;
				++$filter_id_count;
			} else {
				$obj_idx .= $function[0]->filter_id;
			}

			return $obj_idx;
		}
		if ( is_string( $function[0] ) ) {
			return $function[0] . $function[1];
		}
	}
}
