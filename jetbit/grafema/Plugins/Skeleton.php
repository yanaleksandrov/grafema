<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Plugins;

/**
 * Interface Skeleton.
 *
 * The Skeleton defines the contract for a plugin in a Grafema CMS.
 * It serves as a blueprint for implementing plugins and ensures consistency across different plugins.
 *
 * @since 1.0.0
 */
interface Skeleton
{
	/**
	 * Data about plugin.
	 *
	 * @since 1.0.0
	 */
	public function manifest();

	/**
	 * Launch the plugin.
	 *
	 * @since 1.0.0
	 */
	public function launch();

	/**
	 * Activate the plugin.
	 *
	 * This method is responsible for activating the plugin.
	 * It typically performs necessary initialization tasks and sets up any required resources or configurations.
	 *
	 * @since 1.0.0
	 */
	public function activate();

	/**
	 * Deactivate the plugin.
	 *
	 * This method is responsible for deactivating the plugin.
	 * It is called when the plugin is being disabled or turned off.
	 * It usually involves cleaning up resources, unregistering hooks, or undoing any changes made during activation.
	 *
	 * @since 1.0.0
	 */
	public function deactivate();

	/**
	 * Install the plugin.
	 *
	 * This method is responsible for installing the plugin.
	 *
	 * @since 1.0.0
	 */
	public function install();

	/**
	 * Uninstall the plugin.
	 *
	 * This method is responsible for uninstalling the plugin.
	 * It is called when the plugin is being completely removed from the system.
	 * It typically involves removing any database tables, files, or other assets associated with the plugin.
	 *
	 * @since 1.0.0
	 */
	public function uninstall();
}
