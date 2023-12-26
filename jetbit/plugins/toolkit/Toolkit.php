<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@grafema.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
use Grafema\Asset;
use Grafema\I18n;
use Grafema\Is;
use Grafema\Tree;

/**
 * Boilerplate plugin.
 *
 * @since 1.0.0
 */
class Toolkit implements Grafema\Plugins\Skeleton
{
	public function manifest(): array
	{
		return [
			'name'         => I18n::__( 'Toolkit' ),
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

	public function launch()
	{
		if ( ! Is::dashboard() ) {
			return;
		}

		Asset::enqueue( 'toolkit-main', '/plugins/toolkit/assets/css/main.css' );

		Tree::attach(
			'dashboard-main-menu',
			function ( $tree ) {
				$tree->addItems(
					[
						[
							'id'           => 'toolkit',
							'url'          => 'forms-builder',
							'title'        => I18n::__( 'Dev toolkit' ),
							'capabilities' => ['manage_options'],
							'icon'         => 'ph ph-brackets-curly',
							'position'     => 800,
						],
						[
							'id'           => 'fields-builder',
							'url'          => 'fields-builder',
							'title'        => I18n::__( 'Fields builder' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 0,
							'parent_id'    => 'toolkit',
						],
						[
							'id'           => 'forms-builder',
							'url'          => 'forms-builder',
							'title'        => I18n::__( 'Forms builder' ),
							'capabilities' => ['manage_options'],
							'icon'         => '',
							'position'     => 0,
							'parent_id'    => 'toolkit',
						],
					]
				);
			}
		);

		/*
		 * Sign In form
		 *
		 * @since 1.0.0
		 */
		Form::register(
			'builder/fields',
			[
				'class'  => 'dg p-7 g-7',
				'x-data' => "{type: '', tab:'general'}",
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'name'    => 'manage',
							'type'    => 'group',
							'label'   => I18n::__( 'Managing fields' ),
							'class'   => '',
							'columns' => '',
							'fields'  => [
								[
									'name'        => 'general',
									'type'        => 'tab',
									'label'       => I18n::__( 'General' ),
									'caption'     => '',
									'description' => '',
									'icon'        => 'ph ph-cube',
									'fields'      => [
										[
											'type'        => 'select',
											'label'       => I18n::__( 'Field Type' ),
											'name'        => 'type',
											'value'       => '',
											'placeholder' => '',
											'class'       => 'df aic fs-12 t-muted',
											'reset'       => 0,
											'required'    => 0,
											'before'      => '',
											'after'       => '',
											'tooltip'     => '',
											'instruction' => '',
											'attributes'  => [
												'x-select' => '{"showSearch": 1}',
											],
											'options' => [
												'main' => [
													'label'   => I18n::__( 'Basic' ),
													'options' => [
														'input' => [
															'content' => I18n::__( 'Text' ),
															'icon'    => 'ph ph-text-t',
														],
														'textarea' => [
															'content' => I18n::__( 'Text Area' ),
															'icon'    => 'ph ph-textbox',
														],
														'number' => [
															'content' => I18n::__( 'Number' ),
															'icon'    => 'ph ph-hash',
														],
														'range' => [
															'content' => I18n::__( 'Range (TODO)' ),
															'icon'    => 'ph ph-hash',
														],
														'email' => [
															'content' => I18n::__( 'Email (TODO)' ),
															'icon'    => 'ph ph-at',
														],
														'url' => [
															'content' => I18n::__( 'URL (TODO)' ),
															'icon'    => 'ph ph-link',
														],
														'password' => [
															'content' => I18n::__( 'Password' ),
															'icon'    => 'ph ph-password',
														],
														'submit' => [
															'content' => I18n::__( 'Submit Button' ),
															'icon'    => 'ph ph-paper-plane-tilt',
														],
														'date' => [
															'content' => I18n::__( 'Date Picker (TODO)' ),
															'icon'    => 'ph ph-calendar',
														],
														'date_time' => [
															'content' => I18n::__( 'Date & Time Picker (TODO)' ),
															'icon'    => 'ph ph-calendar',
														],
														'time' => [
															'content' => I18n::__( 'Time Picker (TODO)' ),
															'icon'    => 'ph ph-clock-countdown',
														],
														'color' => [
															'content' => I18n::__( 'Color Picker (TODO)' ),
															'icon'    => 'ph ph-swatches',
														],
													],
												],
												'content' => [
													'label'   => I18n::__( 'Content' ),
													'options' => [
														'image' => [
															'content' => I18n::__( 'Image' ),
															'icon'    => 'ph ph-image-square',
														],
														'media' => [
															'content' => I18n::__( 'Media' ), // TODO: gallery instead
															'icon'    => 'ph ph-images-square',
														],
														'editor' => [
															'content' => I18n::__( 'WYSIWYG editor (TODO)' ),
															'icon'    => 'ph ph-image-square',
														],
														'uploader' => [
															'content' => I18n::__( 'Uploader' ),
															'icon'    => 'ph ph-paperclip',
														],
														'progress' => [
															'content' => I18n::__( 'Progress' ),
															'icon'    => 'ph ph-spinner-gap',
														],
													],
												],
												'choice' => [
													'label'   => I18n::__( 'Choice' ),
													'options' => [
														'select' => [
															'content' => I18n::__( 'Select' ),
															'icon'    => 'ph ph-list-checks',
														],
														'checkbox' => [
															'content' => I18n::__( 'Checkbox' ),
															'icon'    => 'ph ph-toggle-left',
														],
														'radio' => [
															'content' => I18n::__( 'Radio Button' ),
															'icon'    => 'ph ph-radio-button',
														],
													],
												],
												'relations' => [
													'label'   => I18n::__( 'Relations' ),
													'options' => [
														'link' => [
															'content' => I18n::__( 'Link (TODO)' ),
															'icon'    => 'ph ph-link-simple',
														],
														'post' => [
															'content' => I18n::__( 'Post Object (TODO)' ),
															'icon'    => 'ph ph-note',
														],
														'user' => [
															'content' => I18n::__( 'User (TODO)' ),
															'icon'    => 'ph ph-user',
														],
													],
												],
												'additional' => [
													'label'   => I18n::__( 'Layout' ),
													'options' => [
														'details' => [
															'content' => I18n::__( 'Details' ), // TODO: rename context menu
															'icon'    => 'ph ph-dots-three-outline-vertical',
														],
														'divider' => [
															'content' => I18n::__( 'Divider' ),
															'icon'    => 'ph ph-arrows-out-line-vertical',
														],
														'step' => [
															'content' => I18n::__( 'Step' ),
															'icon'    => 'ph ph-steps',
														],
														'tab' => [
															'content' => I18n::__( 'Tab' ),
															'icon'    => 'ph ph-tabs',
														],
														'repeater' => [
															'content' => I18n::__( 'Repeater (TODO)' ),
															'icon'    => 'ph ph-infinity',
														],
													],
												],
											],
											'conditions' => [],
										],
										[
											'name'        => 'label',
											'type'        => 'text',
											'label'       => I18n::__( 'Field Label' ),
											'label_class' => 'df aic fs-12 t-muted',
											'before'      => '',
											'tooltip'     => '',
											'instruction' => I18n::__( 'This is the name which will appear on the EDIT page' ),
											'attributes'  => [
												'value'       => 'Title',
												'placeholder' => I18n::__( 'Field label' ),
											],
										],
										[
											'name'        => 'name',
											'type'        => 'text',
											'label'       => I18n::__( 'Field Name' ),
											'label_class' => 'df aic fs-12 t-muted',
											'before'      => '',
											'tooltip'     => '',
											'instruction' => I18n::__( 'Single word, no spaces. Underscores and dashes allowed' ),
											'attributes'  => [
												'value'       => '',
												'placeholder' => I18n::__( 'Field label' ),
											],
										],
										[
											'name'        => 'value',
											'type'        => 'text',
											'label'       => I18n::__( 'Default Value' ),
											'label_class' => 'df aic fs-12 t-muted',
											'before'      => '',
											'tooltip'     => '',
											'instruction' => I18n::__( 'Appears when creating a new post' ),
											'attributes'  => [
												'value' => '',
											],
										],
										[
											'name'        => 'options',
											'type'        => 'textarea',
											'label'       => I18n::__( 'Options' ),
											'class'       => 'df aic fs-12 t-muted',
											'value'       => 'red:Red',
											'before'      => '',
											'after'       => '',
											'tooltip'     => '',
											'limits'      => 0,
											'required'    => 0,
											'placeholder' => I18n::__( 'Placeholder text' ),
											'instruction' => I18n::__( 'Enter each choice on a new line. You must specify both the value and the label as follows: red:Red' ),
											'conditions'  => [
												[
													'field'    => 'type',
													'operator' => 'contains', // value contains
													'value'    => ['select'],
												],
											],
										],
									],
								],
								[
									'name'        => 'validation',
									'type'        => 'tab',
									'label'       => I18n::__( 'Validation' ),
									'caption'     => '',
									'description' => '',
									'icon'        => 'ph ph-shield-check',
									'fields'      => [
										[
											'type'        => 'checkbox',
											'label'       => '',
											'name'        => 'requirements',
											'value'       => '',
											'placeholder' => '',
											'class'       => '',
											'reset'       => 0,
											'required'    => 0,
											'before'      => '',
											'after'       => '',
											'tooltip'     => '',
											'instruction' => I18n::__( 'The form will not be saved if it is not filled in' ),
											'attributes'  => [],
											'conditions'  => [],
											'options'     => [
												'required' => I18n::__( 'Required' ),
											],
										],
									],
								],
								[
									'name'        => 'presentation',
									'type'        => 'tab',
									'label'       => I18n::__( 'Presentation' ),
									'caption'     => '',
									'description' => '',
									'icon'        => 'ph ph-presentation-chart',
									'fields'      => [
										[
											'name'        => 'label_class',
											'type'        => 'text',
											'label'       => I18n::__( 'Label class' ),
											'label_class' => 'df aic fs-12 t-muted',
											'before'      => '',
											'tooltip'     => '',
											'instruction' => '',
											'attributes'  => [
												'placeholder' => I18n::__( 'e.g. df aic fs-12 t-muted' ),
											],
										],
										[
											'name'        => 'before',
											'type'        => 'text',
											'label'       => I18n::__( 'Before content' ),
											'label_class' => 'df aic fs-12 t-muted',
											'before'      => '',
											'tooltip'     => '',
											'instruction' => '',
											'attributes'  => [
												'placeholder' => I18n::__( 'e.g. <i class="ph ph-bug"></i>' ),
											],
										],
										[
											'name'        => 'after',
											'type'        => 'text',
											'label'       => I18n::__( 'After content' ),
											'label_class' => 'df aic fs-12 t-muted',
											'before'      => '',
											'tooltip'     => '',
											'instruction' => '',
											'attributes'  => [
												'placeholder' => I18n::__( 'e.g. Mb' ),
											],
										],
										[
											'name'        => 'reset',
											'type'        => 'select',
											'label'       => I18n::__( 'Show reset button' ),
											'label_class' => 'df aic fs-12 t-muted',
											'value'       => '',
											'reset'       => false,
											'attributes'  => [
												'x-select' => '',
											],
											'options' => [
												'yes' => I18n::__( 'Yes' ),
												'no'  => I18n::__( 'No' ),
											],
										],
										[
											'name'        => 'copy',
											'type'        => 'select',
											'label'       => I18n::__( 'Show copy button' ),
											'label_class' => 'df aic fs-12 t-muted',
											'value'       => '',
											'reset'       => false,
											'attributes'  => [
												'x-select' => '',
											],
											'options' => [
												'yes' => I18n::__( 'Yes' ),
												'no'  => I18n::__( 'No' ),
											],
										],
										[
											'name'        => 'description',
											'type'        => 'textarea',
											'value'       => '',
											'default'     => '',
											'label'       => I18n::__( 'Description' ),
											'class'       => 'df aic fs-12 t-muted',
											'reset'       => false,
											'before'      => '',
											'after'       => '',
											'tooltip'     => '',
											'placeholder' => I18n::__( 'e.g. Mb' ),
											'instruction' => I18n::__( 'Use this field to output instructions or additional explanations' ),
											'attributes'  => [],
											'conditions'  => [],
										],
									],
								],
							],
						],
					]
				);
			}
		);
	}

	public function activate()
	{
		// do something when plugin is activated
	}

	public function deactivate()
	{
		// do something when plugin is deactivated
	}

	public function install()
	{
		// do something when plugin is installed
	}

	public function uninstall()
	{
		// do something when plugin is uninstalled
	}
}
