<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Dir;

use Grafema\Sanitizer;

/**
 * Class Directory.
 *
 * A class that represents a directory on a file system.
 */
class Dir
{
	/**
	 * The path to this directory.
	 */
	public string $path;

	/**
	 * Permissions of directory.
	 */
	public ?int $permissions;

	/**
	 * Check directory is exists.
	 */
	public bool $exists = false;

	/**
	 * Directory constructor.
	 */
	public function __construct( string $path )
	{
		$this->path   = Sanitizer::path( $path );
		$this->exists = file_exists( $this->path );
		if ( is_dir( $this->path ) ) {
			$this->permissions = substr( sprintf( '%o', fileperms( $this->path ) ), -4 );
		}
	}

	/**
	 * Create this directory, if it does not already exist.
	 */
	public function create( int $mode = 0755 ): Dir
	{
		if ( ! is_dir( $this->path ) ) {
			mkdir( $this->path, $mode, true );
		}

		return $this;
	}

	/**
	 * Recursively clear the folder from the contents.
	 */
	public function clear( string $filepath = '' ): Dir
	{
		$path = $filepath ?: $this->path;
		if ( is_dir( $path ) ) {
			$paths = glob( $path . '/*' );

			foreach ( $paths as $filepath ) {
				if ( is_file( $filepath ) ) {
					unlink( $filepath );
				} elseif ( is_dir( $filepath ) ) {
					$this->clear( $filepath );
					rmdir( $filepath );
				}
			}
		}

		return $this;
	}

	/**
	 * Recursively delete a directory.
	 */
	public function delete(): Dir
	{
		if ( is_dir( $this->path ) ) {
			$this->clear();
			if ( rmdir( $this->path ) ) {
				$this->path = '';
			}
		}

		return $this;
	}

	/**
	 * Rename directory.
	 */
	public function rename( string $dirname ): Dir
	{
		if ( is_dir( $this->path ) ) {
			$dirpath = sprintf( '%s%s%s', dirname( $this->path ), DIRECTORY_SEPARATOR, $dirname );
			if ( ! is_dir( $dirpath ) && rename( $this->path, $dirpath ) ) {
				$this->path = $dirpath;
			}
		}

		return $this;
	}

	public function getFolders( int $depth = 0 ): array
	{
		$search = function ( $path, int $current_depth ) use ( &$search, $depth ) {
			$flags   = GLOB_ONLYDIR | GLOB_NOSORT | GLOB_ERR;
			$folders = glob( Sanitizer::path( $path . '/*' ), $flags );

			if ( $current_depth < $depth ) {
				$subfolders = glob( $path . '/*', GLOB_ONLYDIR | GLOB_NOSORT );

				foreach ( $subfolders as $folder ) {
					$folders = array_merge( $folders, $search( $folder, $current_depth + 1 ) );
				}
			}

			return $folders;
		};

		return $search( $this->path, 0 );
	}

	/**
	 * Perform a glob on the directory.
	 */
	public function getFiles( string $pattern = '*', int $depth = 0 ): array
	{
		$search = function ( $path, int $current_depth ) use ( &$search, $pattern, $depth ) {
			$flags = GLOB_BRACE | GLOB_NOSORT | GLOB_MARK | GLOB_ERR;
			$files = glob( Sanitizer::path( $path . '/' . $pattern ), $flags );

			if ( $current_depth < $depth ) {
				$folders = glob( $path . '/*', GLOB_ONLYDIR | GLOB_NOSORT );

				foreach ( $folders as $folder ) {
					$files = array_merge( $files, $search( $folder, $current_depth + 1 ) );
				}
			}

			return array_filter( $files, 'is_file' );
		};

		return $search( $this->path, 0 );
	}

	/**
	 * Get the total size of the directory.
	 */
	public function getSize(): int
	{
		$files = $this->getFiles( '*', 9999 );
		$size  = 0;

		foreach ( $files as $file ) {
			$size += filesize( $file );
		}

		return $size;
	}

	/**
	 * Get path to directory.
	 */
	public function getPath(): string
	{
		return $this->path;
	}
}
