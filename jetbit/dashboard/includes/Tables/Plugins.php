<?php

namespace Tables;

use Grafema\I18n;
use Grafema\View;
use Grafema\Sanitizer;

class Plugins extends Builder implements Skeleton
{
	public function render()
	{
		$output  = '';
		$columns = $this->columns();
		if ( $columns ) {
			$output .= '<div class="table" x-data="table" x-init="$ajax(\'extensions/get\').then(response => items = response)" style="' . $this->stylize( $columns ) . '">';
			$output .= '<!-- table header start -->';
			$output .= '<template x-if="items.length">';
			$output .= '<div class="table__head">';

			ob_start();
			View::part(
				'templates/table/header',
				[
					'title' => I18n::__( 'Plugins' ),
				]
			);
			$output .= ob_get_clean();

			$output .= '<div class="table__row">';

			foreach ( $columns as $key => $column ) {
				ob_start();
				View::part( 'templates/table/cells/head', $column + ['key' => $key] );
				$output .= ob_get_clean();
			}
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</template>';

			$output .= '<!-- table rows list start -->';
			$output .= '<template x-for="item in items">';

			ob_start();

			foreach ( $columns as $key => $column ) {
				$cell = Sanitizer::trim( $column['cell'] ?? '' );
				View::part(
					'templates/table/cells/' . $cell,
					[
						'column' => [
							'key' => $key,
							...$column,
						],
					]
				);
			}
			$output .= '<div class="table__row hover">' . ob_get_clean() . '</div>';
			$output .= '</template>';
			ob_start();
			?>
			<template x-if="!items.length">
				<?php
				View::part(
					'templates/states/undefined',
					[
						'title'       => I18n::__( 'Plugins are not installed yet' ),
						'description' => I18n::__( 'You can download them manually or install from the repository' ),
					]
				);
			?>
			</template>
			<?php
			$output .= ob_get_clean();
			$output .= '</div>';
		}
		echo $output;
	}

	public function columns(): array
	{
		return [
			'cb' => [
				'cell'       => 'cb',
				'title'      => '<input type="checkbox" x-bind="trigger">',
				'width'      => '1rem',
				'flexible'   => false,
				'sortable'   => false,
				'filterable' => false,
			],
			'reviews' => [
				'cell'       => 'raw',
				'title'      => '<i class="ph ph-hash-straight"></i>',
				'width'      => '1rem',
				'flexible'   => false,
				'sortable'   => false,
				'filterable' => false,
			],
			'image' => [
				'cell'       => 'image',
				'title'      => '',
				'width'      => '2.5rem',
				'flexible'   => false,
				'sortable'   => false,
				'filterable' => false,
			],
			'title' => [
				'cell'       => 'title',
				'title'      => I18n::__( 'Title' ),
				'width'      => '22rem',
				'flexible'   => true,
				'sortable'   => true,
				'filterable' => true,
			],
			'author' => [
				'cell'       => 'links',
				'title'      => I18n::__( 'Author' ),
				'width'      => '6rem',
				'flexible'   => true,
				'sortable'   => false,
				'filterable' => true,
			],
			'categories' => [
				'cell'       => 'links',
				'title'      => I18n::__( 'Categories' ),
				'width'      => '6rem',
				'flexible'   => true,
				'sortable'   => false,
				'filterable' => true,
			],
			'date' => [
				'cell'       => 'date',
				'title'      => I18n::__( 'Date' ),
				'width'      => '9rem',
				'flexible'   => false,
				'sortable'   => true,
				'filterable' => true,
			],
		];
	}

	/**
	 * @since 1.0.0
	 */
	public function wrapper()
	{
		return sprintf( '<div %s>%s</div>' );
	}

	public function row()
	{
		return sprintf( '<div %s>%s</div>' );
	}

	public function cell()
	{
		return sprintf( '<div %s>%s</div>' );
	}

	public function sort()
	{
		// TODO: Implement sort() method.
	}

	public function modify() {}
}
