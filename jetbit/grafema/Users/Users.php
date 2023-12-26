<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Users;

use Grafema\DB;

/**
 * @since 1.0.0
 */
class Users
{
	/**
	 * DataBase table name.
	 *
	 * @since 1.0.0
	 */
	public static string $table = 'users';

	/**
	 * Create new table into database.
	 *
	 * @since 1.0.0
	 */
	public static function migrate(): void
	{
		$length          = DB_MAX_INDEX_LENGTH;
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
				ID            bigint(20)   unsigned NOT NULL auto_increment,
				login         varchar(60)  NOT NULL default '',
				password      varchar(255) NOT NULL default '',
				nicename      varchar(60)  NOT NULL default '',
				showname      varchar(250) NOT NULL default '',
				email         varchar(100) NOT NULL default '',
				roles         varchar(999) NOT NULL default '',
				registered    datetime     NOT NULL default NOW(),
				visited       datetime     NOT NULL default NOW(),
				PRIMARY KEY   (ID),
				KEY login_key (login),
				KEY nicename  (nicename),
				KEY email     (email)
			) {$charset_collate};"
		)->fetchAll();

		DB::query(
			"
			CREATE TABLE IF NOT EXISTS {$table}_fields (
				meta_id     bigint(20)   unsigned NOT NULL auto_increment,
				ID          bigint(20)   unsigned NOT NULL default '0',
				name        varchar(255) default NULL,
				value       longtext,
				PRIMARY KEY ( meta_id ),
				KEY user_id ( ID ),
				KEY name ( name({$length}) )
			) {$charset_collate};"
		)->fetchAll();

		DB::updateSchema();
	}
}
