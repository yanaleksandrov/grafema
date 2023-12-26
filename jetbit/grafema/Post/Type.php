<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Post;

use Grafema\DB;
use Grafema\Helpers\Arr;
use Grafema\I18n;
use Grafema\Route;
use Grafema\Tree;

/**
 * Core class used for interacting with post types.
 */
class Type
{
	/**
	 * Posts types list.
	 *
	 * @since 1.0.0
	 */
	public static array $types = [];

	/**
	 * Posts statuses list.
	 *
	 * @since 1.0.0
	 */
	public static array $statuses = ['publish', 'pending', 'draft', 'protected', 'private', 'trash', 'future'];

	/**
	 * Registers a post type.
	 *
	 * @param string $post_type Post type key. Must not exceed 20 characters and may
	 *                          only contain lowercase alphanumeric characters, dashes,
	 *                          and underscores. See sanitize_key().
	 * @param array  $args      {
	 *                          Array or string of arguments for registering a post type
	 *
	 * @var string      $label Name of the post type shown in the menu. Usually plural.
	 *                  Default is value of $labels['name'].
	 * @var array       $labels An array of labels for this post type. If not set, post
	 *                  labels are inherited for non-hierarchical types and page
	 *                  labels for hierarchical ones. See get_post_type_labels() for a full
	 *                  list of supported labels.
	 * @var string      $description A short descriptive summary of what the post type is.
	 *                  Default empty.
	 * @var bool        $public Whether a post type is intended for use publicly either via
	 *                  the admin interface or by front-end users. While the default
	 *                  settings of $exclude_from_search, $publicly_queryable, $show_ui,
	 *                  and $show_in_nav_menus are inherited from public, each does not
	 *                  rely on this relationship and controls a very specific intention.
	 *                  Default false.
	 * @var bool        $hierarchical Whether the post type is hierarchical (e.g. page). Default false.
	 * @var bool        $exclude_from_search Whether to exclude posts with this post type from front end search
	 *                  results. Default is the opposite value of $public.
	 * @var bool        $publicly_queryable Whether queries can be performed on the front end for the post type
	 *                  as part of parse_request(). Endpoints would include:
	 *                  * ?post_type={post_type_key}
	 *                  * ?{post_type_key}={single_post_slug}
	 *                  * ?{post_type_query_var}={single_post_slug}
	 *                  If not set, the default is inherited from $public.
	 * @var bool        $show_ui Whether to generate and allow a UI for managing this post type in the
	 *                  admin. Default is value of $public.
	 * @var bool|string $show_in_menu Where to show the post type in the admin menu. To work, $show_ui
	 *                  must be true. If true, the post type is shown in its own top level
	 *                  menu. If false, no menu is shown. If a string of an existing top
	 *                  level menu (eg. 'tools.php' or 'edit.php?post_type=page'), the post
	 *                  type will be placed as a sub-menu of that.
	 *                  Default is value of $show_ui.
	 * @var bool        $show_in_nav_menus Makes this post type available for selection in navigation menus.
	 *                  Default is value of $public.
	 * @var bool        $show_in_admin_bar Makes this post type available via the admin bar. Default is value
	 *                  of $show_in_menu.
	 * @var bool        $show_in_rest Whether to include the post type in the REST API. Set this to true
	 *                  for the post type to be available in the block editor.
	 * @var string      $rest_base To change the base url of REST API route. Default is $post_type.
	 * @var string      $rest_controller_class REST API Controller class name. Default is 'WP_REST_Posts_Controller'.
	 * @var int         $menu_position The position in the menu order the post type should appear. To work,
	 *                  $show_in_menu must be true. Default null (at the bottom).
	 * @var string      $menu_icon The url to the icon to be used for this menu. Pass a base64-encoded
	 *                  SVG using a data URI, which will be colored to match the color scheme
	 *                  -- this should begin with 'data:image/svg+xml;base64,'. Pass the name
	 *                  of a Dashicons helper class to use a font icon, e.g.
	 *                  'dashicons-chart-pie'. Pass 'none' to leave div.wp-menu-image empty
	 *                  so an icon can be added via CSS. Defaults to use the posts icon.
	 * @var string      $capability_type The string to use to build the read, edit, and delete capabilities.
	 *                  May be passed as an array to allow for alternative plurals when using
	 *                  this argument as a base to construct the capabilities, e.g.
	 *                  array('story', 'stories'). Default 'post'.
	 * @var array       $capabilities Array of capabilities for this post type. $capability_type is used
	 *                  as a base to construct capabilities by default.
	 *                  See get_post_type_capabilities().
	 * @var bool        $map_meta_cap Whether to use the internal default meta capability handling.
	 *                  Default false.
	 * @var array       $supports Core feature(s) the post type supports. Serves as an alias for calling
	 *                  add_post_type_support() directly. Core features include 'title',
	 *                  'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt',
	 *                  'page-attributes', 'thumbnail', 'custom-fields', and 'post-formats'.
	 *                  Additionally, the 'revisions' feature dictates whether the post type
	 *                  will store revisions, and the 'comments' feature dictates whether the
	 *                  comments count will show on the edit screen. A feature can also be
	 *                  specified as an array of arguments to provide additional information
	 *                  about supporting that feature.
	 *                  Example: `array( 'my_feature', array( 'field' => 'value' ) )`.
	 *                  Default is an array containing 'title' and 'editor'.
	 * @var callable    $register_meta_box_cb Provide a callback function that sets up the meta boxes for the
	 *                  edit form. Do remove_meta_box() and add_meta_box() calls in the
	 *                  callback. Default null.
	 * @var array       $taxonomies An array of taxonomy identifiers that will be registered for the
	 *                  post type. Taxonomies can be registered later with register_taxonomy()
	 *                  or register_taxonomy_for_object_type().
	 *                  Default empty array.
	 * @var bool|string $has_archive Whether there should be post type archives, or if a string, the
	 *                  archive slug to use. Will generate the proper rewrite rules if
	 *                  $rewrite is enabled. Default false.
	 *                  }
	 * @var string|bool Sets the query_var key for this post type. Defaults to $post_type
	 *                  key. If false, a post type cannot be loaded at
	 *                  ?{query_var}={post_slug}. If specified as a string, the query
	 *                  ?{query_var_string}={post_slug} will be valid.
	 * @var bool        Whether to allow this post type to be exported. Default true.
	 * @var bool        Whether to delete posts of this type when deleting a user. If true,
	 *                  posts of this type belonging to the user will be moved to Trash
	 *                  when then user is deleted. If false, posts of this type belonging
	 *                  to the user will *not* be trashed or deleted. If not set (the default),
	 *                  posts are trashed if post_type_supports('author'). Otherwise posts
	 *                  are not trashed or deleted. Default null.
	 * @var bool        FOR INTERNAL USE ONLY! True if this post type is a native or
	 *                  "built-in" post_type. Default false.
	 * @var string      FOR INTERNAL USE ONLY! URL segment to use for edit link of
	 *                  this post type. Default 'post.php?post=%d'.
	 *                  }
	 *
	 * @since 1.0.0
	 */
	public static function register( string $post_type, array $args = [] )
	{
		// TODO:: add sanitize
		$post_type = trim( $post_type );

		if ( empty( $post_type ) || strlen( $post_type ) > 20 ) {
			// TODO:: add error to Errors
			return false;
		}

		if ( ! is_array( $args ) ) {
			// TODO:: add error to Errors
			return false;
		}

		$args = array_replace_recursive(
			[
				'labels' => [
					'name'        => I18n::__( 'Page' ),
					'name_plural' => I18n::__( 'Pages' ),
					'add'         => I18n::__( 'Add New' ),
					'edit'        => I18n::__( 'Edit Page' ),
					'update'      => I18n::__( 'Update Page' ),
					'view'        => I18n::__( 'View Page' ),
					'view_plural' => I18n::__( 'View Pages' ),
					'search'      => I18n::__( 'Search Pages' ),
					'not_found'   => I18n::__( 'Nothing found' ),
					'all_items'   => I18n::__( 'All Pages' ),
					'published'   => I18n::__( 'Page Published' ),
					'scheduled'   => I18n::__( 'Page Scheduled' ),
					'updated'     => I18n::__( 'Page Updated' ),
				],
				'description'  => '',
				'public'       => true,
				'hierarchical' => false,
				'searchable'   => true,
				'show_ui'      => true,
				'show_in_menu' => true,
				'position'     => 10,
				'menu_icon'    => 'icon-megaphone',
				'capabilities' => ['types_edit'],
				'supports'     => ['title', 'editor', 'thumbnail', 'fields'],
				'taxonomies'   => [],
				'can_export'   => true,
				'can_import'   => true,
				'route'        => null,
			],
			$args
		);

		if ( ! isset( self::$types[$post_type] ) ) {
			self::$types[$post_type] = $args;
		}

		$public       = (bool) ( $args['public'] ?? true );
		$show_in_menu = (bool) ( $args['show_in_menu'] ?? true );

		/*
		 * Add routes if type is public.
		 *
		 * @since 1.0.0
		 */
		if ( $public ) {
			$pattern = ( $args['route'] ?? $post_type ) . '/([a-z0-9-]+)/';
			$route   = new Route();
			$route->get(
				'/api/v1/page/(\d+)',
				function () {
					header( 'Content-Type: application/json' );
					echo json_encode(
						[
							'status'  => '404',
							'content' => 'route not defined',
						]
					);
					exit;
				}
			);
			$route->mount(
				'/' . $post_type,
				function () use ( $route ) {
					$route->get(
						'/',
						function () {
							echo 'overview';
						}
					);
					$route->get(
						'/(\d+)',
						function ( $id ) {
							echo 'id ' . htmlentities( $id );
						}
					);
				}
			);
			$route->run();
		}

		/*
		 * Show in dashboard menu.
		 * TODO:: add items to menu only in dashboard
		 *
		 * @since 1.0.0
		 */
		if ( $show_in_menu ) {
			Tree::attach(
				'dashboard-main-menu',
				function ( $tree ) use ( $post_type, $args ) {
					$tree->addItems(
						[
							[
								'id'           => $post_type,
								'url'          => $post_type,
								'title'        => $args['labels']['name_plural'],
								'capabilities' => $args['capabilities'],
								'icon'         => $args['menu_icon'],
								'position'     => $args['position'],
							],
							[
								'id'           => sprintf( 'type-%s', $post_type ),
								'url'          => $post_type,
								'title'        => $args['labels']['all_items'],
								'capabilities' => $args['capabilities'],
								'parent_id'    => $post_type,
							],
						]
					);
				}
			);
		}

		/**
		 * DataBase table schema.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		$schema = DB::schema();

		$db_post_type  = DB_PREFIX . $post_type;
		$update_schema = false;
		if ( empty( $schema[$db_post_type] ) ) {
			$update_schema = DB::query( self::migrate( $post_type ) )->fetchAll();
		}
		if ( empty( $schema[$db_post_type . 'fields'] ) && in_array( 'fields', $args['supports'], true ) ) {
			$update_schema = DB::query( self::metaQuery( $post_type ) )->fetchAll();
		}
	}

	/**
	 * Unregister post type.
	 *
	 * @since 1.0.0
	 */
	public static function unregister( string $post_type )
	{
		if ( isset( self::$types[$post_type] ) ) {
			unset( self::$types[$post_type] );
		}
	}

	/**
	 * Check a post type's support for a given feature.
	 *
	 * @param string $post_type the post type being checked
	 * @param string $feature   the feature being checked
	 *
	 * @return bool whether the post type supports the given feature
	 *
	 * @since  1.0.0
	 */
	public static function supports( string $post_type, string $feature )
	{
		return  ! empty( self::$types[$post_type] ) && in_array( $feature, self::$types[$post_type]['supports'], true );
	}

	/**
	 * Retrieves data (objects) of registered record types.
	 * Not the records themselves, but the record type registration data.
	 * You can filter the output by a variety of criteria.
	 *
	 * @param array  $args     Array of criteria by which posts types will be selected.
	 *                         For the value of each parameter, see the description of the "Type::register" method.
	 * @param string $operator Optional. The logical operation to perform. 'or' means only one
	 *                         element from the array needs to match; 'and' means all elements
	 *                         must match; 'not' means no elements may match. Default 'and'.
	 *
	 * @since 1.0.0
	 */
	public static function fetch( $args = [], $operator = 'and' ): array
	{
		return Arr::filter( self::$types ?? [], $args, $operator );
	}

	/**
	 * Get registered types for using in select field.
	 *
	 * @since 1.0.0
	 */
	public static function get(): array
	{
		$types = [];

		foreach ( self::$types as $key => $type ) {
			$types[$key] = sprintf( '%s (%s)', $type['labels']['name_plural'] ?? '', $key );
		}

		return $types;
	}

	/**
	 * Get white list of posts statuses.
	 *
	 * @return array|string[]
	 */
	public static function getStatuses()
	{
		return self::$statuses;
	}

	/**
	 * Get white list of posts statuses for use in select.
	 *
	 * @return array|string[]
	 */
	public static function getStatusesOptions()
	{
		return self::$statuses;
	}

	/**
	 * Check if type registered.
	 *
	 * @return bool
	 */
	public static function exist( $type )
	{
		return true;
	}

	/**
	 * Get query for create new table into database.
	 *
	 * @since 1.0.0
	 */
	public static function migrate( string $post_type )
	{
		$table = DB_PREFIX . $post_type;

		$charset_collate = '';
		if ( DB_CHARSET ) {
			$charset_collate = 'DEFAULT CHARACTER SET ' . DB_CHARSET;
		}
		if ( DB_COLLATE ) {
			$charset_collate .= ' COLLATE ' . DB_COLLATE;
		}

		return '
		CREATE TABLE IF NOT EXISTS ' . $table . " (
			ID          int          unsigned NOT NULL auto_increment,
			author      int          unsigned NOT NULL default '0',
			parent      int          unsigned NOT NULL default '0',
			views       int          unsigned NOT NULL default '0',
			comments    mediumint    unsigned NOT NULL default '0',
			position    mediumint    unsigned NOT NULL default '0',
			title       text         NOT NULL default '',
			content     longtext     NOT NULL default '',
			created     datetime     NOT NULL default NOW(),
			modified    datetime     NOT NULL default NOW(),
			status      varchar(255) NOT NULL default 'draft',
			slug        varchar(255) NOT NULL default '',
			password    varchar(255) NOT NULL default '',
			discussion  varchar(255) NOT NULL default 'open',
			PRIMARY KEY (ID),
			KEY slug (slug(" . DB_MAX_INDEX_LENGTH . ')),
			KEY parent (parent),
			KEY author (author),
			FULLTEXT KEY content (title,content)
		) ' . $charset_collate . ';

		CREATE TRIGGER ' . $table . '_update_date_modified
			BEFORE UPDATE ON ' . $table . '
			FOR EACH ROW
			BEGIN
				IF NEW.title <> OLD.title OR NEW.content <> OLD.content THEN
					SET NEW.modified = NOW();
				END IF;
			END;
		';
	}

	/**
	 * Get query for create new table into database.
	 *
	 * @since 1.0.0
	 */
	public static function metaQuery( string $post_type )
	{
		$table            = DB_PREFIX . $post_type;
		$max_index_length = DB_MAX_INDEX_LENGTH;

		$charset_collate = '';
		if ( DB_CHARSET ) {
			$charset_collate = 'DEFAULT CHARACTER SET ' . DB_CHARSET;
		}
		if ( DB_COLLATE ) {
			$charset_collate .= ' COLLATE ' . DB_COLLATE;
		}

		return "
		CREATE TABLE IF NOT EXISTS {$table}_fields (
			field_id bigint(20) unsigned NOT NULL auto_increment PRIMARY KEY,
			post_id  bigint(20) unsigned NOT NULL default '0',
			`key`    varchar(255) default NULL,
			value    longtext,
			KEY      post_id (post_id),
			KEY      `key` (`key`({$max_index_length}))
		) {$charset_collate};

		CREATE TRIGGER {$table}_cascade_delete
			AFTER DELETE ON {$table}
			FOR EACH ROW
				BEGIN
					DELETE FROM {$table}_fields WHERE post_id = OLD.ID;
				END;
		";
	}
}
