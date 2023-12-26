<?php
namespace Grafema;

use Hook\Hook;

use Grafema\Users\Users;
use Grafema\Helpers\Hash;

/**
 * User
 */
class User extends Users {

	/**
	 * Session key
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private static string $session_id = DB_PREFIX . 'user_logged';

	/**
	 * Current user data.
	 *
	 * @since 1.0.0
	 */
	private static $current = null;

	/**
	 * Capabilities that the individual user has been granted outside those inherited from their role.
	 *
	 * @var   array Array of key/value pairs where keys represent a capability name
	 *              and boolean values represent whether the user has that capability.
	 * @since 1.0.0
	 */
	private static array $caps = [];

	public int $ID;
	public string $login;
	public string $password;
	public string $nicename;
	public string $showname;
	public string $email;

	/**
	 * The user roles.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	public array $roles;
	public string $registered;
	public bool $online;

	/**
	 * Sanitize user data before inserting into the database.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private static function data_sanitize( array $userdata ) {
		$schema    = DB::schema( DB_PREFIX . self::$table );
		$_userdata = [];
		foreach ( $userdata as $column => $value ) {
			if ( isset( $schema[ $column ] ) ) {
				if ( is_array( $value ) ) {
					$_userdata[ $column ] = $value;
				} else {
					$_userdata[ $column ] = Esc::sql( $value, $schema[ $column ] );
				}
			}
		}
		return $_userdata;
	}

	/**
	 * Insert a user into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $userdata {
	 *     @type int    $ID           User ID. If supplied, the user will be updated.
	 *     @type string $password     The plain-text user password.
	 *     @type string $login        The user's login username.
	 *     @type string $nicename     The URL-friendly username.
	 *     @type string $showname     The user's display name.
	 *     @type string $email        The user email address.
	 *     @type string $url          The user URL.
	 *     @type string $registered   Date the user registered. Format is 'Y-m-d H:i:s'.
	 *     @type string $status       User's status.
	 *     @type string $roles        User's roles.
	 * }
	 * @return array|User|bool The newly created user's ID or an Errors object if the user could not be created.
	 */
	public static function add( array $userdata ) {
		// leave only the allowed fields
		$userdata = array_filter(
			$userdata,
			function( $key ) use ( $userdata ) {
				return in_array( $key, [ 'ID', 'login', 'password', 'nicename', 'showname', 'email', 'roles', 'registered', 'online' ], true );
			},
			ARRAY_FILTER_USE_KEY
		);

		$login    = trim( (string) ( $userdata['login'] ?? '' ) );
		$email    = trim( (string) ( $userdata['email'] ?? '' ) );
		$password = trim( (string) ( $userdata['password'] ?? '' ) );
		$roles    = is_string( $userdata['roles'] ) && ! empty( $userdata['roles'] ) ? [ $userdata['roles'] ] : $userdata['roles'];

		$errors = new Errors();
		if ( empty( $login ) ) {
			$errors->add( __METHOD__, I18n::__( 'Cannot create a user with an empty login name.' ) );
		}

		if ( mb_strlen( $login ) > 60 ) {
			$errors->add( __METHOD__, I18n::__( 'Login may not be longer than 60 characters.' ) );
		}
		// check user by email or login
		$users = self::exists(
			[
				'login' => $login,
				'email' => $email,
			]
		);
		if ( $users ) {
			$errors->add( __METHOD__, I18n::__( 'Sorry, that email address or login is already used!' ) );
		}

		$all_errors = $errors->get_error_messages( __METHOD__ );
		if ( ! empty( $all_errors ) ) {
			return $all_errors;
		}

		/**
		 * Otherwise build a nicename and showname from the login.
		 */
		if ( empty( $userdata['nicename'] ) ) {
			$userdata['nicename'] = Esc::slug( $login );
		}
		if ( empty( $userdata['showname'] ) ) {
			$userdata['showname'] = ucfirst( Esc::slug( $login ) );
		}

		$nicename_check = DB::select( self::$table, 'ID', [ 'nicename' => $userdata['nicename'] ] );
		if ( $nicename_check ) {
			$suffix = 1;
			while ( $nicename_check ) {
				$suffix++;
				$nicename_check = DB::select( self::$table, 'ID', [ 'nicename' => $userdata['nicename'] . "-$suffix" ] );
			}
			$userdata['nicename'] = $userdata['nicename'] . "-$suffix";
		}

		if ( ! empty( $password ) ) {
			$userdata['password'] = password_hash( $password, PASSWORD_DEFAULT );
		} else {
			$userdata['password'] = Hash::generate();
		}

		// TODO:: add a check for the rights of creating a user with rights older than subscriber
		$userdata['roles'] = $roles ?? [ Option::get( 'users.roles' ) ];

		$user_count = DB::insert( self::$table, $userdata )->rowCount();
		if ( $user_count === 1 ) {
			$user = self::get( $login, 'login' );
			if ( $user instanceof User ) {

				/**
				 * Fires immediately after a new user is registered.
				 *
				 * @since 1.0.0
				 *
				 * @param User $user data of created user
				 */
				Hook::apply( 'grafema_user_added', $user );

				return $user;
			}
		}
		return false;
	}

