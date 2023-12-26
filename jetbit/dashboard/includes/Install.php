<?php
use Grafema\DB;
use Grafema\Hook;
use Grafema\Route;
use Grafema\Is;
use Grafema\Json;
use Grafema\Debug;
use Grafema\I18n;
use Grafema\Option;
use Grafema\Asset;
use Grafema\View;
use Grafema\Url;
use Grafema\Html;
use Grafema\Users;

/**
 *
 *
 * @package Grafema
 */
final class Install extends Grafema\App\App {

	use \Grafema\Patterns\Singleton;

	/**
	 * Class constructor
	 *
	 * @return void|bool
	 * @since 1.0.0
	 */
	public function __construct() {

		/**
		 * Do nothing if Grafema is installed
		 *
		 * @since 1.0.0
		 */
		if ( Is::installed() ) {
			return false;
		}

		/**
		 * Define declare the necessary constants
		 *
		 * @since 1.0.0
		 */
		$this->define( 'GRFM_IS_INSTALL', true );

		/**
		 * Register new form for install wizard
		 *
		 * @since 1.0.0
		 */
		$this->form( 'install/grafema' );

		/**
		 * Override response
		 *
		 * @since 1.0.0
		 */
		Hook::add(
			'grafema_api_response',
			function( $json, $slug, $data ) {
				switch ( $slug ) {
					case 'install/test':
						$json = Json::encode(
							[
								'status'    => 200,
								'benchmark' => Debug::timer( 'getall' ),
								'data'      => [
									[
										'fragment' => $data,
										'target'   => 'body',
										'method'   => 'alpine',
									],
								],
							]
						);
						break;
					case 'sign/up':
						break;
				}
				return $json;
			},
			10,
			3
		);

		/**
		 * Register new routes
		 *
		 * @since 1.0.0
		 */
		$this->route();
	}

	/**
	 * Check the compliance of the server with the minimum requirements
	 *
	 * @return array
	 */
	public static function test(): array {
		$data   = [];
		$checks = [
			'php'        => '8.1',
			'mysql'      => '5.6',
			'pdo'        => false,
			'connection' => false,
			'curl'       => false,
			'mbstring'   => false,
			'gd'         => false,
			'memory'     => 128,
		];

		DB::init(
			[
				'database' => trim( strval( $_POST['name'] ?? '' ) ),
				'username' => trim( strval( $_POST['user'] ?? '' ) ),
				'password' => trim( strval( $_POST['password'] ?? '' ) ),
				'host'     => trim( strval( $_POST['host'] ?? '' ) ),
				'prefix'   => trim( strval( $_POST['prefix'] ?? '' ) ),
			]
		);

		foreach ( $checks as $check => $value ) {
			$data[ $check ] = match ( $check ) {
				'php',
				'mysql',
				'memory',
				'connection' => parent::check( $check, $value ),
				default      => parent::has( $check ),
			};
		}

		return $data;
	}

