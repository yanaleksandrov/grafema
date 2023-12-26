<?php
use Grafema\{
	Esc,
	I18n,
	Json,
	View,
	Debug,
	Hook,
	Errors,
	Sanitizer,
	Helpers\Arr
};

/**
 *
 *
 * @package Grafema
 */
class Form {

	use \Grafema\Patterns\Multiton;

	/**
	 * Unique ID of form class instance.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public string $uniqid;

	/**
	 * List of all form fields
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	public array $fields = [];

	/**
	 * Form default attributes
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	public array $attributes = [
		'method' => 'POST',
	];

	/**
	 * ID of the field before which the new field will be added
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public string $before = '';

	/**
	 * ID of the field after which the new field will be added
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public string $after = '';

	/**
	 * ID of the field to be replaced with new field
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public string $instead = '';

	/**
	 * Register new form.
	 *
	 * @param string $uniqid
	 * @param array $attributes
	 * @param callable|null $function
	 * @return void|Form
	 */
	public static function register( string $uniqid, array $attributes = [], ?callable $function = null ) {
		// TODO:: wrong escaping, use sanitize
		$uniqid = Esc::html( $uniqid, false );
		if ( empty( $uniqid ) ) {
			new Errors(
				Debug::get_backtrace(),
				sprintf( I18n::__( 'The $uniqid of the form is empty.' ), $uniqid )
			);

			return;
		}

		$form = self::init( $uniqid );
		if ( isset( $form->uniqid ) ) {
			new Errors(
				Debug::get_backtrace(),
				sprintf( I18n::__( 'The form identified by %s already exists! Potential conflicts detected!' ), $uniqid )
			);
		}

		$form->uniqid     = $uniqid;
		$form->attributes = array_merge( [ 'method' => 'POST' ], $attributes );

		if ( is_callable( $function ) ) {
			call_user_func( $function, $form );
		}

		return $form;
	}

	/**
	 * Get all data of form by name.
	 *
	 * @param string $uniqid
	 * @param callable|null $function
	 * @return Form
	 */
	public static function get( string $uniqid, ?callable $function = null ): Form {
		$form = self::init( $uniqid );

		if ( is_callable( $function ) ) {
			call_user_func( $function, $form );
		}

		return $form;
	}

	/**
	 * Form output
	 *
	 * @param string $uniqid
	 * @param bool $without_form_wrapper
	 * @return string|Errors
	 */
	public static function view( string $uniqid, bool $without_form_wrapper = false ): Errors|string {
		$form   = self::init( $uniqid );
		$fields = $form->fields ?? [];

		/**
		 * Override form fields
		 *
		 * @since 1.0.0
		 * @param array $fields Fields list of form.
		 * @param array $uniqid ID of form.
		 */
		$fields = Hook::apply( 'grafema_form_view_' . $uniqid, $fields, $form );
		if ( ! array( $fields ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'Form fields is incorrect.' ) );
		}

		/**
		 * Override form html
		 *
		 * @since 1.0.0
		 * @param string $content Parsed fields html
		 */
		$content = $form->parseFields( $fields );
		$content = Hook::apply( 'grafema_form_view_html_' . $uniqid, $content );

		/**
		 * Return only the contents of the form without its wrapper
		 *
		 * @since 1.0.0
		 */
		if ( $without_form_wrapper ) {
			return $content;
		}

