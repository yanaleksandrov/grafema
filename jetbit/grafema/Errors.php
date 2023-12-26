<?php
namespace Grafema;

/**
 * Errors class.
 *
 * A class whose task is to simplify error handling (messages) when working with Grafema.
 * In order to start working with a class, you need to create an instance of it, and then add or remove errors (messages) to it.
 * The class applies the $message and $data parameters.
 * These are strings and in general they are similar, but they separated by logic:
 * $message is a message for the user and $data is a message for the developer.
 *
 * @since 1.0.0
 */
class Errors {

	/**
	 * Format for recording the time of the event.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public string $format = 'Y-m-d H:i:s';

	/**
	 * Stores the list of errors.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public array $errors = [];

	/**
	 * Stores the list of data for error codes.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public array $error_data = [];

	/**
	 * Initialize the error.
	 *
	 * If `$code` is empty, the other parameters will be ignored.
	 * When `$code` is not empty, `$message` will be used even if
	 * it is empty. The `$data` parameter will be used only if it
	 * is not empty.
	 *
	 * Though the class is constructed with a single error code and
	 * message, multiple codes can be added using the `add()` method.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code    Error code.
	 * @param string     $message Error message.
	 * @param mixed      $data    Optional. Error data.
	 */
	public function __construct( string|int $code = '', string $message = '', $data = '' ) {
		if ( empty( $code ) ) {
			return;
		}

		$this->add( $code, $message, $data );
	}

	/**
	 * Retrieve all error codes.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of error codes, if available.
	 */
	public function get_error_codes() {
		if ( ! $this->has_errors() ) {
			return [];
		}
		return array_keys( $this->errors );
	}

	/**
	 * Retrieve first error code available.
	 *
	 * @since 1.0.0
	 *
	 * @return string|int Empty string, if no error codes.
	 */
	public function get_error_code() {
		$codes = $this->get_error_codes();

		return $codes[0] ?? '';
	}

	/**
	 * Retrieve all error messages or error messages matching code.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Optional. Retrieve messages matching code, if exists.
	 * @return array Error strings on success, or empty array on failure (if using code parameter).
	 */
	public function get_error_messages( $code = '' ) {
		// Return all messages if no code specified.
		if ( empty( $code ) ) {
			$all_messages = [];
			foreach ( (array) $this->errors as $messages ) {
				$all_messages = array_merge( $all_messages, $messages );
			}
			return $all_messages;
		}

		return $this->errors[ $code ] ?? [];
	}

	/**
	 * Get single error message.
	 *
	 * This will get the first message available for the code. If no code is
	 * given then the first code available will be used.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Optional. Error code to retrieve message.
	 * @return string
	 */
	public function get_error_message( $code = '' ) {
		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}
		$messages = $this->get_error_messages( $code );
		if ( empty( $messages ) ) {
			return '';
		}
		return $messages[0];
	}

	/**
	 * Retrieve error data for error code.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Optional. Error code.
	 * @return mixed Error data, if it exists.
	 */
	public function get_error_data( $code = '' ) {
		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}

		if ( isset( $this->error_data[ $code ] ) ) {
			return $this->error_data[ $code ];
		}
	}

	/**
	 * Verify if the instance contains errors.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function has_errors() {
		return ! empty( $this->errors );
	}

	/**
	 * Add an error or append additional message to an existing error.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code    Error code.
	 * @param string     $message Error message.
	 * @param mixed      $data    Optional. Error data.
	 */
	public function add( $code, $message, $data = '' ) {
		$this->errors[ $code ][] = [
			'delay'    => 0,
			'fragment' => $message,
			'method'   => 'update',
			'target'   => '[name="email"]',
			'time'     => ( new \DateTime() )->format( $this->format ),
		];

		if ( ! empty( $data ) ) {
			$this->error_data[ $code ] = [
				'delay'    => 0,
				'fragment' => $data,
				'method'   => 'update',
				'target'   => '[name="email"]',
				'time'     => ( new \DateTime() )->format( $this->format ),
			];
		}
	}

	/**
	 * Add data for error code.
	 *
	 * The error code can only contain one error data.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed      $data Error data.
	 * @param string|int $code Error code.
	 */
	public function add_data( $data, $code = '' ) {
		if ( empty( $code ) ) {
			$code = $this->get_error_code();
		}
		$this->error_data[ $code ] = $data;
	}

	/**
	 * Removes the specified error.
	 *
	 * This function removes all error messages associated with the specified
	 * error code, along with any error data for that code.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Error code.
	 */
	public function remove( $code ) {
		unset( $this->errors[ $code ] );
		unset( $this->error_data[ $code ] );
	}
}