	/**
	 * Run Grafema installation
	 *
	 * @return void
	 * @throws JsonException
	 */
	public static function grafema(): void {
		$site        = $_REQUEST['site'] ?? [];
		$database    = $_REQUEST['db'] ?? [];
		$user        = $_REQUEST['user'] ?? [];
		$name        = trim( strval( $site['name'] ?? '' ) );
		$description = trim( strval( $site['description'] ?? '' ) );
		$login       = trim( strval( $user['login'] ?? '' ) );
		$email       = trim( strval( $user['email'] ?? '' ) );
		$password    = trim( strval( $user['password'] ?? '' ) );
		$protocol    = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== strtolower( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' );

		/**
		 * The check for connection to the database should have already been passed by this point.
		 * Therefore, just fill in the file config.php data and immediately connect it.
		 *
		 * @since 1.0.0
		 */
		$config = GRFM_PATH . 'config.php';
		if ( ! file_exists( $config ) ) {
			$file = new Grafema\File\File( GRFM_PATH . 'config-sample.php' );
			$file->copy( 'config' )->rewrite(
				[
					'db.name'     => trim( strval( $database['name'] ?? '' ) ),
					'db.user'     => trim( strval( $database['user'] ?? '' ) ),
					'db.password' => trim( strval( $database['password'] ?? '' ) ),
					'db.host'     => trim( strval( $database['host'] ?? 'localhost' ) ),
					'db.prefix'   => trim( strval( $database['prefix'] ?? 'grafema_' ) ),
				]
			);
		}

		require_once $config;

		DB::init();

		/**
		 * Creating the necessary tables in the database
		 *
		 * @since 1.0.0
		 */
		Grafema\Term::migrate();
		Grafema\Users\Users::migrate();
		Grafema\Options::migrate();
		Grafema\Comments::migrate();
		Grafema\Taxonomy::migrate();

		/**
		 * Fill same options
		 *
		 * @since 1.0.0
		 */
		Option::update(
			'site',
			[
				'url'     => $protocol . $_SERVER['SERVER_NAME'],
				'name'    => $name,
				'tagline' => $description,
			]
		);

		/**
		 * Add roles and users.
		 *
		 * @since 1.0.0
		 */
		Users\Roles::add(
			'admin',
			I18n::__( 'Administrator' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
				'other_types_publish',
				'other_types_edit',
				'other_types_delete',
				'private_types_publish',
				'private_types_edit',
				'private_types_delete',
				'manage_comments',
				'manage_options',
				'manage_update',
				'manage_import',
				'manage_export',
				'themes_install',
				'themes_switch',
				'themes_delete',
				'plugins_install',
				'plugins_activate',
				'plugins_delete',
				'users_create',
				'users_edit',
				'users_delete',
			]
		);

		Users\Roles::add(
			'editor',
			I18n::__( 'Editor' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
				'other_types_publish',
				'other_types_edit',
				'other_types_delete',
				'private_types_publish',
				'private_types_edit',
				'private_types_delete',
				'manage_comments',
			]
		);

		Users\Roles::add(
			'author',
			I18n::__( 'Author' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
			]
		);

		Users\Roles::add(
			'subscriber',
			I18n::__( 'Subscriber' ),
			[
				'read',
			]
		);

		$user = Grafema\User::add(
			[
				'login'    => $login,
				'email'    => $email,
				'password' => $password,
				'roles'    => [ 'admin' ],
			]
		);

		if ( $user instanceof Grafema\User ) {
			Grafema\User::login( $login, $password );

			echo Json::encode(
				[
					'status'    => 200,
					'benchmark' => Debug::timer( 'getall' ),
					'data'      => [
						[
							'delay'    => 100,
							'fragment' => View::include(
								GRFM_PATH . 'dashboard/templates/installed.php',
								[
									'title'       => I18n::__( 'Grafema is installed' ),
									'description' => I18n::__( 'Grafema has been installed. Thank you, and enjoy!' ),
								]
							),
							'method'   => 'update',
							'target'   => 'form.card',
						],
					],
				]
			);
		} else {
			echo Json::encode(
				[
					'status'    => 200,
					'benchmark' => Debug::timer( 'getall' ),
					'data'      => $user,
				]
			);
		}
	}

	private function route(): void {
		$route = new Route();
		$route->get(
			'(.*)',
			function( $slug ) use ( $route ) {

				/**
				 * Redirect to installer wizard if Grafema is not installed.
				 *
				 * @since 1.0.0
				 */
				if ( $slug !== 'install' ) {
					View::redirect( Url::site( 'install' ) );
					exit;
				}

				/**
				 * Run the installer wizard.
				 *
				 * @since 1.0.0
				 */
				$styles = [ 'main', 'phosphor' ];
				foreach ( $styles as $style ) {
					Asset::enqueue( $style, Url::site( 'dashboard/assets/css/' . $style . '.css' ) );
				}

				$scripts = [ 'index', 'alpine.min' ];
				foreach ( $scripts as $script ) {
					$data = [];
					if ( $script === 'index' ) {
						$data = [
							'data' => [
								'apiurl' => 'https://cms.codyshop.ru/api/v1/',
								'nonce'  => Grafema\User::addNonce(),
							],
						];
					}
					Asset::enqueue( $script, Url::site( 'dashboard/assets/js/' . $script . '.js' ), $data );
				}

				/**
				 * The administrative panel also has a single entry point.
				 *
				 * @since 1.0.0
				 */
				ob_start();
				View::output( GRFM_PATH . 'dashboard/install.php' );
				echo ( new Html() )->beautify( ob_get_clean() );

				die();
			}
		);
		$route->run();
	}

	/**
	 * Register form for first Grafema installation
	 *
	 * @param string $name
	 * @return void
	 */
	private function form( string $name ): void {
		Form::register(
			$name,
			[
				'class'           => 'card card-border',
				'@submit.prevent' => '$ajax("install/grafema")',
				'x-data'          => "{approved: {}, site: {name: ''}, db: {host: 'localhost', prefix: 'grafema_'}, user: {}}",
			],
			function( $form ) {
				$form->addFields(
					[
						[
							'type'       => 'step',
							'attributes' => [
								'x-wizard:step' => 'site.name.trim()',
							],
							'fields'     => [
								[
									'name'        => 'title',
									'type'        => 'header',
									'label'       => I18n::__( 'Welcome to Grafema!' ),
									'description' => I18n::__( 'This is installation wizard. Before start, you need to set some settings. Please fill the information about your website.' ),
								],
								[
									'name'  => 'website-data',
									'type'  => 'divider',
									'label' => I18n::__( 'Website data' ),
								],
								[
									'name'       => 'site[name]',
									'type'       => 'text',
									'label'      => I18n::__( 'Site name' ),
									'class'      => 'pl-8 pr-8 pt-6',
									'attributes' => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Example: My Blog' ),
										'x-autocomplete' => '',
										'x-model'        => 'site.name',
									],
								],
								[
									'name'        => 'site[description]',
									'type'        => 'text',
									'label'       => I18n::__( 'Site description' ),
									'description' => I18n::__( 'Don\'t worry, you can always change these settings later' ),
									'class'       => 'pl-8 pr-8 pt-6',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Example: Just Another Website' ),
										'x-autocomplete' => '',
									],
								],
							],
						],
						[
							'type'       => 'step',
							'attributes' => [
								'x-cloak'       => true,
								'x-wizard:step' => '[db.name, db.user, db.password, db.host, db.prefix].every(value => value !== undefined && value.trim())',
							],
							'fields'     => [
								[
									'name'        => 'title',
									'type'        => 'header',
									'label'       => I18n::__( 'Step 1: Database' ),
									'description' => I18n::__( 'Information about connecting to the database. If you are not sure about it, contact your hosting provider.' ),
								],
								[
									'name'  => 'credits',
									'type'  => 'divider',
									'label' => I18n::__( 'Database credits' ),
								],
								[
									'name'        => 'db[name]',
									'type'        => 'text',
									'label'       => I18n::__( 'Database name' ),
									'description' => I18n::__( 'Specify the name of the empty database' ),
									'class'       => 'pl-8 pr-8 pt-6',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'database_name' ),
										'x-autocomplete' => '',
										'x-model'        => 'db.name',
									],
								],
								[
									'name'        => 'db[user]',
									'type'        => 'text',
									'label'       => I18n::__( 'MySQL database user name' ),
									'description' => I18n::__( 'User of the all privileges in the database' ),
									'class'       => 'pl-8 pr-8 pt-6',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'user_name' ),
										'x-autocomplete' => '',
										'x-model'        => 'db.user',
									],
								],
								[
									'name'        => 'db[password]',
									'type'        => 'text',
									'label'       => I18n::__( 'MySQL password' ),
									'description' => I18n::__( 'Password for the specified user' ),
									'class'       => 'pl-8 pr-8 pt-6',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Password' ),
										'x-autocomplete' => '',
										'x-model'        => 'db.password',
									],
								],
								[
									'type'    => 'group',
									'columns' => 2,
									'class'   => 'pl-8 pr-8 pt-6',
									'fields'  => [
										[
											'name'       => 'db[host]',
											'type'       => 'text',
											'label'      => I18n::__( 'Hostname' ),
											'attributes' => [
												'required' => true,
												'placeholder' => I18n::__( 'Hostname' ),
												'x-autocomplete' => '',
												'x-model'  => 'db.host',
											],
										],
										[
											'name'       => 'db[prefix]',
											'type'       => 'text',
											'label'      => I18n::__( 'Prefix' ),
											'attributes' => [
												'required' => true,
												'placeholder' => I18n::__( 'Prefix' ),
												'x-autocomplete' => '',
												'x-model'  => 'db.prefix',
											],
										],
									],
								],
							],
						],
						[
							'type'       => 'step',
							'attributes' => [
								'x-wizard:step'   => 'Object.values(approved).every(Boolean) === true',
								'x-wizard:action' => 'approved = {};$ajax("install/test",db).then(response => approved = response)',
								'x-cloak'         => true,
							],
							'fields'     => [
								[
									'name'        => 'title',
									'type'        => 'header',
									'label'       => I18n::__( 'Step3: System check' ),
									'description' => I18n::__( 'This is an important step that will help make sure that your server is ready for installation and properly configured.' ),
								],
								[
									'name'  => 'website-data',
									'type'  => 'divider',
									'label' => I18n::__( 'System check' ),
								],
								[
									'name'     => 'date-format',
									'type'     => 'custom',
									'callback' => function() {
										$checks = [
											'php'        => I18n::__( 'PHP version 8.1 or higher' ),
											'mysql'      => I18n::__( 'MySQL version 5.6 or higher' ),
											'pdo'        => I18n::__( 'PDO PHP Extension' ),
											'connection' => I18n::__( 'Testing the database connection' ),
											'curl'       => I18n::__( 'cURL PHP Extension' ),
											'mbstring'   => I18n::__( 'Mbstring PHP Extension' ),
											'gd'         => I18n::__( 'GD PHP Extension' ),
											'memory'     => I18n::__( '128MB or more allocated memory' ),
										];
										?>
										<ul class="p-8 pb-0 dg g-2">
											<?php
											foreach ( $checks as $icon => $title ) :
												?>
												<li class="df aic">
													<span class="badge badge--lg badge--rounded bg-muted-lt" :class="approved.<?php echo $icon; ?> === undefined ? 'badge--load' : (approved.<?php echo $icon; ?> ? 't-herbal' : 't-reddish')">
														<i class="ph" :class="approved.<?php echo $icon; ?> ? 'ph-check' : 'ph-x'"></i>
													</span>
													<span class="ml-4"><?php echo $title; ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
										<?php
									},
								],
							],
						],
						[
							'type'       => 'step',
							'attributes' => [
								'x-cloak'       => true,
								'x-wizard:step' => '[user.login, user.email, user.password].every(value => value !== undefined && value.trim())',
							],
							'fields'     => [
								[
									'name'        => 'title',
									'type'        => 'header',
									'label'       => I18n::__( 'Step 4: Create account' ),
									'description' => I18n::__( 'Almost everything is ready! The last step. Add website owner information.' ),
								],
								[
									'name'  => 'user-credits',
									'type'  => 'divider',
									'label' => I18n::__( 'Owner credits' ),
								],
								[
									'name'        => 'user[login]',
									'type'        => 'text',
									'label'       => I18n::__( 'User login' ),
									'description' => I18n::__( 'Can use only alphanumeric characters, underscores, hyphens and @ symbol' ),
									'class'       => 'pl-8 pr-8 pt-6',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Enter login' ),
										'x-autocomplete' => '',
										'x-model'        => 'user.login',
									],
								],
								[
									'name'        => 'user[email]',
									'type'        => 'text',
									'label'       => I18n::__( 'Email address' ),
									'description' => I18n::__( 'Double-check your email address before continuing' ),
									'class'       => 'pl-8 pr-8 pt-6',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Enter email' ),
										'x-autocomplete' => '',
										'x-model'        => 'user.email',
									],
								],
								[
									'name'        => 'user[password]',
									'type'        => 'text',
									'label'       => I18n::__( 'Password' ),
									'description' => I18n::__( 'You will need this password to sign-in. Please store it in a secure location' ),
									'class'       => 'pl-8 pr-8 pt-6',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Password' ),
										'x-autocomplete' => '',
										'x-model'        => 'user.password',
									],
								],
							],
						],
						[
							'type'     => 'custom',
							'callback' => function() {
								ob_start();
								?>
								<!-- buttons -->
								<div class="p-8 df jcsb g-2">
									<button type="button" class="btn btn--outline" :disabled="$wizard.cannotGoBack()" @click="$wizard.goBack()" disabled><?php I18n::e( 'Back' ); ?></button>
									<button type="button" class="btn btn--primary" :disabled="$wizard.cannotGoNext()" x-show="$wizard.isNotLast()" @click="$wizard.goNext()" disabled><?php I18n::e( 'Continue' ); ?></button>
									<button type="submit" class="btn btn--primary" :disabled="$wizard.isUncompleted()" x-show="$wizard.isLast()" x-cloak disabled><?php I18n::e( 'Install Grafema' ); ?></button>
								</div>
								<?php
								return ob_get_clean();
							},
						],
					]
				);
			}
		);
	}
}