		// add form wrapper & return form
		return $form->wrap( $form->attributes, $content );
	}

	/**
	 * Add 'form' tag wrapper for form content.
	 *
	 * @param array $attributes
	 * @param string $content
	 * @return string
	 */
	public function wrap( array $attributes, string $content = '' ): string {
		$wrapper = sprintf( "<form%s>\n%s</form>\n", Arr::toHtmlAtts( $attributes ), $content );

		/**
		 * Override form wrapper
		 *
		 * @since 1.0.0
		 * @param array  $wrapper    Form wrapper markup.
		 * @param array  $attributes Form html attributes.
		 * @param string $content    Parsed form content.
		 * @param Form   $this       Instance of current class.
		 */
		return Hook::apply( 'grafema_form_wrap', $wrapper, $attributes, $content, $this );
	}

	/**
	 * Get fields html from array
	 *
	 * @param array $fields
	 * @param int $step
	 * @return string
	 */
	public function parseFields( array $fields, int $step = 1 ): string {
		ob_start();
		View::part( 'templates/form/layout/tab-menu', [ 'fields' => $fields ] );
		$content = ob_get_clean();

		foreach ( $fields as $field ) {
			$type     = Sanitizer::key( $field['type'] ?? '' );
			$name     = Sanitizer::key( $field['name'] ?? '' );
			$callback = $field['callback'] ?? null;

			if ( in_array( $type, [ 'color', 'date', 'datetime-local', 'email', 'hidden', 'image', 'month', 'range', 'search', 'tel', 'text', 'time', 'url', 'week' ], true ) ) {
				$type = 'input';
			}

			// add required attributes & other manipulations
			$field['attributes'] = isset( $field['attributes'] ) && is_array( $field['attributes'] ) ? $field['attributes'] : [];
			if ( ! in_array( $type, [ 'tab', 'group' ], true ) ) {
				match ( $type ) {
					'step'     => $field['attributes']['x-wizard:step'] ??= '',
					'textarea' => $field['attributes']['x-textarea']    ??= '',
					'select'   => $field['attributes']['x-select']      ??= '',
					default    => '',
				};
				$field['attributes'] = array_merge(
					[
						'name'         => $name,
						'x-model.fill' => $name,
					],
					$field['attributes']
				);

				if ( $type === 'step' ) {
					unset( $field['attributes']['x-model.fill'] );
				}
			}

			// parse conditions
			if ( ! empty( $field['conditions'] ) ) {
				$field['conditions'] = $this->parseConditions( $field['conditions'] );
			}

			ob_start();
			switch ( $type ) {
				case 'tab':
				case 'step':
				case 'group':
					View::part(
						"templates/form/layout/{$type}",
						array_merge(
							[
								'columns'    => 2,
								'width'      => 100,
								'content'    => $this->parseFields( $field['fields'] ?? [], $step + 1 ),
								'step'       => $step++,
								'attributes' => array_merge(
									[
										'x-wizard:step' => '',
									],
									$field['attributes']
								),
							],
							$field
						)
					);
					break;
				case 'custom':
					if ( is_callable( $callback ) ) {
						echo call_user_func( $callback );
					}
					break;
				default:
					View::part( "templates/form/{$type}", $field );
					break;
			}
			$content .= ob_get_clean();
		}

		return $content;
	}

	/**
	 * Add fields to form
	 *
	 * @param array $fields
	 * @return void
	 */
	public function addFields( array $fields ): void {
		$this->fields = $fields;
	}

	/**
	 * Adding a new field to the end of the array with all fields.
	 *
	 * @param array $field
	 * @return void|Errors
	 */
	public function addField( array $field ) {
		$fields   = $this->fields ?? [];
		$field_id = trim( strval( $field['ID'] ?? '' ) );
		if ( empty( $field_id ) ) {
			return new Errors( Debug::get_backtrace(), I18n::__( 'It is not possible to add a field with an empty ID.' ) );
		}

		$insert_places = array_filter( [ $this->after, $this->before, $this->instead ] );
		$last_place    = end( $insert_places ) ?? 0;
		$position      = array_search( $last_place, array_column( $fields, 'ID' ), true );

		if ( $this->after ) {
			if ( $position === array_key_last( $fields ) ) {
				$this->fields[] = $field;
			} else {
				$this->fields = Arr::insert( $fields, $position, [ $field ] );
			}
		} elseif ( $this->before ) {
			$position--;
			if ( $position < 0 ) {
				array_unshift( $this->fields, $field );
			} else {
				$this->fields = Arr::insert( $fields, $position, [ $field ] );
			}
		} elseif ( $this->instead && $position >= 0 ) {
			$this->fields[ $position ] = $field;
		} else {
			$this->fields[] = $field;
		}

		$this->after   = '';
		$this->before  = '';
		$this->instead = '';
	}

	/**
	 * @param $conditions_list
	 * @return string
	 * @throws JsonException
	 */
	public function parseConditions( $conditions_list ) {
		$jsString = '';
		if ( $conditions_list ) {
			$conditions = [];

			foreach ( $conditions_list as $condition ) {
				$field    = $condition['field'];
				$operator = $condition['operator'];
				$value    = $condition['value'];

				$condition = '';

				switch ( $operator ) {
					case '!==':
						$condition = "$field !== ''";
						break;
					case '===':
						if ( is_array( $value ) ) {
							$values    = Json::encode( $value );
							$condition = "$values.includes($field)";
						} else {
							$condition = "$field === '$value'";
						}
						break;
					case 'contains':
						$values    = str_replace( '"', "'", Json::encode( $value ) );
						$condition = "$values.includes($field)";
						break;
					case 'pattern':
						$values    = Json::encode( $value );
						$condition = "$values.some(value => value.test($field))";
						break;
				}

				if ( $condition !== '' ) {
					$conditions[] = $condition;
				}
			}

			$jsString = implode( ' && ', $conditions );
		}
		return $jsString;
	}

	/**
	 * Insert a new field after the specified one.
	 *
	 * @param string $field_id
	 * @return Form
	 */
	public function after( string $field_id ): Form {
		$this->after = $field_id;

		return $this;
	}

	/**
	 * Insert a new field before the specified one.
	 *
	 * @param string $field_id
	 * @return Form
	 */
	public function before( string $field_id ): Form {
		$this->before = $field_id;

		return $this;
	}

	/**
	 * Insert a new field instead the specified one.
	 *
	 * @param string $field_id
	 * @return Form
	 */
	public function instead( string $field_id ): Form {
		$this->instead = $field_id;

		return $this;
	}
}
