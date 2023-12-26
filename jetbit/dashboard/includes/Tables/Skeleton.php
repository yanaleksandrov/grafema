<?php
namespace Tables;

/**
 *
 *
 * @since 1.0.0
 */
interface Skeleton {

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function render();

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function wrapper();

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function columns();

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function row();

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function cell();

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function sort();
}
