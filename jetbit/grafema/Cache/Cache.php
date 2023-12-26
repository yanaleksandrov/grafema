<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
class Cache
{
	/**
	 * The amount of times the cache data was already stored in the cache.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $cache_hits = 0;

	/**
	 * Amount of times the cache did not have the request in cache.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	public $cache_misses = 0;

	/**
	 * List of global cache groups.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $global_groups = [];

	/**
	 * Holds the cached objects.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $cache = [];

	/**
	 * Sets up object properties; PHP 5 style constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Makes private properties readable for backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name property to get
	 *
	 * @return mixed property
	 */
	public function __get( $name )
	{
		return $this->{$name};
	}

	/**
	 * Makes private properties settable for backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name  property to set
	 * @param mixed  $value property value
	 *
	 * @return mixed newly-set property
	 */
	public function __set( $name, $value )
	{
		return $this->{$name} = $value;
	}

	/**
	 * Makes private properties checkable for backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name property to check if set
	 *
	 * @return bool whether the property is set
	 */
	public function __isset( $name )
	{
		return isset( $this->{$name} );
	}

	/**
	 * Makes private properties un-settable for backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name property to unset
	 */
	public function __unset( $name )
	{
		unset( $this->{$name} );
	}

	/**
	 * Adds data to the cache if it doesn't already exist.
	 *
	 * @since 1.0.0
	 *
	 * @uses Cache::_exists() Checks to see if the cache already has data.
	 * @uses Cache::set()     Sets the data after the checking the cache
	 *                        contents existence.
	 *
	 * @param int|string $key    What to call the contents in the cache
	 * @param mixed      $data   The contents to store in the cache
	 * @param string     $group  Optional. Where to group the cache contents. Default 'default'.
	 * @param int        $expire Optional. When to expire the cache contents. Default 0 (no expiration).
	 *
	 * @return bool true on success, false if cache key and group already exist
	 */
	public function add( $key, $data, $group = 'default', $expire = 0 )
	{
		if ( empty( $group ) ) {
			$group = 'default';
		}

		$id = $key;

		if ( $this->_exists( $id, $group ) ) {
			return false;
		}

		return $this->set( $key, $data, $group, (int) $expire );
	}

	/**
	 * Sets the list of global cache groups.
	 *
	 * @since 1.0.0
	 *
	 * @param array $groups list of groups that are global
	 */
	public function add_global_groups( $groups )
	{
		$groups = (array) $groups;

		$groups              = array_fill_keys( $groups, true );
		$this->global_groups = array_merge( $this->global_groups, $groups );
	}

	/**
	 * Decrements numeric cache item's value.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $key    the cache key to decrement
	 * @param int        $offset Optional. The amount by which to decrement the item's value. Default 1.
	 * @param string     $group  Optional. The group the key is in. Default 'default'.
	 *
	 * @return false|int the item's new value on success, false on failure
	 */
	public function decr( $key, $offset = 1, $group = 'default' )
	{
		if ( empty( $group ) ) {
			$group = 'default';
		}

		if ( ! $this->_exists( $key, $group ) ) {
			return false;
		}

		if ( ! is_numeric( $this->cache[$group][$key] ) ) {
			$this->cache[$group][$key] = 0;
		}

		$offset = (int) $offset;

		$this->cache[$group][$key] -= $offset;

		if ( $this->cache[$group][$key] < 0 ) {
			$this->cache[$group][$key] = 0;
		}

		return $this->cache[$group][$key];
	}

	/**
	 * Removes the contents of the cache key in the group.
	 *
	 * If the cache key does not exist in the group, then nothing will happen.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $key        what the contents in the cache are called
	 * @param string     $group      Optional. Where the cache contents are grouped. Default 'default'.
	 * @param bool       $deprecated Optional. Unused. Default false.
	 *
	 * @return bool false if the contents weren't deleted and true on success
	 */
	public function delete( $key, $group = 'default', $deprecated = false )
	{
		if ( empty( $group ) ) {
			$group = 'default';
		}

		if ( ! $this->_exists( $key, $group ) ) {
			return false;
		}

		unset( $this->cache[$group][$key] );

		return true;
	}

	/**
	 * Clears the object cache of all data.
	 *
	 * @since 1.0.0
	 *
	 * @return true always returns true
	 */
	public function flush(): bool
	{
		$this->cache = [];

		return true;
	}

	/**
	 * Retrieves the cache contents, if it exists.
	 *
	 * The contents will be first attempted to be retrieved by searching by the
	 * key in the cache group. If the cache is hit (success) then the contents
	 * are returned.
	 *
	 * On failure, the number of cache misses will be incremented.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $key   the key under which the cache contents are stored
	 * @param string     $group Optional. Where the cache contents are grouped. Default 'default'.
	 * @param bool       $force Optional. Unused. Whether to force an update of the local cache
	 *                          from the persistent cache. Default false.
	 * @param bool       $found Optional. Whether the key was found in the cache (passed by reference).
	 *                          Disambiguates a return of false, a storable value. Default null.
	 *
	 * @return false|mixed the cache contents on success, false on failure to retrieve contents
	 */
	public function get( $key, $group = 'default', $force = false, &$found = null )
	{
		if ( empty( $group ) ) {
			$group = 'default';
		}

		if ( $this->_exists( $key, $group ) ) {
			$found = true;
			++$this->cache_hits;
			if ( is_object( $this->cache[$group][$key] ) ) {
				return clone $this->cache[$group][$key];
			}

			return $this->cache[$group][$key];
		}

		$found = false;
		++$this->cache_misses;

		return false;
	}

