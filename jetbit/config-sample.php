<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
/**
 * Constants for database name, user, password, host, prefix & charset.
 *
 * Indexes have a maximum size of 767 bytes. Historically, we haven't had to worry about this.
 * Utf8mb4 uses 4 bytes for each character. This means that an index that used to have room for
 * floor(767/3) = 255 characters now only has room for floor(767/4) = 191 characters.
 *
 * @since 1.0.0
 */
const DB_NAME             = 'db.name';
const DB_USER             = 'db.user';
const DB_PASSWORD         = 'db.password';
const DB_HOST             = 'db.host';
const DB_PREFIX           = 'db.prefix';
const DB_TYPE             = 'mysql';
const DB_CHARSET          = 'utf8mb4';
const DB_COLLATE          = '';
const DB_MAX_INDEX_LENGTH = 191;

/**
 * Constants for paths to Grafema directories.
 *
 * @since 1.0.0
 */
const GRFM_CORE      = __DIR__ . '/grafema/';
const GRFM_DASHBOARD = __DIR__ . '/dashboard/';
const GRFM_PLUGINS   = __DIR__ . '/plugins/';
const GRFM_THEMES    = __DIR__ . '/themes/';
const GRFM_UPLOADS   = __DIR__ . '/uploads/';

/**
 * Authentication unique keys and salts.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 1.0.0
 */
const GRFM_AUTH_KEY  = 'put your unique phrase here';
const GRFM_NONCE_KEY = 'put your unique phrase here';
const GRFM_HASH_KEY  = 'put your unique phrase here';

/**
 * Debug mode.
 *
 * @since 1.0.0
 */
const GRFM_DEBUG     = true;
const GRFM_DEBUG_LOG = true;

/**
 * System versions.
 *
 * @since 1.0.0
 */
const GRFM_VERSION                = '1.0.0';
const GRFM_REQUIRED_PHP_VERSION   = '8.1';
const GRFM_REQUIRED_MYSQL_VERSION = '5.6';

/**
 * Cron intervals.
 *
 * @since 1.0.0
 */
const GRFM_HOUR_IN_SECONDS     = 3600;
const GRFM_HALF_DAY_IN_SECONDS = 43200;
const GRFM_DAY_IN_SECONDS      = 86400;
