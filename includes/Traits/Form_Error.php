<?php

namespace MyCrud\Testing\Traits;

/**
 * Error handling trait.
 */
trait Form_Error {

	/**
	 * Declare a public array.
	 *
	 * @var array
	 */
	public $error = array();

	/**
	 * Checks whether the form has errors or not.
	 *
	 * @param string $key The error key.
	 * @return boolean
	 */
	public function has_error( $key ) {
		if ( isset( $this->error[ $key ] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Gets the error.
	 *
	 * @param string $key The error key.
	 * @return string|boolean
	 */
	public function get_error( $key ) {
		if ( isset( $this->error[ $key ] ) ) {
			return $this->error[ $key ];
		}
		return false;
	}

}
