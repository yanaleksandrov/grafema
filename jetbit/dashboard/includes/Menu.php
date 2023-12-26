<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
use Grafema\Tree;
use Grafema\I18n;

final class Menu
{
	use \Grafema\Patterns\Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		/*
		 * Register menu
		 *
		 * @since 1.0.0
		 */
		Tree::attach(
			'grafema-panel-menu',
			function ( $tree ) {
				$tree->addItems(
					[
						[
							'id'           => 'users',
							'url'          => 'users',
							'title'        => I18n::__( 'Users' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-users-three',
							'position'     => 600,
						],
						[
							'id'           => 'emails',
							'url'          => 'emails',
							'title'        => I18n::__( 'Emails' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-tray',
							'position'     => 700,
						],
						[
							'id'           => 'tasks',
							'url'          => 'tasks',
							'title'        => I18n::__( 'My plans and tasks' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-list-checks',
							'position'     => 800,
						],
						[
							'id'           => 'settings',
							'url'          => 'settings',
							'title'        => I18n::__( 'Settings' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-gear-six',
							'position'     => 900,
						],
					]
				);
			}
		);

		/*
		 * Register menu
		 *
		 * @since 1.0.0
		 */
		Tree::attach(
			'dashboard-main-menu',
			function ( $tree ) {
				$tree->addItems(
					[
						[
							'id'           => 'profile',
							'url'          => 'profile',
							'title'        => I18n::__( 'Profile' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-user-focus',
							'position'     => 0,
							'count'        => 5,
						],
						[
							'id'       => 'divider-content',
							'title'    => I18n::__( 'Content' ),
							'position' => 10,
						],
						[
							'id'           => 'dialogs',
							'url'          => 'comments',
							'title'        => I18n::__( 'Dialogs' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-chats',
							'position'     => 200,
						],
						[
							'id'           => 'comments',
							'url'          => 'comments',
							'title'        => I18n::__( 'Comments' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 0,
							'parent_id'    => 'dialogs',
						],
						[
							'id'           => 'chat',
							'url'          => 'chat',
							'title'        => I18n::__( 'Chat' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 10,
							'parent_id'    => 'dialogs',
						],
						[
							'id'       => 'divider-customization',
							'title'    => I18n::__( 'Customization' ),
							'position' => 300,
						],
						[
							'id'           => 'appearance',
							'url'          => 'themes',
							'title'        => I18n::__( 'Appearance' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-paint-bucket',
							'position'     => 400,
						],
						[
							'id'           => 'themes',
							'url'          => 'themes',
							'title'        => I18n::__( 'Themes' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 0,
							'parent_id'    => 'appearance',
						],
						[
							'id'           => 'plugins',
							'url'          => 'plugins',
							'title'        => I18n::__( 'Plugins' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-plug',
							'position'     => 500,
						],
						[
							'id'           => 'installed',
							'url'          => 'plugins',
							'title'        => I18n::__( 'Installed' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 0,
							'parent_id'    => 'plugins',
						],
						[
							'id'           => 'install',
							'url'          => 'plugins-install',
							'title'        => I18n::__( 'Add new' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 10,
							'parent_id'    => 'plugins',
						],
						[
							'id'           => 'tools',
							'url'          => 'tools',
							'title'        => I18n::__( 'Tools' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-hammer',
							'position'     => 700,
						],
						[
							'id'           => 'import',
							'url'          => 'import',
							'title'        => I18n::__( 'Import' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 10,
							'parent_id'    => 'tools',
						],
						[
							'id'           => 'export',
							'url'          => 'export',
							'title'        => I18n::__( 'Export' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 20,
							'parent_id'    => 'tools',
						],
					]
				);
			}
		);
	}
}
