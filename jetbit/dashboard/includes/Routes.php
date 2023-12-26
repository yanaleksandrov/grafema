<?php

use Grafema\Mail\Mail;
use Grafema\Post;
use Grafema\File\Image;
use Grafema\Route;
use Grafema\Helpers\Arr;
use Grafema\Json;
use Grafema\Debug;
use Grafema\I18n;
use Query\Query;

/**
 *
 *
 * @package Grafema
 */
final class Routes {

	use Grafema\Patterns\Singleton;

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$route = new Route();
		$route->mount(
			'/api/v1',
			function() use ( $route ) {
				$route->before(
					'GET|POST|DELETE',
					'/.*',
					function() {
						header( 'Content-Type: application/json' );
						if ( empty( $_REQUEST['nonce'] ) || ! is_string( $_REQUEST['nonce'] ) ) {
							echo Json::encode(
								[
									'status'    => 200,
									'benchmark' => Debug::timer( 'getall' ),
									'error'     => I18n::__( 'Ajax queries not allows without nonce' ),
								]
							);
							die();
						}
					}
				);

				$route->post(
					'upload/media',
					function () {
						$files = [];
						if ( $_FILES && is_array( $_FILES ) ) {
							foreach ( $_FILES as $file ) {
								// upload original image
								$original_file = File::upload(
									$file,
									function( $file ) {
										$file->set_directory( 'i/original' );
									}
								);

								// now make smaller copies
								$file_path = $original_file['path'] ?? '';
								if ( $file_path ) {
									$image = new Image();
									$sizes = Patterns\Registry::get( 'jb.images' );
									if ( is_array( $sizes ) && $sizes !== [] ) {
										foreach ( $sizes as $size ) {
											$mime   = $size['mime'] ?? null;
											$width  = intval( $size['width'] ?? 0 );
											$height = intval( $size['height'] ?? 0 );

											if ( ! $width || ! $height ) {
												continue;
											}

											$file_resized = sprintf( '/i/%s/', implode( 'x', [ $width, $height ] ) );
											$file_resized = str_replace( '/i/original/', $file_resized, $file_path );

											$image->fromFile( $file_path )->thumbnail( $width, $height )->toFile( $file_resized, $mime );
										}
									}

									$files[] = $original_file;
								}
							}
						}

						$posts = [];
						if ( $files ) {
							foreach ( $files as $file ) {
								$post_id = Post::add(
									'media',
									[
										'status' => 'publish',
										'slug'   => $file['slug'],
										'fields' => [
											'mime' => $file['mime'],
										],
									]
								);

								if ( $post_id ) {
									$posts[] = Post::get( 'media', $post_id );
								}
							}
						}

						echo Json::encode(
							[
								'status'    => 200,
								'benchmark' => Debug::timer( 'getall' ),
								'data'      => [
									[
										'fragment' => sprintf( I18n::__( '%d files have been successfully uploaded to the library' ), count( $files ) ),
										'target'   => 'body',
										'method'   => 'notify',
										'custom'   => [
											'type'     => count( $posts ) > 0 ? 'success' : 'error',
											'duration' => 5000,
										],
									],
									[
										'fragment' => $posts,
										'target'   => 'body',
										'method'   => 'alpine',
									],
								],
							]
						);
					}
				);

				$route->post(
					'/export-posts/',
					function () {
						$format = trim( strval( $_REQUEST['format'] ?? '' ) );
						$types  = $_REQUEST['types'] ?? [];
						$date   = date( 'YmdHis' );

						header( 'Content-type: application/force-download' );
						header( 'Content-Disposition: inline; filename="grafema-posts-' . $date . '.' . $format . '"' );

						if ( ! is_array( $types ) ) {
							exit;
						}

						echo Query::apply(
							[
								'type'     => $types,
								'per_page' => 99999999,
							],
							function( $posts ) use ( $format ) {
								if ( ! is_array( $posts ) || empty( $posts ) ) {
									return $posts;
								}

								switch ( $format ) {
									case 'json':
										return Json::encode( $posts );
									case 'csv':
										array_unshift( $posts, array_keys( $posts[0] ) );

										return \File\Csv::export( $posts );
									default:
										return $posts;
								}
							}
						);
						exit();
					}
				);

				$route->post(
					'/save-settings/',
					function () {
						$options = Arr::exclude( $_POST, [ 'nonce' ] );
						if ( $options ) {
							print_r( $options );
							foreach ( $options as $option => $value ) {
								print_r( Arr::dot( [ $option => $value ] ) );
								//Option::modify( $option, $value );
							}
						}

						echo Json::encode(
							[
								'status'    => 200,
								'benchmark' => Debug::timer( 'getall' ),
								'data'      => [
									[
										'fragment' => I18n::__( 'Options is updated successfully' ),
										'target'   => 'body',
										'method'   => 'notify',
										'custom'   => [
											'type'     => 'success',
											'duration' => 5000,
										],
									],
								],
							]
						);
					}
				);

				$route->post(
					'/reset-password/',
					function () {
						$email = trim( strval( $_REQUEST['email'] ?? '' ) );

						if ( empty( $email ) ) {
							echo Json::encode(
								[
									'status'    => 200,
									'benchmark' => Debug::timer( 'getall' ),
									'data'      => [
										[
											'delay'    => 0,
											'fragment' => I18n::__( 'Field can\'t be empty' ),
											'method'   => 'update',
											'target'   => '.email-error',
										],
										[
											'delay'    => 0,
											'fragment' => 'is-invalid',
											'method'   => 'addClass',
											'target'   => '[name="email"]',
										],
										[
											'delay'    => 4000,
											'fragment' => '',
											'method'   => 'update',
											'target'   => '.email-error',
										],
										[
											'delay'    => 4000,
											'fragment' => 'is-invalid',
											'method'   => 'removeClass',
											'target'   => '[name="email"]',
										],
									],
								]
							);

							die;
						}

						$user = User\User::get( $email, 'email' );
						if ( ! $user instanceof User\User ) {
							echo Json::encode(
								[
									'status'    => 200,
									'benchmark' => Debug::timer( 'getall' ),
									'data'      => [
										[
											'delay'    => 0,
											'fragment' => I18n::__( 'The user with this email was not found' ),
											'method'   => 'update',
											'target'   => '.email-error',
										],
										[
											'delay'    => 4000,
											'fragment' => '',
											'method'   => 'update',
											'target'   => '.email-error',
										],
									],
								]
							);
						} else {
							$mail_is_sended = Mail::send(
								$email,
								'Instructions for reset password',
								View::include(
									GRFM_DASHBOARD . 'templates/mails/wrapper.php',
									[
										'body_template' => GRFM_DASHBOARD . 'templates/mails/reset-password.php',
									]
								)
							);
							if ( $mail_is_sended ) {
								echo Json::encode(
									[
										'status'    => 200,
										'benchmark' => Debug::timer( 'getall' ),
										'alpine'    => [
											[
												'fragment' => true,
												'target'   => 'sent',
											],
										],
									]
								);
							}
						}
					}
				);
			}
		);
		$route->run(
			function() {
				die();
			}
		);
	}
}
