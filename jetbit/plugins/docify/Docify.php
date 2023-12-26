<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
use Docify\App\Finder;
use Docify\App\Parser;
use Grafema\Api;
use Grafema\Dir;
use Grafema\I18n;
use Grafema\Post;
use Grafema\Tree;

/**
 * Docify plugin.
 *
 * @since 1.0.0
 */
class Docify implements Grafema\Plugins\Skeleton
{
	public function manifest(): array
	{
		return [
			'name'         => I18n::__( 'Docify' ),
			'description'  => I18n::__( 'Simple way to create documentation for your plugins' ),
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
		spl_autoload_register(
			function ( $class ) {
				$parts = explode( '\\', $class );
				$parts = array_map(
					function ( $part, $index ) {
						return ( $index < 2 ) ? strtolower( $part ) : $part;
					},
					$parts,
					array_keys( $parts )
				);

				$classname = implode( '\\', $parts );
				$filepath  = str_replace( '\\', '/', GRFM_PLUGINS . sprintf( '%s.php', $classname ) );

				if ( is_file( $filepath ) ) {
					require_once $filepath;
				}
			}
		);

		//Api::create( sprintf( '%s%sapi', __DIR__, DIRECTORY_SEPARATOR ), '/api/v1/' );

		Post\Type::register(
			'documents',
			[
				'labels' => [
					'name'        => I18n::__( 'Documentation' ),
					'name_plural' => I18n::__( 'Documentation' ),
					'add'         => I18n::__( 'Add Document' ),
					'edit'        => I18n::__( 'Edit Document' ),
					'update'      => I18n::__( 'Update Document' ),
					'view'        => I18n::__( 'View Document' ),
					'view_plural' => I18n::__( 'View Documents' ),
					'search'      => I18n::__( 'Search Documents' ),
					'all_items'   => I18n::__( 'Documents' ),
					'published'   => I18n::__( 'Document published.' ),
					'scheduled'   => I18n::__( 'Document scheduled.' ),
					'updated'     => I18n::__( 'Document updated.' ),
				],
				'description'  => '',
				'public'       => true,
				'hierarchical' => true,
				'searchable'   => 0,
				'show_ui'      => true,
				'show_in_menu' => true,
				'show_in_bar'  => true,
				'position'     => 200,
				'menu_icon'    => 'ph ph-file-doc',
				'capabilities' => ['types_edit'],
				'supports'     => ['title', 'editor', 'thumbnail', 'fields'],
				'taxonomies'   => [],
				'can_export'   => true,
			]
		);

		Tree::attach(
			'dashboard-main-menu',
			function ( $tree ) {
				$tree->addItems(
					[
						[
							'id'           => 'docify',
							'url'          => 'docify',
							'title'        => I18n::__( 'Import project' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 100,
							'parent_id'    => 'documents',
						],
					]
				);
			}
		);

		/**
		 * Get all uploaded plugins.
		 *
		 * @since 1.0.0
		 */
		$plugins = Grafema\Plugins\Manager::init(
			function () {
				$paths = ( new Dir\Dir( GRFM_PLUGINS ) )->getFiles( '*.php', 1 );
				if ( ! $paths ) {
					return null;
				}
			}
		);
		if ( $plugins->plugins ) {
			$docblock = ( new Parser() )->run( '' );
			$classes  = ( new Finder() )->methods( $plugins->plugins );
			// echo '<pre>';
			// print_r( $files );
			// print_r( $classes );
			// echo '</pre>';
		}
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
