<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Users;

use Grafema\Debug;
use Grafema\Errors;
use Grafema\I18n;
use Grafema\Option;

/**
 * Base class used to implement the API for user roles and their capabilities.
 *
 * The role option is simple, the structure is organized by role name that store
 * the name in value of the 'name' key. The capabilities are stored as an array
 * in the value of the 'capabilities' key. Example:
 * [
 *    'rolename' => [
 *       'name'         => 'rolename',
 *       'capabilities' => []
 *    ]
 * ]
 *
 * @since 1.0.0
 */
class Roles
{
	/**
	 * Object list of roles and capabilities.
	 *
	 * @since 1.0.0
	 *
	 * @var array[]
	 */
	private static array $roles = [];

	/**
	 * Option name for storing role list.
	 *
	 * @since 2.1.0
	 */
	private static string $option = 'users.roles';

	/**
	 * Add role name with list of capabilities.
	 *
	 * Updates the list of roles, if the role doesn't already exist.
	 *
	 * The capabilities are defined in the following format `array( 'read' => true );`
	 * To explicitly deny a role a capability you set the value for that capability to false.
	 *
	 * @since 1.0.0
	 *
	 * @param string $display_name role display name
	 * @param mixed  $capabilities List of capabilities keyed by the capability name, e.g. [ 'edit_posts', 'delete_posts' ].
	 *                             You can specify the ID of an existing role as the value.
	 *                             In this case, the capabilities of the specified role are copied to the new one.
	 */
	public static function add( string $role, string $display_name, $capabilities )
	{
		$roles = self::fetch();
		if ( empty( $role ) || isset( $roles[$role] ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Sorry, the role with this ID already exists.' ) );
		}

		if ( is_string( $capabilities ) ) {
			if ( isset( $roles[$capabilities] ) ) {
				$capabilities = $roles[$capabilities]['capabilities'];
			} else {
				return new Errors( Debug::get_backtrace(), I18n::__( 'You are trying to copy capabilities from a non exists role.' ) );
			}
		}

		self::$roles[$role] = [
			'name'         => $display_name,
			'capabilities' => $capabilities,
		];

		if ( self::$option ) {
			Option::update( self::$option, self::$roles );
		}

		return true;
	}

	/**
	 * Retrieve role object by name.
	 *
	 * @since 1.0.0
	 *
	 * @return object role object if found, null if the role does not exist
	 */
	public static function get( string $role )
	{
		$roles = self::fetch();
		if ( isset( $roles[$role] ) ) {
			return $roles[$role];
		}

		return null;
	}

	/**
	 * Remove role.
	 *
	 * @since 1.0.0
	 */
	public static function delete( string $role ): bool
	{
		$roles = self::fetch();
		if ( ! isset( $roles[$role] ) ) {
			return false;
		}

		unset( self::$roles[$role] );

		if ( self::$option ) {
			Option::update( self::$option, self::$roles );
		}

		return true;
	}

	/**
	 * Set capability to role.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $capability Single capability or capabilities array
	 */
	public static function set( string $role, $capability )
	{
		$roles = self::fetch();
		if ( ! isset( $roles[$role] ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'You are trying set capability for non exists role.' ) );
		}

		if ( is_array( $capability ) ) {
			self::$roles[$role]['capabilities'] = array_merge( $roles[$role]['capabilities'], $capability );
		} else {
			self::$roles[$role]['capabilities'][] = $capability;
		}

		if ( self::$option ) {
			Option::update( self::$option, self::$roles );
		}

		return true;
	}

	/**
	 * Remove capability from role.
	 *
	 * @return bool|Errors
	 *
	 * @since 1.0.0
	 */
	public static function unset( string $role, string $capability )
	{
		self::fetch();

		if ( ! isset( self::$roles[$role] ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'You are trying unset capability for non exists role.' ) );
		}

		unset( self::$roles[$role]['capabilities'][$capability] );
		if ( self::$option ) {
			Option::update( self::$option, self::$roles );
		}

		return true;
	}

	/**
	 * Whether role name is currently in the list of available roles.
	 *
	 * @since 1.0.0
	 */
	public static function exists( string $role ): bool
	{
		$roles = self::fetch();

		return isset( $roles[$role] );
	}

	/**
	 * Whether role name is currently in the list of available roles.
	 *
	 * @since 1.0.0
	 */
	public static function has_cap( string $role, $capabilities ): bool
	{
		$roles = self::fetch();
		$role  = $roles[$role] ?? [];
		if ( empty( $role ) ) {
			return false;
		}

		if ( is_scalar( $capabilities ) ) {
			$capabilities = [$capabilities];
		}

		return count( array_intersect( $capabilities, $role['capabilities'] ) ) > 0;
	}

	/**
	 * Get all roles.
	 *
	 * @since 1.0.0
	 */
	private static function fetch()
	{
		if ( empty( self::$roles ) && self::$option ) {
			self::$roles = Option::get( self::$option );
		}

		return self::$roles;
	}
}
