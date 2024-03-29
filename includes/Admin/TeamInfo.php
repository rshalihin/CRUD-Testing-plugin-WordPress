<?php
/**
 * Team members CRUD handler
 *
 * @package crud-testing
 */
namespace MyCrud\Testing\Admin;

use MyCrud\Testing\Traits\Form_Error;

/**
 * Team members CRUD handler class
 */
class TeamInfo {

	use Form_Error;

	/**
	 * Create custom route for team members information.
	 *
	 * @return void
	 */
	public function crud_testing_team_members_page() {

		$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : 'list';
		$id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

		switch ( $action ) {
			case 'new':
				$template = __DIR__ . '/view/crud-team-members-new.php';
				break;
			case 'edit':
				$information = crud_get_team_members_info( $id );
				$template    = __DIR__ . '/view/crud-team-members-edit.php';
				break;
			case 'view':
				$template = __DIR__ . '/view/crud-team-members-view.php';
				break;
			default:
				$template = __DIR__ . '/view/crud-team-members-list.php';
				break;
		}
		if ( file_exists( $template ) ) {
			include $template;
		}
	}

	/**
	 * Handles the form submission.
	 *
	 * @return void
	 */
	public function crud_testing_team_members_from_handle() {
		/**
		 * Basic form validation.
		 */
		if ( ! isset( $_POST['submit_member_info'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'add_team_member_info' ) ) {
			die( 'Sorry, Please try again in legal way.' );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			die( 'Sorry, Please try legal way again.' );
		}

		$id                           = isset( $_POST['team_id'] ) ? intval( $_POST['team_id'] ) : 0;
		$crud_team_member_name        = isset( $_POST['team_member_name'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_name'] ) ) : '';
		$crud_team_member_designation = isset( $_POST['team_member_designation'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_designation'] ) ) : '';
		$crud_team_member_email       = isset( $_POST['team_member_email'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_email'] ) ) : '';
		$crud_team_member_phone       = isset( $_POST['team_member_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_phone'] ) ) : '';
		$crud_team_member_address     = isset( $_POST['team_member_address'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_address'] ) ) : '';
		$crud_team_member_bio         = isset( $_POST['team_member_bio'] ) ? sanitize_textarea_field( wp_unslash( $_POST['team_member_bio'] ) ) : '';

		// Validate data before inserting.
		if ( empty( $crud_team_member_name ) ) {
			$this->error['name'] = 'Name cannot be empty';
		}
		if ( empty( $crud_team_member_designation ) ) {
			$this->error['designation'] = 'Designation cannot be empty';
		}
		if ( empty( $crud_team_member_email ) ) {
			$this->error['email'] = 'Email cannot be empty';
		}
		if ( empty( $crud_team_member_phone ) ) {
			$this->error['phone'] = 'Phone cannot be empty';
		}

		if ( ! empty( $this->error ) ) {
			return;
		}

		$args = array(
			'name'        => $crud_team_member_name,
			'designation' => $crud_team_member_designation,
			'email'       => $crud_team_member_email,
			'phone'       => $crud_team_member_phone,
			'address'     => $crud_team_member_address,
			'bio'         => $crud_team_member_bio,
		);
		if ( $id ) {
			$args['id'] = $id;
		}
		// Save the data to the database.
		$inserted_id = crud_test_team_member_info_insert( $args );

		// Check if data is saved or not.
		if ( is_wp_error( $inserted_id ) ) {
			wp_die( esc_html( $inserted_id->get_error_message() ) );
		}

		if ( $id ) {
			$redirected_to = admin_url( 'admin.php?page=crud-team-members&action=edit&crud-updated=true&id=' . $id );

		} else {
			// Redirect to the list page.
			$redirected_to = admin_url( 'admin.php?page=crud-team-members&inserted=true' );
		}

		wp_safe_redirect( $redirected_to );
		exit();

	}

	public function crud_delete_information( $id ) {

		// Verify nonce and capability.
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'crud-team-member-delete' ) ) {
			die( 'Sorry, Please try again in legal way.' );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			die( 'Sorry, Please try legal way again.' );
		}
		$id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

		// $delete_info = crud_delete_team_members_info( $id );
		if ( crud_delete_team_members_info( $id ) ) {
			// Redirect to the list page.
			$redirected_to = admin_url( 'admin.php?page=crud-team-members&crud-info-deleted=true' );
		} else {
			$redirected_to = admin_url( 'admin.php?page=crud-team-members&crud-info-deleted=false' );
		}
		wp_safe_redirect( $redirected_to );
		exit();
	}
}
