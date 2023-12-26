<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
use Grafema\I18n;

/**
 * Boilerplate plugin.
 *
 * @since 1.0.0
 */
class Boilerplate implements Grafema\Plugins\Skeleton
{
	public function manifest(): array
	{
		return [
			'name'         => I18n::__( 'Plugin Boilerplate' ),
			'description'  => I18n::__( 'Plugin Boilerplate Description' ),
			'author'       => 'Grafema Team',
			'email'        => '',
			'url'          => '',
			'license'      => 'GNU General Public License v3.0',
			'version'      => '1.0.0',
			'php'          => '8.2',
			'mysql'        => '5.7',
			'dependencies' => [],
		];
	}

	public function launch()
	{
		// plugin functionality
	}

	public function activate()
	{
		// do something when plugin is activated
	}

	public function deactivate()
	{
		// do something when plugin is deactivated
	}

	public function install()
	{
		// do something when plugin is installed
	}

	public function uninstall()
	{
		// do something when plugin is uninstalled
	}
}
