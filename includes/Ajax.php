<?php

namespace MyCrud\Testing;

/**
 * Admin class handling.
 */
class Ajax {

	/**
	 * Ajax constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_crud_team_enquiry', array( $this, 'crud_test_input_form' ) );
		add_action( 'wp_ajax_nopriv_crud_team_enquiry', array( $this, 'crud_test_input_form' ) ); // For Guest user.
		add_action( 'wp_ajax_crud-test-info-delete', array( $this, 'crud_test_info_delete' ) );
	}

	/**
	 * Ajax input form.
	 *
	 * @return void
	 */
	public function crud_test_input_form() {
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'crud-enquiry-form' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verification valid', 'crud-test' ),
				)
			);
		}
		wp_send_json_success(
			array(
				'message' => __( 'Successful', 'crud-test' ),
			)
		);
	}

	/**
	 * Ajax Delete
	 *
	 * @return void
	 */
	public function crud_test_info_delete() {

		$id       = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
		$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '';

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $wp_nonce, 'crud_test_ajax_admin_nonce' ) ) {
			die( esc_html__( 'Security check', 'crud-test' ) );
		}

		if ( crud_delete_team_members_info( $id ) ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}
}
