<?php

namespace Grafema;

use PDO, PDOException, PDOStatement, Raw;

final class DB {

	use \Grafema\Patterns\Singleton;

	/**
	 * DB structure
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public static array $schema = [];

	/**
	 * DB queries count
	 *
	 * @var int
	 * @since 1.0.0
	 */
	public static int $queries = 0;

	/**
	 * DB queries count
	 *
	 * @var null|DB\Medoo
	 * @since 1.0.0
	 */
	public static ?DB\Medoo $connection = null;

	/**
	 * Init database connection
	 *
	 * @return void|Errors
	 */
	public function __construct( array $options = [] ) {
		$options = array_merge(
			[
				'database' => defined( 'DB_NAME' ) ? DB_NAME : '',
				'username' => defined( 'DB_USER' ) ? DB_USER : '',
				'password' => defined( 'DB_PASSWORD' ) ? DB_PASSWORD : '',
				'host'     => defined( 'DB_HOST' ) ? DB_HOST : 'localhost',
				'prefix'   => defined( 'DB_PREFIX' ) ? DB_PREFIX : 'grafema_',
				'type'     => defined( 'DB_TYPE' ) ? DB_TYPE : 'mysql',
				'charset'  => defined( 'DB_CHARSET' ) ? DB_CHARSET : 'utf8mb4',
			],
			$options
		);

		try {
			self::$connection = new DB\Medoo( $options );
		} catch ( PDOException $e ) {
			return new Errors( 'database-connection', I18n::__( 'There is a problem with connecting to the database' ) );
		}
	}

	/**
	 * Execute customized raw statement.
	 *
	 * @param string $statement The raw SQL statement.
	 * @param array $map The array of input parameters value for prepared statement.
	 * @return PDOStatement|null
	 */
	public static function query( string $statement, array $map = [] ): ?PDOStatement {
		return self::$connection->query( $statement, $map );
	}

	/**
	 * Execute the raw statement.
	 *
	 * @param string $statement The SQL statement.
	 * @param array $map The array of input parameters value for prepared statement.
	 * @param callable|null $callback
	 * @return PDOStatement|null
	 * @codeCoverageIgnore
	 */
	public static function exec( string $statement, array $map = [], callable $callback = null ): ?PDOStatement {
		return self::$connection->exec( $statement, $map, $callback );
	}

	/**
	 * Drop a table.
	 *
	 * @param string $table
	 * @return PDOStatement|null
	 */
	public static function drop( string $table ): ?PDOStatement {
		return self::$connection->drop( $table );
	}

	/**
	 * Select data from the table.
	 *
	 * @param string $table
	 * @param array $join
	 * @param array|string $columns
	 * @param array $where
	 * @return array|null
	 */
	public static function select( string $table, $join, $columns = null, $where = null ): ?array {
		return self::$connection->select( $table, $join, $columns, $where );
	}

	/**
	 * Insert one or more records into the table.
	 *
	 * @param string $table
	 * @param array $values
	 * @param string|null $primaryKey
	 * @return PDOStatement|null
	 */
	public static function insert( string $table, array $values, string $primaryKey = null ): ?PDOStatement {
		return self::$connection->insert( $table, $values, $primaryKey );
	}

	/**
	 * Modify data from the table.
	 *
	 * @param string $table
	 * @param array $data
	 * @param array $where
	 * @return PDOStatement|null
	 */
	public static function update( string $table, $data, $where = null ): ?PDOStatement {
		return self::$connection->update( $table, $data, $where );
	}

	/**
	 * Delete data from the table.
	 *
	 * @param string $table
	 * @param array|Raw $where
	 * @return PDOStatement|null
	 */
	public static function delete( string $table, $where ): ?PDOStatement {
		return self::$connection->delete( $table, $where );
	}

	/**
	 * Replace old data with a new one.
	 *
	 * @param string $table
	 * @param array $columns
	 * @param array $where
	 * @return PDOStatement|null
	 */
	public static function replace( string $table, array $columns, $where = null ): ?PDOStatement {
		return self::$connection->replace( $table, $columns, $where );
	}

	/**
	 * Get only one record from the table.
	 *
	 * @param string $table
	 * @param array $join
	 * @param array|string $columns
	 * @param array $where
	 * @return mixed
	 */
	public static function get( string $table, $join = null, $columns = null, $where = null ) {
		return self::$connection->get( $table, $join, $columns, $where );
	}

	/**
	 * Determine whether the target data existed from the table.
	 *
	 * @param string $table
	 * @param array $join
	 * @param array $where
	 * @return bool
	 */
	public static function has( string $table, $join, $where = null ): bool {
		return self::$connection->has( $table, $join, $where );
	}

