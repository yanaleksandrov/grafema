<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Docify\Api\v1;

use Grafema;

class Projects extends Grafema\Api\Handler
{
	public string $endpoint = 'projects';

	/**
	 * Get all items.
	 *
	 * @url    GET api/v1/projects
	 */
	public function index(): array
	{
		return [
			'method' => 'GET',
		];
	}

	/**
	 * Get item by ID.
	 *
	 * @url    GET api/v1/projects/$id
	 */
	public function view(): array
	{
		return [
			'method' => 'GET',
		];
	}

	/**
	 * Create item.
	 *
	 * @url    POST api/v1/projects
	 */
	public function create(): array
	{
		return [
			'method' => 'GET',
		];
	}

	/**
	 * Update item by ID.
	 *
	 * @url    POST api/v1/projects/$id
	 */
	public function update(): array
	{
		return [
			'method' => 'GET',
		];
	}

	/**
	 * Remove item by ID.
	 *
	 * @url    DELETE api/v1/projects/$id
	 */
	public function delete(): array
	{
		return [
			'method' => 'GET',
		];
	}
}
