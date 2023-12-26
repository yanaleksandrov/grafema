<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

use DB\DB;

/**
 * Core class for managing comments.
 *
 * @since 1.0.0
 */
class Comments
{
	/**
	 * DataBase table name.
	 *
	 * @since 1.0.0
	 */
	public static string $table = 'comments';

	/**
	 * @since 1.0.0
	 */
	public static function add( string $string ): void {}

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
				ID           bigint(20)   unsigned NOT NULL auto_increment,
				post_ID      bigint(20)   unsigned NOT NULL default '0',
				author       tinytext     NOT NULL,
				author_ID    bigint(20)   unsigned NOT NULL default '0',
				author_email varchar(100) NOT NULL default '',
				author_url   varchar(200) NOT NULL default '',
				author_IP    varchar(100) NOT NULL default '',
				dating       datetime     NOT NULL default '0000-00-00 00:00:00',
				content      text         NOT NULL,
				karma        int(11)      NOT NULL default '0',
				approved     varchar(20)  NOT NULL default '1',
				agent        varchar(255) NOT NULL default '',
				category     varchar(20)  NOT NULL default 'comment',
				parent       bigint(20)   unsigned NOT NULL default '0',
				PRIMARY KEY  (ID),
				KEY post_ID (post_ID),
				KEY approved_dating (approved,dating),
				KEY dating (dating),
				KEY parent (parent),
				KEY author_email (author_email(10))
			) {$charset_collate};"
		)->fetchAll();

		DB::query(
			"
				CREATE TABLE IF NOT EXISTS {$table}_fields (
				meta_id     bigint(20) unsigned NOT NULL auto_increment,
				ID          bigint(20) unsigned NOT NULL default '0',
				name        varchar(255) default NULL,
				value       longtext,
				PRIMARY KEY (meta_id),
				KEY ID (ID),
				KEY name ( name({$length}) )
			) {$charset_collate};"
		)->fetchAll();

		DB::updateSchema();
	}
}
