<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Plugins;

use Grafema\Debug;
use Grafema\Errors;
use Grafema\I18n;
use Grafema\Sanitizer;

/**
 * The main class for managing plugins.
 *
 * This class provides functionality for registering, activating, deactivating, installing, and uninstalling plugins.
 *
 * @since 1.0.0
 */
final class Manager
{
	use \Grafema\Patterns\Singleton;

	/**
	 * Full list of existing plugins.
	 */
	public array $plugins = [];

	/**
	 * Contains registered instances of plugin classes.
	 */
	public array $instances = [];

	/**
	 * Constructor for the Manager class.
	 *
	 * @param callable $callback a callback function used for registering plugins
	 */
	public function __construct( callable $callback )
	{
		call_user_func( $callback, $this );
	}

	/**
	 * Register a plugin.
	 *
	 * Adds a plugin to the list of registered plugins.
	 *
	 * @param array $paths Array of paths to the start files of plugins
	 */
	public function register( array $paths ): void
	{
		$this->extract( $paths );

		foreach ( $this->plugins as $class => $path ) {
			if ( class_exists( $class ) || ! file_exists( $path ) ) {
				continue;
			}

			require_once $path;

			$plugin = new $class();
			if ( $plugin instanceof Skeleton ) {
				$data    = $plugin->manifest();
				$missing = array_diff( ['name', 'description', 'version', 'php', 'mysql'], array_keys( $data ) );
				if ( ! empty( $missing ) ) {
					new Errors( Debug::get_backtrace(), sprintf( I18n::__( 'Plugin "%s" parameters "%s" are required' ), $class, implode( ' ', $missing ) ) );

					continue;
				}
				$this->instances[$class] = $plugin;
			}
		}
	}

	/**
	 * Extract all implements on Plugins\Skeleton.
	 *
	 * Adds a plugin to the list of registered plugins.
	 *
	 * @param array $paths Array of paths to the start files of plugins
	 */
	public function extract( array $paths ): void
	{
		foreach ( $paths as $path ) {
			$code = file_get_contents( $path );

			// try to find the implementation of the new plugin
			// preg_match( '/class\s+(\w+)(?:\s+extends\s+(\w+))?.*implements\s+Plugins\\\Skeleton/', $code, $matches );
			// TODO: add support final & extends
			preg_match( '/class\s+(\w+)\s+implements\s+Plugins\\\\Skeleton/', $code, $matches );

			$class = Sanitizer::pascalcase( $matches[1] ?? '' );
			if ( ! $class || class_exists( $class ) ) {
				// TODO: add error if plugin with same name is exist
				continue;
			}

			$this->plugins[$class] = $path;
		}
	}

	/**
	 * Launch all registered plugins.
	 */
	public function launch(): void
	{
		foreach ( $this->instances as $plugin ) {
			if ( $plugin instanceof Skeleton ) {
				$plugin->launch();
			}
		}
	}

	/**
	 * Activate all registered plugins.
	 *
	 * Calls the `activate()` method on each registered plugin, allowing them to perform necessary initialization tasks.
	 */
	public function activate(): void
	{
		foreach ( $this->instances as $plugin ) {
			if ( $plugin instanceof Skeleton ) {
				$plugin->activate();
			}
		}
	}

	/**
	 * Deactivate all registered plugins.
	 *
	 * Calls the `deactivate()` method on each registered plugin, allowing them to clean up resources or undo changes made during activation.
	 */
	public function deactivate(): void
	{
		foreach ( $this->instances as $plugin ) {
			if ( $plugin instanceof Skeleton ) {
				$plugin->deactivate();
			}
		}
	}

	/**
	 * Install all registered plugins.
	 *
	 * Calls the `install()` method on each registered plugin, allowing them to perform installation tasks.
	 */
	public function install(): void
	{
		foreach ( $this->instances as $plugin ) {
			if ( $plugin instanceof Skeleton ) {
				$plugin->install();
			}
		}
	}

	/**
	 * Uninstall all registered plugins.
	 *
	 * Calls the `uninstall()` method on each registered plugin, allowing them to clean up resources or remove associated assets.
	 */
	public function uninstall(): void
	{
		foreach ( $this->instances as $plugin ) {
			if ( $plugin instanceof Skeleton ) {
				$plugin->uninstall();
			}
		}
	}
}
