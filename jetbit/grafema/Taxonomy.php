<?php

namespace Grafema;

use DB\DB;

/**
 * Core class for managing taxonomies.
 *
 * @since 1.0.0
 */
class Taxonomy {

	/**
	 * DataBase table name.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public static string $table = 'term_taxonomy';

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public static function register( string $taxonomy, string $object_type, array $args = [] ) {

	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public static function unregister( string $taxonomy ) {

	}

	/**
	 * Determines whether the taxonomy object is hierarchical.
	 *
	 * Checks to make sure that the taxonomy is an object first. Then Gets the
	 * object, and finally returns the hierarchical value in the object.
	 *
	 * A false return value might also mean that the taxonomy does not exist.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Name of taxonomy object.
	 * @return  bool           Whether the taxonomy is hierarchical.
	 */
	public static function is_hierarchical( string $taxonomy ): bool {

	}

	/**
	 * Create new table into database.
	 *
	 * @since 1.0.0
	 */
	public static function migrate() {
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
			CREATE TABLE IF NOT EXISTS $table (
				term_taxonomy_id  bigint(20) unsigned NOT NULL auto_increment,
				term_id           bigint(20) unsigned NOT NULL default 0,
				taxonomy          varchar(32) NOT NULL default '',
				description       longtext NOT NULL,
				parent bigint(20) unsigned NOT NULL default 0,
				count bigint(20)  NOT NULL default 0,
				PRIMARY KEY (term_taxonomy_id),
				UNIQUE KEY  term_id_taxonomy (term_id,taxonomy),
				KEY taxonomy (taxonomy)
			) $charset_collate;"
		)->fetchAll();

		DB::updateSchema();
	}
}
