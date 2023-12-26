<?php

use Grafema\DB;
use Grafema\I18n;
use Grafema\Is;
use Hook\Hook;

/**
 * QueryMonitor plugin.
 *
 * @since 1.0.0
 */
class QueryMonitor implements Grafema\Plugins\Skeleton
{
	public function manifest(): array
	{
		return [
			'name'         => I18n::__( 'Query Monitor' ),
			'description'  => I18n::__( 'The developer tools panel for Grafema.' ),
			'author'       => 'Grafema Team',
			'email'        => '',
			'url'          => '',
			'license'      => 'GNU General Public License v3.0',
			'version'      => '1.0.0',
			'php'          => '8.2',
			'mysql'        => '5.7',
			'dependencies' => [],
		];
	}

	/**
	 * QueryMonitor plugin.
	 *
	 * @since 1.0.0
	 */
	public function launch(): void
	{
		if ( ! Is::dashboard() ) {
			return;
		}

		Hook::add(
			'grafema_dashboard_footer',
			function () {
				?>
				<template x-teleport="#query">
					<a class="menu__link" x-show="query" href="#">
						<i class="ph ph-monitor"></i> <?php printf( '%s %s %sQ', Grafema\Debug::timer( 'getall' ), Grafema\Debug::memory_peak(), DB::queries() ); ?>
					</a>
				</template>
				<?php
			}
		);
	}

	/**
	 * This is sample function.
	 *
	 * This is big description of current function.
	 * End second...
	 *
	 * @param mixed  $str     Some parameter description
	 * @param bool   $ret     Some return parameter. Default true.
	 * @param string $content Description. Default null. If edit,
	 *                        and other content.
	 *                        Start new paragraph!
	 * @param bool   $after   Description
	 *
	 * @category              CategoryName
	 *
	 * @copyright  1997-2005 The PHP Group
	 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
	 *
	 * @version    SVN: $Id$
	 *
	 * @global     string $gStr глобальная строчная переменная
	 *
	 * @see       http://pear.php.net/package/PackageName Указывает ссылку на документацию элемента.
	 * @since      1.2.0
	 * @deprecated 2.0.0
	 * @see        wp_signon()
	 */
	public function test( mixed $str, bool $ret, string $content, $after ): void {}

	public function activate() {}

	public function deactivate() {}

	public function install() {}

	public function uninstall() {}
}
