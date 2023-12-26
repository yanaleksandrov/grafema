<?php
namespace Grafema\Post;

use Grafema\Debug;
use Grafema\Errors;
use Grafema\I18n;
use Grafema\DB;
use Grafema\Helpers\Arr;

/**
 * Core Metadata API
 *
 * Functions for retrieving and manipulating fields of various Grafema object types.
 * Metadata for an object is a represented by a simple key-value pair.
 * Objects may contain multiple metadata entries that share the same key and differ only in their value.
 *
 * @since 1.0.0
 */
class Field {

	use \Grafema\Patterns\Multiton;

	/**
	 * List of posts and their meta fields.
	 *
	 * @since 1.0.0
	 */
	public array $fields = [];

	/**
	 * Fetches metadata for the given post of the specified type.
	 *
	 * @param string $type  Type of the post.
	 * @param int    $post  Post ID.
	 *
	 * @return array|Errors Array of metadata or an instance of Errors if the post type is invalid.
	 * @since 1.0.0
	 */
	public static function fetch( string $type, int $post ): Errors|array {
		if ( ! Type::exist( $type ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Invalid Post Type.' ) );
		}

		$type   = sprintf( '%s%s_fields', DB_PREFIX, $type );
		$fields = self::init( sprintf( '%s_%d', $type, $post ) );

		if ( $fields->fields ) {
			return $fields->fields;
		}

		$fields->fields = DB::select( $type, [ 'name', 'value' ], [ 'post[=]' => $post ] );

		return $fields->fields;
	}

	/**
	 * Adds a meta field to the given post.
	 * Post fields is called "Custom Fields" on the Administration Screen.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post       Post ID.
	 * @param string $field      Field name.
	 * @param mixed  $value      Metadata value. Must be serializable if non-scalar.
	 * @param bool   $unique     Optional. Whether the same key should not be added.
	 *                           Default false.
	 * @return Errors|int        Meta ID on success, false on failure.
	 */
	public static function add( string $type, int $post, string $field, $value, $unique = true ): Errors|int {
		if ( ! Type::exist( $type ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Invalid Post Type.' ) );
		}

		$fields = self::fetch( $type, $post );
		$value  = is_numeric( $value ) ? strval( $value ) : $value;
		$type   = sprintf( '%s%s_fields', DB_PREFIX, $type );

		if ( $unique ) {
			$exist_values = Arr::filter(
				$fields,
				[
					'name'  => $field,
					'value' => $value,
				]
			);

			if ( count( $exist_values ) > 0 ) {
				return count( $exist_values );
			}
		}

		// TODO: update 'fields' parameter & add hook
		return DB::insert(
			$type,
			[
				'post'  => $post,
				'name'  => $field,
				'value' => $value,
			]
		)->rowCount();
	}

	/**
	 * Bulk insert fields.
	 *
	 * @param  string $type
	 * @param  array  $fields
	 * @return Errors|int
	 */
	public static function import( string $type, array $fields ): Errors|int {
		if ( ! Type::exist( $type ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Invalid Post Type.' ) );
		}

		if ( $fields ) {
			$the_fields = [];
			foreach ( $fields as $field ) {
				if ( empty( $field['post'] ) || empty( $field['name'] ) || empty( $field['value'] ) ) {
					continue;
				}

				$the_fields[] = [
					'post'  => $field['post'],
					'name'  => $field['name'],
					'value' => $field['value'],
				];
			}

			if ( ! empty( $the_fields ) ) {
				$type = sprintf( '%s%s_fields', DB_PREFIX, $type );

				return DB::insert( $type, $the_fields )->rowCount();
			}
		}

		return false;
	}

	/**
	 * Retrieves a post meta field for the given post ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post     Post ID.
	 * @param string $field    Optional. The meta key to retrieve. By default, returns data for all keys. Default empty.
	 * @param bool   $single   Optional. Whether to return a single value.
	 *                         This parameter has no effect if $key is not specified.
	 *                         Default false.
	 * @return mixed           An array if $single is false. The value of the meta field
	 *                         if $single is true. False for an invalid $post.
	 */
	public static function get( string $type, int $post, string $field = '', bool $single = true ) {
		if ( ! Type::exist( $type ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Invalid Post Type.' ) );
		}

		$fields = self::fetch( $type, $post );

		if ( empty( $field ) ) {
			return $fields;
		} else {
			$fields = Arr::filter( $fields, [ 'name' => $field ] );
			if ( $single ) {
				$keys   = array_keys( $fields );
				$key    = reset( $keys );
				$fields = array_column( [ $fields[ $key ] ], 'value', 'name' );

				return $fields[ $field ] ?? '';
			}
		}
	}

	/**
	 * Updates a post meta field based on the given post ID.
	 *
	 * Use the `$old_value` parameter to differentiate between meta fields with the
	 * same key and post ID.
	 *
	 * If the meta field for the post does not exist, it will be added and its ID returned.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post    Post ID.
	 * @param string $field      Metadata key.
	 * @param mixed  $new_value  Metadata value. Must be serializable if non-scalar.
	 * @param mixed  $old_value  Optional. Previous value to check before updating.
	 *                           If specified, only update existing metadata entries with
	 *                           this value. Otherwise, update all entries. Default empty.
	 * @return Errors|int        Meta ID if the key didn't exist, true on successful update,
	 *                           false on failure or if the value passed to the function
	 *                           is the same as the one that is already in the database.
	 */
	public static function update( string $type, int $post, string $field, mixed $new_value, mixed $old_value = '' ): Errors|int {
		if ( ! Type::exist( $type ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Invalid Post Type.' ) );
		}

		$type = sprintf( '%s%s_fields', DB_PREFIX, $type );

		$args = [
			'post' => $post,
			'name' => $field,
		];
		if ( $old_value ) {
			$args['value[=]'] = $old_value;
		}

		// TODO: update 'fields' parameter & add hook
		return DB::update( $type, [ 'value' => $new_value ], $args )->rowCount();
	}

	/**
	 * Deletes a post meta field for the given post ID.
	 *
	 * You can match based on the key, or key and value. Removing based on key and
	 * value, will keep from removing duplicate metadata with the same key. It also
	 * allows removing all metadata matching the key, if needed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type
	 * @param int    $post   Post ID.
	 * @param string $field  Field name. If it is empty, then all fields of the post will be deleted.
	 * @param mixed  $value  Optional. Metadata value. If provided, rows will only be removed that match the value.
	 *                       Must be serializable if non-scalar. Default empty.
	 * @return Errors|int    Count of deleted rows if success, false on failure.
	 */
	public static function delete( string $type, int $post, string $field = '', mixed $value = '' ): Errors|int {
		if ( ! Type::exist( $type ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Invalid Post Type.' ) );
		}

		$type = sprintf( '%s%s_fields', DB_PREFIX, $type );

		$args = [ 'post' => $post ];
		if ( ! empty( $field ) ) {
			// TODO: update 'fields' parameter & add hook
			$args['name'] = $field;
		}

		if ( ! empty( $value ) ) {
			// TODO: update 'fields' parameter & add hook
			$args['value[=]'] = $value;
		}

		return DB::delete( $type, $args )->rowCount();
	}
}
