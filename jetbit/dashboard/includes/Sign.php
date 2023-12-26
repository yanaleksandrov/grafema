<?php

use Validator\Validator;
use User\User;

/**
 *
 *
 * @package Grafema
 */
class Sign {

	/**
	 * Check the compliance of the server with the minimum requirements
	 *
	 * @return array|User|Errors
	 */
	public static function in(): array|User|Errors {
		$login    = trim( strval( $_REQUEST['login'] ?? '' ) );
		$password = trim( strval( $_REQUEST['password'] ?? '' ) );
		$remember = boolval( $_REQUEST['remember'] ?? false );

		$validator = ( new Validator(
			$_REQUEST,
			[
				'login'    => 'required',
				'password' => 'required',
			]
		) )->apply();

		if ( $validator instanceof Validator ) {
			return $validator->errors;
		}

		return User::login( $login, $password, $remember );
	}

	/**
	 * @return string
	 * @throws JsonException
	 */
	public static function up(): string {
		$user = User::add( $_REQUEST ?? [] );

		if ( $user instanceof User ) {
			echo Json::encode(
				[
					'status'    => 200,
					'benchmark' => Debug::timer( 'getall' ),
					'data'      => [
						[
							'target'   => 'body',
							'fragment' => Url::sign_in(),
							'method'   => 'redirect',
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
}
