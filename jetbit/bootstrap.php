<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
use Grafema\Csrf;
use Grafema\DB;
use Grafema\Debug;
use Grafema\Dir;
use Grafema\Hook;
use Grafema\Html;
use Grafema\I18n;
use Grafema\Is;
use Grafema\Part;
use Grafema\Plugins;
use Grafema\Route;
use Grafema\Url;
use Grafema\User;
use Grafema\View;
use Grafema\Post;

/**
 * Bootstrap file for setting the constants and loading the config.php file.
 * At this point, we initialize to autoload of the application core classes.
 */
class Bootstrap
{
	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$provider = new Csrf\Providers\NativeHttpOnlyCookieProvider();
		$csrf     = new Csrf\Csrf( $provider );
		$token    = $csrf->generate( 'token' );

		/*
		 * Check debug mode & run benchmark
		 *
		 * @since 1.0.0
		 */
		Debug::check();
		Debug::timer();

		/*
		 *
		 *
		 * @since 1.0.0
		 */
		Part::register(
			'dashboard',
			[
				GRFM_DASHBOARD,
				...(new Dir\Dir( GRFM_PLUGINS ))->getFolders(),
			]
		);

		/*
		 * Run the installer if Grafema is not installed.
		 *
		 * @since 1.0.0
		 */
		Install::init();

		/*
		 * Create a single entry point to the site.
		 *
		 * @since 1.0.0
		 */
		$this->create_htaccess();

		/*
		 * Check the required PHP and DataBase versions.
		 *
		 * @since 1.0.0
		 */
		$this->check_versions();

		/**
		 * Define auxiliary constants necessary for the application and make them available in any part.
		 *
		 * @since 1.0.0
		 */
		$route = new Route();
		$route->before(
			'GET|POST|PUT|DELETE',
			'/.*',
			function () {
				Constants::init();
			}
		);
		$route->run();

		/*
		 * Set current user and get data about it.
		 *
		 * @since 1.0.0
		 */
		User::current();

		/**
		 * Load and launch installed and active plugins.
		 *
		 * @since 1.0.0
		 */
		$plugins = Plugins\Manager::init( function ( $plugins ) {
			$paths = (new Dir\Dir( GRFM_PLUGINS ))->getFiles( '*.php', 1 );

			if ( ! $paths ) {
				return null;
			}
			$plugins->register( $paths );
		} );
		$plugins->launch();
		// $plugins->install();
		// $plugins->uninstall();
		// $plugins->activate();
		// $plugins->deactivate();

		/*
		 * Triggered after Grafema plugins is loaded.
		 *
		 * @since 1.0.0
		 */
		Hook::apply( 'grafema_plugins_loaded' );

		/*
		 * Set up default post types: "pages" & "media"
		 *
		 * @since 1.0.0
		 */
		Post\Type::register(
			'pages',
			[
				'labels' => [
					'name'        => I18n::__( 'Page' ),
					'name_plural' => I18n::__( 'Pages' ),
					'add'         => I18n::__( 'Add New' ),
					'edit'        => I18n::__( 'Edit Page' ),
					'update'      => I18n::__( 'Update Page' ),
					'view'        => I18n::__( 'View Page' ),
					'view_plural' => I18n::__( 'View Pages' ),
					'search'      => I18n::__( 'Search Pages' ),
					'all_items'   => I18n::__( 'All Pages' ),
					'published'   => I18n::__( 'Page published' ),
					'scheduled'   => I18n::__( 'Page scheduled' ),
					'updated'     => I18n::__( 'Page updated' ),
				],
				'description'  => '',
				'public'       => true,
				'hierarchical' => true,
				'searchable'   => true,
				'show_ui'      => true,
				'show_in_menu' => true,
				'show_in_bar'  => true,
				'position'     => 20,
				'menu_icon'    => 'ph ph-folders',
				'capabilities' => ['types_edit'],
				'supports'     => ['title', 'editor', 'thumbnail', 'fields'],
				'taxonomies'   => [],
				'can_export'   => true,
			]
		);

		Post\Type::register(
			'media',
			[
				'labels' => [
					'name'        => I18n::__( 'Storage' ),
					'name_plural' => I18n::__( 'Storage' ),
					'add'         => I18n::__( 'Upload' ),
					'edit'        => I18n::__( 'Edit Media' ),
					'update'      => I18n::__( 'Update Attachment' ),
					'view'        => I18n::__( 'View Attachment' ),
					'view_plural' => I18n::__( 'View Attachments' ),
					'search'      => I18n::__( 'Search Attachments' ),
					'all_items'   => I18n::__( 'Library' ),
					'published'   => I18n::__( 'Attachment published.' ),
					'scheduled'   => I18n::__( 'Attachment scheduled.' ),
					'updated'     => I18n::__( 'Attachment updated.' ),
				],
				'description'  => '',
				'public'       => true,
				'hierarchical' => true,
				'searchable'   => 0,
				'show_ui'      => true,
				'show_in_menu' => true,
				'show_in_bar'  => true,
				'position'     => 30,
				'menu_icon'    => 'ph ph-dropbox-logo',
				'capabilities' => ['types_edit'],
				'supports'     => ['title', 'editor', 'thumbnail', 'fields'],
				'taxonomies'   => [],
				'can_export'   => true,
			]
		);

