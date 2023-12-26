<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api\V1;

use Grafema\Debug;
use Grafema\Json;
use Grafema\Sanitizer;
use Grafema\Url;
use Grafema\Validator;

class User extends \Grafema\Api\Handler
{
	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'user';

	/**
	 * Get all items.
	 *
	 * @url    GET api/v1/user
	 */
	public function index(): array
	{
		return [
			'method' => 'GET user list',
		];
	}

	/**
	 * Get item by ID.
	 *
	 * @url    GET api/v1/user/$id
	 */
	public function view(): array
	{
		return [
			'method' => 'GET user by ID',
		];
	}

	/**
	 * Create item.
	 *
	 * @url    POST api/v1/user
	 */
	public function create(): array
	{
		return [
			'method' => 'POST create user',
		];
	}

	/**
	 * Update item by ID.
	 *
	 * @url    PUT api/v1/user/$id
	 */
	public function update(): array
	{
		return [
			'method' => 'PUT update user by ID',
		];
	}

	/**
	 * Remove item by ID.
	 *
	 * @url    DELETE api/v1/user/$id
	 */
	public function delete(): array
	{
		return [
			'method' => 'DELETE remove user by ID',
		];
	}

	/**
	 * Check the compliance of the server with the minimum requirements.
	 */
	public static function signIn(): array
	{
		$request = ( new Sanitizer() )->apply(
			$_REQUEST,
			[
				'login'    => 'trim',
				'password' => 'trim',
				'remember' => 'bool',
			]
		);

		$validator = ( new Validator(
			$request,
			[
				'login'    => 'required',
				'password' => 'required',
			]
		) )->apply();

		if ( $validator instanceof Validator ) {
			return $validator->errors;
		}

		[$login, $password, $remember] = array_values( $request );

		return (array) \Grafema\User::login( $login, $password, $remember );
	}

	/**
	 * @throws JsonException
	 */
	public static function signUp(): string
	{
		$user = \Grafema\User::add( $_REQUEST ?? [] );

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
