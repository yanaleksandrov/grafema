<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
use Grafema\Api;
use Grafema\Asset;
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Post\Status;
use Grafema\Post\Type;
use Grafema\User;
use Grafema\Debug;
use Grafema\Json;
use Grafema\View;

/**
 * Bootstrap file for setting the constants and loading the config.php file.
 * At this point, we initialize autoload of the application core classes.
 */
class Dashboard extends Grafema\App\App
{
	use Grafema\Patterns\Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		/*
		 * Define a constants
		 *
		 * @since 1.0.0
		 */
		$this->define( 'GRFM_IS_DASHBOARD', true );

		/*
		 * Add core api endpoints
		 *
		 * @since 1.0.0
		 */
		Api::create( sprintf( '%sapi/v1', GRFM_DASHBOARD ), '/api/v1' );

		/*
		 * Override response
		 *
		 * @since 1.0.0
		 */
		Hook::add(
			'grafema_api_response',
			function ( $json, $slug, $data ) {
				switch ( $slug ) {
					case 'sign/in':
						if ( $data instanceof User ) {
							$data = [
								[
									'target'   => 'body',
									'fragment' => 'https://cms.codyshop.ru/dashboard/',
									'method'   => 'redirect',
								],
							];
						}
						break;
					case 'sign/up':
						break;
					case 'grab/files':
						$data = [
							[
								'fragment' => sprintf( I18n::__( '%d files have been successfully uploaded to the library' ), count( $data ) ),
								'target'   => 'body',
								'method'   => 'notify',
								'custom'   => [
									'type'     => count( $data ) > 0 ? 'success' : 'error',
									'duration' => 5000,
								],
							],
						];
						break;
					case 'import/posts':
						ob_start();
						View::part(
							'templates/states/completed',
							[
								'title'       => sprintf( I18n::__( 'Import is completed' ), count( $data ) ),
								'instruction' => sprintf( I18n::__( '%d posts was successfully imported. Do you want <a href="%s">to launch a new import?</a>' ), count( $data ), '/dashboard/import' ),
							]
						);
						$fragment = ob_get_clean();

						$data = [
							[
								'fragment' => $fragment,
								'target'   => 'body',
								'method'   => 'alpine',
							],
						];
						break;
					case 'upload/file':
						$filepath = $data->path ?? '';
						if ( $filepath ) {
							$rows    = \File\Csv::import( $filepath );
							$samples = $rows[0] ?? [];

							$fields = [
								[
									'type'   => 'group',
									'label'  => I18n::__( 'Required Data' ),
									'fields' => [
										[
											'name'        => 'type',
											'type'        => 'select',
											'instruction' => sprintf( I18n::__( 'Sample: <samp>%s</samp>' ), 'pages' ),
											'attributes'  => [
												'x-select' => '',
												'class'    => 'dg g-1 ga-2',
											],
											'options' => Type::get(),
										],
										[
											'name'        => 'status',
											'type'        => 'select',
											'instruction' => I18n::__( 'Set default post status, if not specified' ),
											'attributes'  => [
												'x-select' => '',
												'class'    => 'dg g-1 ga-2',
											],
											'options' => Status::get(),
										],
										[
											'name'        => 'author',
											'type'        => 'select',
											'instruction' => I18n::__( 'Set author, if not specified' ),
											'attributes'  => [
												'x-select' => '',
												'class'    => 'dg g-1 ga-2',
											],
											'options' => [
												'1' => 'Yan Aleksandrov',
											],
										],
									],
								],
								[
									'type'   => 'group',
									'label'  => I18n::__( 'Map Data' ),
									'fields' => [],
								],
							];

							foreach ( $samples as $index => $sample ) {
								$fields[1]['fields'][] = [
									'name'        => 'map[' . $index . ']',
									'type'        => 'select',
									'instruction' => sprintf( I18n::__( 'Sample: <samp>%s</samp>' ), $sample ),
									'options'     => [
										''     => I18n::__( 'No import' ),
										'main' => [
											'label'   => I18n::__( 'Main fields' ),
											'options' => [
												'name'     => I18n::__( 'Post ID' ),
												'author'   => I18n::__( 'Author ID' ),
												'views'    => I18n::__( 'Views count' ),
												'type'     => I18n::__( 'Type' ),
												'title'    => I18n::__( 'Title' ),
												'content'  => I18n::__( 'Content' ),
												'created'  => I18n::__( 'Created at' ),
												'modified' => I18n::__( 'Modified at' ),
												'status'   => I18n::__( 'Status' ),
											],
										],
									],
									'attributes' => [
										'x-select' => '',
										'class'    => 'dg g-1 ga-2',
									],
								];
							}

							$fields[] = [
								'name'     => 'custom',
								'type'     => 'custom',
								'callback' => fn () => '<input type="hidden" value="' . $filepath . '" name="filename">',
							];

							Form::register(
								'import-posts-fields',
								[],
								function ( $form ) use ( $fields ) {
									$form->addFields( $fields );
								}
							);

							$data = [
								[
									'target' => 'input[type="file"]',
									'method' => 'value',
								],
								[
									'fragment' => Form::view( 'import-posts-fields', true ),
									'target'   => 'body',
									'method'   => 'alpine',
								],
							];
						}
						break;
					case 'extensions/get':
						$data = [
							[
								'fragment' => $data,
								'target'   => 'body',
								'method'   => 'alpine',
							],
						];
						break;
					default:
						break;
				}

				return Json::encode(
					[
						'status'    => 200,
						'benchmark' => Debug::timer( 'getall' ),
						'data'      => $data,
					]
				);
			},
			10,
			3
		);

		/**
		 * Include CSS styles & JS scripts.
		 *
		 * @since 1.0.0
		 */
		$styles = ['phosphor', 'colorist', 'datepicker', 'drooltip', 'flags', 'nouislider', 'prism', 'slimselect', 'sortable', 'main'];

		foreach ( $styles as $style ) {
			Asset::enqueue( $style, '/dashboard/assets/css/' . $style . '.css', [], '1.5.0' );
		}

		$scripts = ['index', 'slimselect', 'drooltip', 'alpine.min', 'dragula.min', 'croppr.min', 'prism.min'];

		foreach ( $scripts as $script ) {
			$data = [];
			if ( $script === 'index' ) {
				$data['data'] = [
					'apiurl' => 'https://cms.codyshop.ru/api/v1/',
				];
			}

			if ( $script === 'index' ) {
				// $data['dependencies'] = [ 'dragula-min-js' ];
			}
			Asset::enqueue( $script, '/dashboard/assets/js/' . $script . '.js', $data );
		}

		/*
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 1.0.0
		 */
		Hook::add(
			'dashboard_dashboard_head',
			function () {
				Asset::plug( '*.css' );
			}
		);

		Hook::add(
			'grafema_dashboard_footer',
			function () {
				Asset::plug( '*.js' );
			}
		);

		/*
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 1.0.0
		 */
		Menu::init();

		/*
		 * Register new forms
		 *
		 * @since 1.0.0
		 */
		Forms::init();

		/*
		 * Register new routes
		 *
		 * @since 1.0.0
		 */
		Routes::init();
	}
}
