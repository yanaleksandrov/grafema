<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

/**
 * Option class it is a self-contained class for creating, updating, and deleting options.
 * Uses static variables to store options, which allows to avoid using the object cache without losing performance.
 *
 * @since 1.0.0
 */
class Options
{
	/**
	 * DataBase table name.
	 *
	 * @since 1.0.0
	 */
	public static string $table = 'options';

	/**
	 * Create new table into database.
	 *
	 * @since 1.0.0
	 */
	public static function migrate(): void
	{
		$table           = DB_PREFIX . self::$table;
		$charset_collate = '';
		if ( DB_CHARSET ) {
			$charset_collate = 'DEFAULT CHARACTER SET ' . DB_CHARSET;
		}
		if ( DB_COLLATE ) {
			$charset_collate .= ' COLLATE ' . DB_COLLATE;
		}

		DB::query(
			"
			CREATE TABLE IF NOT EXISTS {$table} (
				ID          bigint(20)   unsigned NOT NULL auto_increment,
				name        varchar(191) NOT NULL default '',
				value       longtext     NOT NULL,
				PRIMARY KEY (ID),
				UNIQUE KEY name (name)
			) {$charset_collate};"
		)->fetchAll();

		DB::updateSchema();
	}
}
