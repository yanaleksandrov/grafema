<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

class Debug
{
	public static function check(): void
	{
		if ( defined( 'GRFM_DEBUG' ) && GRFM_DEBUG ) {
			ini_set( 'error_reporting', E_ALL );
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
			error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
		}
	}

	/**
	 * Считает время выполнения PHP кода (в секундах).
	 *
	 * @param string $phase run             - включает подсчет: первый раз или после паузы (по умолчанию).
	 *                      start           - очищает все кэши и включает подсчет - clear & run.
	 *                      get             - получает разницу, между предыдущим вызовом функции.
	 *                      getall|end|stop - получает разницу, между первым вызовом функции (run).
	 *                      pause           - временная остановка подсчета. exec_time() для продолжения.
	 *                      clear           - полностью очищает результат. exec_time() для начала нового подсчета.
	 * @param int    $round до скольки знаков после запятой округлять результат
	 *
	 * @return float|void Example 0.03654
	 *
	 * @ver: 3.4.3
	 */
	public static function timer( string $phase = 'run', int $round = 2, string $txt = 's' )
	{
		static $prev_time, $collect;

		if ( $phase === 'start' ) {
			$collect = $prev_time = 0;
		} elseif ( $phase === 'clear' ) {
			return $collect = $prev_time = 0;
		}

		[$usec, $sec] = explode( ' ', microtime() );
		$mctime       = bcadd( $usec, $sec, 8 );

		if ( $prev_time ) {
			$exectime = $mctime - $prev_time; // bcsub( $mctime, $prev_time, 8 );
			$collect += $exectime; // bcadd( $collect, $exectime, 8 );
		}
		$prev_time = $mctime;

		if ( $phase === 'pause' ) {
			$prev_time = 0;
		} elseif ( $phase === 'get' ) {
			return round( $exectime, $round ) . $txt;
		} elseif ( in_array( $phase, ['getall', 'end', 'stop'], true ) ) {
			return round( $collect, $round ) . $txt;
		}
	}
	
	/**
	 * Возвращает количество памяти в байтах, которое было выделено PHP-скрипту.
	 *
	 * @param bool $real_usage
	 * @return string
	 */
	public static function memory( bool $real_usage = false ): string
	{
		return self::convert( memory_get_usage( $real_usage ) );
	}

	/**
	 * Возвращает пиковое значение объема памяти, выделенное PHP.
	 */
	public static function memory_peak( bool $real_usage = false ): string
	{
		return self::convert( memory_get_peak_usage( $real_usage ) );
	}

	/**
	 * Возвращает процент используемой памяти от общего количества выделенной.
	 *
	 * @return false|string
	 */
	public static function memory_limit( bool $relative = true ): bool|string
	{
		if ( $relative ) {
			return round( memory_get_usage() / ( (int) ini_get( 'memory_limit' ) * 1024 * 1024 ) * 100, 2 ) . '%';
		}

		return ini_get( 'memory_limit' );
	}

	/**
	 * Converts bytes.
	 */
	public static function convert( int $size ): string
	{
		$i    = floor( log( $size, 1024 ) );
		$unit = ['b', 'kb', 'mb', 'gb'];

		return round( $size / pow( 1024, $i ), 1 ) . $unit[$i];
	}

	/**
	 * Получает конфигурационную информацию кеша.
	 *
	 * @return mixed|void
	 */
	public static function opcache( string $field )
	{
		$directives = opcache_get_configuration();
		if ( isset( $directives['directives']['opcache.' . $field] ) ) {
			return $directives['directives']['opcache.' . $field];
		}
	}

	/**
	 * Generates an error ID to use when logging errors.
	 * It is formed as a string that separates the name of the class, method, and line in the file with a slash.
	 * This makes it convenient to search for the source of errors for subsequent debugging.
	 *
	 * @since 1.0.0
	 */
	public static function get_backtrace(): string
	{
		$backtrace = self::backtrace();
		if ( isset( $backtrace[1]['file'], $backtrace[1]['line'] ) ) {
			return str_replace( GRFM_PATH, '', $backtrace[1]['file'] ) . '/' . $backtrace[1]['line'];
		}

		return '';
	}

	/**
	 * Gets the source file of a function or method call.
	 *
	 * @since 1.0.0
	 */
	public static function get_source( $class, $function )
	{
		$backtrace = self::backtrace();

		foreach ( $backtrace as $k => $trace ) {
			if ( isset( $trace['class'], $trace['function'] ) && $trace['class'] === $class && $trace['function'] === $function ) {
				++$k;
				break;
			}
		}

		return $backtrace[$k]['file'] ?? '';
	}

	/**
	 * TODO: output some debug code only for specified user.
	 *
	 * @param int $user_id User ID
	 * @param ?callable $function
	 */
	public static function only( int $user_id, ?callable $function ): void
	{
		echo '<pre>';
		$function();
		echo '</pre>';
	}

	/**
	 * Generates a backtrace.
	 *
	 * @since 1.0.0
	 */
	private static function backtrace(): array
	{
		return debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
	}
}
