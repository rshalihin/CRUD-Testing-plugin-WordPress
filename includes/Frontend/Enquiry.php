<?php

namespace MyCrud\Testing\Frontend;

/**
 * Admin class handling.
 */
class Enquiry {

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_shortcode( 'crud-enquiry-shortcode', array( $this, 'crud_enquiry_shortcode_render' ) );
	}

	public function crud_enquiry_shortcode_render() {
		wp_enqueue_script( 'enquiry-script' );
		wp_enqueue_style( 'enquiry-style' );

		ob_start();

		include_once __DIR__ . '/views/enquiry.php';

		return ob_get_clean();
	}

}
