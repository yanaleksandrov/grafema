<?php
list( $label, $name, $value, $placeholder, $class, $reset, $before, $after, $instruction, $tooltip, $copy, $attributes, $conditions, $options ) = array_values(
	( new Grafema\Sanitizer() )->apply(
		$args,
		[
			'label'       => 'trim',
			'name'        => 'key',
			'value'       => 'attribute',
			'placeholder' => 'trim',
			'class'       => 'class:df aic jcsb fw-600',
			'reset'       => 'bool:false',
			'before'      => 'trim',
			'after'       => 'trim',
			'instruction' => 'trim',
			'tooltip'     => 'attribute',
			'copy'        => 'bool:false',
			'attributes'  => 'array',
			'conditions'  => 'array',
			'options'     => 'array',
		]
	)
);
// field array
[
	'type'        => 'textarea',
	'label'       => I18n::__( 'Description' ),
	'name'        => 'description',
	'value'       => '',
	'placeholder' => '',
	'class'       => 'df aic fs-12 t-muted',
	'reset'       => 0,
	'required'    => 0,
	'before'      => '',
	'after'       => '',
	'tooltip'     => '',
	'instruction' => '',
	'attributes'  => [],
	'conditions'  => [],
	// password
	'switcher'    => 1,
	'generator'   => 0,
	'indicator'   => 0,
	'characters'  => [
		'lowercase' => 2,
		'uppercase' => 2,
		'special'   => 2,
		'length'    => 12,
		'digit'     => 2,
	],
	// details: dropdown with button
	'content'     => '',
	// simple select/checkbox/radio options
	'options'     => [
		'one' => I18n::__( '#1' ),
		'two' => I18n::__( '#2' ),
	],
	// select options with additional data
	'options'     => [
		'one' => [
			'content'     => I18n::__( '#1' ),
			'icon'        => 'ph ph-image-square',
			'image'       => 'path to image',
			'description' => '',
		],
		'two' => [
			'content'     => I18n::__( '#2' ),
			'icon'        => 'ph ph-image-square',
			'image'       => 'path to image',
			'description' => '',
		],
	],
	// select options with optgroups
	'options'     => [
		'optgroup' => [
			'label'   => I18n::__( 'Optgroup Label' ),
			'options' => [
				'one' => [
					'content'     => I18n::__( '#1' ),
					'icon'        => 'ph ph-image-square',
					'image'       => 'path to image',
					'description' => '',
				],
			],
		],
	],
];

// header
[
	'type'        => 'header',
	'label'       => I18n::__( 'Welcome to Grafema' ),
	'name'        => 'title',
	'class'       => '',
	'instruction' => '',
];

// group
[
	'type'    => 'group',
	'label'   => '',
	'name'    => 'manage',
	'class'   => 'df aic fs-12 t-muted',
	'content' => '',
	'columns' => '',
	'fields'  => [],
];

// step
[
	'type'       => 'step',
	'name'       => 'manage',
	'step'       => 'tab',
	'content'    => '',
	'attributes' => [],
];

// tabs
[
	'type'          => 'tab',
	'label'         => I18n::__( 'General' ),
	'name'          => 'general',
	'caption'       => '',
	'description'   => '',
	'icon'          => 'ph ph-cube',
	'class_button'  => '', // css class for tab menu button
	'class_content' => '', // css class for tab content
	'fields'        => [],
];