	/**
	 * Получение данных о пользователе
	 *
	 * @since 1.0.0
	 */
	public static function get( $value, string $get_by = 'ID' ): Errors|User|bool {
		$allowed_fields = [ 'ID', 'login', 'email', 'nicename' ];
		if ( ! in_array( $get_by, $allowed_fields, true ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Sorry, to get a user, use an ID, login, nicename or email.' ) );
		}

		$user = DB::select( self::$table, '*', [ $get_by => $value ], [ 'LIMIT' => 1 ] );
		if ( isset( $user[0] ) && is_array( $user[0] ) ) {
			$_user = new self();
			foreach ( $user[0] as $field => $value ) {
				$_user->$field = $field === 'roles' ? unserialize( $value ) : $value;
			}
			return $_user;
		}
		return false;
	}

	/**
	 * Update a user in the database.
	 * If no ID is found in the received array, the function passes the work to the add method.
	 *
	 * @since 1.0.0
	 */
	public static function update( array $userdata ) {
		$user_id = (int) ( $userdata['ID'] ?? 0 );
		if ( ! $user_id ) {
			return self::add( $userdata );
		}

		// login is the unchanged parameter of the user
		unset( $userdata['login'] );

		$user = DB::select( self::$table, 'ID', [ 'ID' => $user_id ], [ 'LIMIT' => 1 ] );
		if ( ! $user ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'User not found.' ) );
		}

		// sanitize incoming data and exclusion of extraneous data
		$userdata = self::data_sanitize( $userdata );