		/*
		 * Include all dashboard functions.
		 *
		 * @since 1.0.0
		 */
		Dashboard::init();

		/*
		 * Grafema is fully loaded, but before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		Hook::apply( 'grafema_loaded' );

		/**
		 * Load private administrative panel.
		 * TODO: The dashboard must to be connected only if the current user is logged in & Is::ajax query.
		 *
		 * @since 1.0.0
		 */
		$dashboard = str_replace( GRFM_PATH, '/', GRFM_DASHBOARD );
		$route     = new Route();

		$route->get( sprintf( '%s(.*)', $dashboard ), function ( $slug ) use ( $route ) {
			http_response_code( 200 );

			/*
			 * Run the installer if Grafema is not installed.
			 *
			 * @since 1.0.0
			 */
			if ( $slug !== 'install' && ! Is::installed() ) {
				View::redirect(
					Url::install()
				);
				exit;
			}

			/*
			 * Out from dashboard if user is not logged.
			 * Also leave access for the registration and password recovery pages.
			 *
			 * @since 1.0.0
			 */
			if ( ! in_array( $slug, ['sign-in', 'sign-up', 'reset-password'], true )
				&& ! User::logged() && Is::installed()
			) {
				View::redirect(
					Url::sign_in()
				);
				exit;
			}

			/**
			 * Not allow some slugs for logged user, they are reserved.
			 *
			 * @since 1.0.0
			 */
			$black_list_slugs = ['install', 'sign-in', 'sign-up', 'reset-password'];

			if ( in_array( $slug, $black_list_slugs, true ) && User::logged() ) {
				View::redirect(
					Url::site( 'dashboard' )
				);
				exit;
			}

			/*
			 * The administrative panel also has a single entry point.
			 *
			 * @since 1.0.0
			 */
			ob_start();
			View::output(
				GRFM_DASHBOARD . 'index.php',
				[
					'route' => $route,
					'slug'  => $slug,
				]
			);
			echo ( new Html() )->beautify( ob_get_clean() );

			/*
			 * Grafema dashboard is fully loaded.
			 *
			 * @param string $slug Current page slug.
			 *
			 * @since 1.0.0
			 */
			Hook::apply( 'grafema_dashboard_loaded', $slug );

			exit;
		} );
		$route->run();
	}

	/**
	 * Check for the required PHP version, and the MySQL extension or a database drop-in.
	 * Dies if requirements are not met.
	 *
	 * @since  1.0.0
	 */
	private function check_versions(): void
	{
		$php_version   = phpversion();
		$mysql_version = DB::version();

		$php_version_is_compatible   = version_compare( GRFM_REQUIRED_PHP_VERSION, $php_version, '<=' );
		$mysql_version_is_compatible = version_compare( GRFM_REQUIRED_MYSQL_VERSION, $mysql_version, '<=' );

		if ( ! $php_version_is_compatible || ! $mysql_version_is_compatible ) {
			header( sprintf( '%s 500 Internal Server Error', $this->get_server_protocol() ), true, 500 );
			header( 'Content-Type: text/html; charset=utf-8' );
		}

		if ( ! $php_version_is_compatible ) {
			printf(
				I18n::__( 'Your server is running PHP version %1$s but Grafema %2$s requires at least %3$s.' ),
				$php_version,
				GRFM_VERSION,
				GRFM_REQUIRED_PHP_VERSION
			);
		}

		if ( ! $mysql_version_is_compatible ) {
			printf(
				I18n::__( 'Your server is running MySQL version %1$s but Grafema %2$s requires at least %3$s.' ),
				$mysql_version,
				GRFM_VERSION,
				GRFM_REQUIRED_MYSQL_VERSION
			);
		}

		if ( ! $php_version_is_compatible || ! $mysql_version_is_compatible ) {
			exit( 1 );
		}
	}

	/**
	 * Create .htaccess.
	 *
	 * @since 1.0.0
	 */
	private function create_htaccess(): void
	{
		if ( file_exists( GRFM_PATH . '.htaccess' ) ) {
			return;
		}
		// save as .htaccess
		file_put_contents(
			GRFM_PATH . '.htaccess',
			'<<<HTACCESS
			<IfModule mod_rewrite.c>
				RewriteEngine On
				RewriteBase /

				RewriteCond %{REQUEST_FILENAME} !-f
				RewriteCond %{REQUEST_FILENAME} !-d
				RewriteRule ^(.*)$ index.php?/$1 [L]
			</IfModule>
            HTACCESS'
		);
	}

	/**
	 * Return the HTTP protocol sent by the server.
	 *
	 * @since 1.0.0
	 *
	 * @return string The HTTP protocol. Default: HTTP/1.0.
	 */
	private function get_server_protocol(): string
	{
		$protocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
		if ( ! in_array( $protocol, ['HTTP/1.1', 'HTTP/2', 'HTTP/2.0', 'HTTP/3'], true ) ) {
			$protocol = 'HTTP/1.0';
		}

		return $protocol;
	}
}
new Bootstrap();