	/**
	 * Randomly fetch data from the table.
	 *
	 * @param string $table
	 * @param array $join
	 * @param array|string $columns
	 * @param array $where
	 * @return array
	 */
	public static function rand( string $table, $join = null, $columns = null, $where = null ): array {
		return self::$connection->rand( $table, $join, $columns, $where );
	}

	/**
	 * Count the number of rows from the table.
	 *
	 * @param string $table
	 * @param array $join
	 * @param string $column
	 * @param array $where
	 * @return int|null
	 */
	public static function count( string $table, $join = null, $column = null, $where = null ): ?int {
		return self::$connection->count( $table, $join, $column, $where );
	}

	/**
	 * Calculate the average value of the column.
	 *
	 * @param string $table
	 * @param array $join
	 * @param string $column
	 * @param array $where
	 * @return string|null
	 */
	public static function avg( string $table, $join, $column = null, $where = null ): ?string {
		return self::$connection->avg( $table, $join, $column, $where );
	}

	/**
	 * Get the maximum value of the column.
	 *
	 * @param string $table
	 * @param array $join
	 * @param string $column
	 * @param array $where
	 * @return string|null
	 */
	public static function max( string $table, $join, $column = null, $where = null ): ?string {
		return self::$connection->max( $table, $join, $column, $where );
	}

	/**
	 * Get the minimum value of the column.
	 *
	 * @param string $table
	 * @param array $join
	 * @param string $column
	 * @param array $where
	 * @return string|null
	 */
	public static function min( string $table, $join, $column = null, $where = null ): ?string {
		return self::$connection->min( $table, $join, $column, $where );
	}

	/**
	 * Calculate the total value of the column.
	 *
	 * @param string $table
	 * @param array $join
	 * @param string $column
	 * @param array $where
	 * @return string|null
	 */
	public static function sum( string $table, $join, $column = null, $where = null ): ?string {
		return self::$connection->sum( $table, $join, $column, $where );
	}

	/**
	 * Enable debug mode and output readable statement string.
	 *
	 * @codeCoverageIgnore
	 * @return DB\Medoo
	 */
	public static function debug(): DB\Medoo {
		return self::$connection->debug();
	}

	/**
	 * Return the last performed statement.
	 *
	 * @return string|null
	 */
	public static function last(): ?string {
		return self::$connection->last();
	}

	/**
	 * Get count of queries
	 *
	 * @since 1.0.0
	 */
	public static function queries(): int {
		return self::$queries;
	}

	/**
	 * Get count of queries
	 *
	 * @since 1.0.0
	 */
	public static function info(): ?array {
		if ( self::$connection instanceof DB\Medoo ) {
			return self::$connection->info();
		}
		return null;
	}

	/**
	 * Return the ID for the last inserted row.
	 *
	 * @param string|null $name
	 * @return string|null
	 * @codeCoverageIgnore
	 */
	public static function id( string $name = null ): ?string {
		return self::$connection->id( $name );
	}

	/**
	 * Get database version
	 *
	 * @since 1.0.0
	 */
	public static function version(): array|string|null {
		$info = self::info();
		if ( isset( $info['version'] ) ) {
			$version = preg_replace( '/[^0-9.].*/', '', $info['version'] );
			return preg_replace( '/^\D+([\d.]+).*/', '$1', $version );
		}
		return null;
	}

	/**
	 * Get database schema
	 *
	 * @see   https://stackoverflow.com/questions/52642542/how-to-extract-column-name-and-type-from-mysql
	 * @since 1.0.0
	 */
	public static function schema( string $_column = null ) {
		if ( ! self::$schema ) {
			$columns = self::query(
				'
				SELECT
    				COLUMN_NAME as name, DATA_TYPE as type, TABLE_NAME as tbl
				FROM
				    INFORMATION_SCHEMA.COLUMNS
				WHERE
				    TABLE_SCHEMA = :database',
				[
					':database' => DB_NAME,
				]
			)->fetchAll( PDO::FETCH_ASSOC );
			if ( $columns ) {
				foreach ( $columns as $column ) {
					self::$schema[ $column['tbl'] ][ $column['name'] ] = $column['type'];
				}
			}
		}

		if ( isset( self::$schema[ $_column ] ) ) {
			return self::$schema[ $_column ];
		}
		return self::$schema;
	}

	/**
	 * Update database schema.
	 *
	 * @since 1.0.0
	 */
	public static function updateSchema() {
		if ( self::$schema ) {
			self::$schema = [];
		}
		return self::schema();
	}

	/**
	 * Try to check database connection
	 *
	 * @return DB\Medoo|Errors
	 * @since 1.0.0
	 */
	public static function check() {
		try {
			return self::$connection;
		} catch ( PDOException $e ) {
			return new Errors( 'database-connection', I18n::__( 'There is a problem with connecting to the database' ) );
		}
	}
}