		return DB::update( self::$table, $userdata )->rowCount();
	}

	/**
	 * Remove user and optionally reassign posts and links to another user.
	 *
	 * If the $reassign parameter is not assigned to a User ID, then all posts will
	 * be deleted of that user. The action {@see 'delete_user'} that is passed the User ID
	 * being deleted will be run after the posts are either reassigned or deleted.
	 * The user meta will also be deleted that are for that User ID.
	 *
	 * @since 1.0.0
	 *
	 * @param  int   $user_id  User ID.
	 * @param  int   $reassign Optional. Reassign posts to new User ID.
	 * @return Errors|int      The number of remote users or false.
	 */
	public static function delete( int $user_id, int $reassign = 0 ) {

		$fields = [
			'ID' => abs( $user_id ),
		];

		if ( ! self::exists( $fields ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'The user you are trying to delete does not exist.' ) );
		}

		if ( $reassign ) {

		}
		return DB::delete( self::$table, $fields )->rowCount();
	}

	/**
	 * Searches for users by the specified parameters
	 *
	 * @since 1.0.0
	 *
	 * @return bool Array of fields and values to search for users.
	 */
	public static function exists( array $fields ) {
		$users = DB::select( self::$table, '*', [ 'OR' => $fields ] );
		if ( $users ) {
			return true;
		}
		return false;
	}

	/**
	 * Returns whether a particular user has the specified capability.
	 *
	 * @since 1.0.0
	 *
	 * @param integer  $user_id      User ID.
	 * @param  string  $capabilities Capability name.
	 * @return   bool                Whether the user has the given capability.
	 */
	public static function can( int $user_id, $capabilities ) {
		$roles = [];
		$user  = self::current();
		if ( (int) $user->ID === $user_id ) {
			$roles = $user->roles ?? [];
		} else {
			$user = self::get( $user_id );
			if ( $user ) {
				$roles = $user->roles ?? [];
			}
		}

		if ( is_array( $roles ) ) {
			foreach ( $roles as $role ) {
				return Users\Roles::has_cap( $role, $capabilities );
			}
		}
		return false;
	}

	/**
	 * Checks whether the user is with the specified role.
	 *
	 * @since 1.0.0
	 *
	 * @param integer  $user_id  User ID.
	 * @param  string  $role     Role name.
	 * @return   bool            The user has a role.
	 */
	public static function is( int $user_id, string $role ) {
		$roles = [];
		$user  = self::current();
		if ( $user->ID === $user_id ) {
			$roles = $user->roles ?? [];
		} else {
			$user = self::get( $user_id );
			if ( $user ) {
				$roles = $user->roles ?? [];
			}
		}
		return in_array( $role, $roles, true );
	}

	/**
	 * Получает данные текущего, зарегистрированного пользователя.
	 *
	 * @return Errors|false|User
	 * @since   1.0.0
	 */
	public static function current() {
		if ( self::$current ) {
			return self::$current;
		}

		Session::start();
		$user_id = abs( (int) Session::get( self::$session_id ) );
		if ( $user_id ) {
			self::$current = self::get( $user_id );
		}
		return self::$current;
	}

	/**
	 * Проверяет, залогинен ли пользователь в этом сеансе.
	 *
	 * @since   1.0.0
	 * @return   bool
	 */
	public static function logged() {
		Session::start();
		$user_id = abs( (int) Session::get( self::$session_id ) );
		if ( $user_id ) {
			return true;
		}
		return false;
	}

	/**
	 * Авторизует пользователя по паролю и логину/email.
	 *
	 * @param  string      $login_or_email
	 * @param  string      $password
	 * @param  bool        $remember
	 * @return Errors|User
	 * @since  1.0.0
	 */
	public static function login( string $login_or_email, string $password, bool $remember = true ): Errors|User {
		$field = Is::email( $login_or_email ) ? 'email' : 'login';
		if ( $remember ) {
			Session::start();
		} else {
			Session::start( 1 );
		}

		$user = self::get( trim( $login_or_email ), $field );
		if ( $user instanceof User && password_verify( trim( $password ), $user->password ) ) {
			Session::set( self::$session_id, $user->ID );

			self::$current = self::update(
				[
					'ID'     => $user->ID,
					'online' => true,
				]
			);

			return $user;
		}

		return new Errors( Debug::get_backtrace(), I18n::__( 'User not found: invalid login/email or incorrect password.' ) );
	}

	/**
	 * Де-авторизует текущего пользователя.
	 *
	 * @since   1.0.0
	 */
	public static function logout(): void {
		Session::start();
		$user_id       = abs( (int) Session::get( self::$session_id ) );
		self::$current = [];
		if ( $user_id ) {
			self::update(
				[
					'ID'     => $user_id,
					'online' => false,
				]
			);
		}
		Session::set( self::$session_id, null );
	}

	public static function addNonce( $action = -1 ) {
		$user_id = 0;
		return substr( hash_hmac( 'ripemd160', $action . '|' . $user_id, 'nonce' ), -12, 10 );
	}
}