	/**
	 * Retrieves multiple values from the cache in one call.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $keys  array of keys under which the cache contents are stored
	 * @param string $group Optional. Where the cache contents are grouped. Default 'default'.
	 * @param bool   $force Optional. Whether to force an update of the local cache
	 *                      from the persistent cache. Default false.
	 *
	 * @return array array of values organized into groups
	 */
	public function get_multiple( $keys, $group = 'default', $force = false )
	{
		$values = [];

		foreach ( $keys as $key ) {
			$values[$key] = $this->get( $key, $group, $force );
		}

		return $values;
	}

	/**
	 * Increments numeric cache item's value.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $key    The cache key to increment
	 * @param int        $offset Optional. The amount by which to increment the item's value. Default 1.
	 * @param string     $group  Optional. The group the key is in. Default 'default'.
	 *
	 * @return false|int the item's new value on success, false on failure
	 */
	public function incr( $key, $offset = 1, $group = 'default' )
	{
		if ( empty( $group ) ) {
			$group = 'default';
		}

		if ( ! $this->_exists( $key, $group ) ) {
			return false;
		}

		if ( ! is_numeric( $this->cache[$group][$key] ) ) {
			$this->cache[$group][$key] = 0;
		}

		$offset = (int) $offset;

		$this->cache[$group][$key] += $offset;

		if ( $this->cache[$group][$key] < 0 ) {
			$this->cache[$group][$key] = 0;
		}

		return $this->cache[$group][$key];
	}

	/**
	 * Replaces the contents in the cache, if contents already exist.
	 *
	 * @since 1.0.0
	 * @see Cache::set()
	 *
	 * @param int|string $key    what to call the contents in the cache
	 * @param mixed      $data   the contents to store in the cache
	 * @param string     $group  Optional. Where to group the cache contents. Default 'default'.
	 * @param int        $expire Optional. When to expire the cache contents. Default 0 (no expiration).
	 *
	 * @return bool false if not exists, true if contents were replaced
	 */
	public function replace( $key, $data, $group = 'default', $expire = 0 )
	{
		if ( empty( $group ) ) {
			$group = 'default';
		}

		$id = $key;

		if ( ! $this->_exists( $id, $group ) ) {
			return false;
		}

		return $this->set( $key, $data, $group, (int) $expire );
	}

	/**
	 * Sets the data contents into the cache.
	 *
	 * The cache contents are grouped by the $group parameter followed by the
	 * $key. This allows for duplicate IDs in unique groups. Therefore, naming of
	 * the group should be used with care and should follow normal function
	 * naming guidelines outside of core WordPress usage.
	 *
	 * The $expire parameter is not used, because the cache will automatically
	 * expire for each time a page is accessed and PHP finishes. The method is
	 * more for cache plugins which use files.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $key    what to call the contents in the cache
	 * @param mixed      $data   the contents to store in the cache
	 * @param string     $group  Optional. Where to group the cache contents. Default 'default'.
	 * @param int        $expire not Used
	 *
	 * @return true always returns true
	 */
	public function set( $key, $data, $group = 'default', $expire = 0 )
	{
		if ( empty( $group ) ) {
			$group = 'default';
		}

		if ( is_object( $data ) ) {
			$data = clone $data;
		}

		$this->cache[$group][$key] = $data;

		return true;
	}

	/**
	 * Echoes the stats of the caching.
	 *
	 * Gives the cache hits, and cache misses. Also prints every cached group,
	 * key and the data.
	 *
	 * @since 1.0.0
	 */
	public function stats()
	{
		echo '<p>';
		echo "<strong>Cache Hits:</strong> {$this->cache_hits}<br />";
		echo "<strong>Cache Misses:</strong> {$this->cache_misses}<br />";
		echo '</p>';
		echo '<ul>';

		foreach ( $this->cache as $group => $cache ) {
			echo '<li><strong>Group:</strong> ' . esc_html( $group ) . ' - ( ' . number_format( strlen( serialize( $cache ) ) / 1024, 2 ) . 'k )</li>';
		}
		echo '</ul>';
	}

	/**
	 * Serves as a utility function to determine whether a key exists in the cache.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $key   cache key to check for existence
	 * @param string     $group cache group for the key existence check
	 *
	 * @return bool whether the key exists in the cache for the given group
	 */
	protected function _exists( $key, $group )
	{
		return isset( $this->cache[$group] ) && ( isset( $this->cache[$group][$key] ) || array_key_exists( $key, $this->cache[$group] ) );
	}
}
$GLOBALS['cache'] = new Cache();
