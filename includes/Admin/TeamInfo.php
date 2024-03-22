<?php
/**
 * Team members CRUD handler
 *
 * @package crud-testing
 */
namespace MyCrud\Testing\Admin;

 /**
  * Team members CRUD handler class
  */
class TeamInfo {

	/**
	 * Declare a public array.
	 *
	 * @var array
	 */
	public $error = array();
	/**
	 * Create custom route for team members information.
	 *
	 * @return void
	 */
	public function crud_testing_team_members_page() {

		$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';

		switch ( $action ) {
			case 'new':
				$template = __DIR__ . '/view/crud-team-members-new.php';
				break;
			case 'edit':
				$template = __DIR__ . '/view/crud-team-members-edit.php';
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

		$crud_team_member_name        = isset( $_POST['team_member_name'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_name'] ) ) : '';
		$crud_team_member_designation = isset( $_POST['team_member_designation'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_designation'] ) ) : '';
		$crud_team_member_email       = isset( $_POST['team_member_email'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_email'] ) ) : '';
		$crud_team_member_phone       = isset( $_POST['team_member_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_phone'] ) ) : '';
		$crud_team_member_address     = isset( $_POST['team_member_address'] ) ? sanitize_text_field( wp_unslash( $_POST['team_member_address'] ) ) : '';
		$crud_team_member_bio         = isset( $_POST['team_member_bio'] ) ? sanitize_textarea_field( wp_unslash( $_POST['team_member_bio'] ) ) : '';

		// Validate data before inserting.
		if ( empty( $crud_team_member_name ) ) {
			$error['name'] = 'Name cannot be empty';
		}
		if ( empty( $crud_team_member_designation ) ) {
			$error['designation'] = 'Designation cannot be empty';
		}
		if ( empty( $crud_team_member_email ) ) {
			$error['email'] = 'Email cannot be empty';
		}
		if ( empty( $crud_team_member_phone ) ) {
			$error['phone'] = 'Phone cannot be empty';
		}

		if ( isset( $error ) ) {
			return;
		}

		// Save the data to the database.
		$inserted_id = crud_test_team_member_info_insert(
			array(
				'name'        => $crud_team_member_name,
				'designation' => $crud_team_member_designation,
				'email'       => $crud_team_member_email,
				'phone'       => $crud_team_member_phone,
				'address'     => $crud_team_member_address,
				'bio'         => $crud_team_member_bio,
			)
		);

		// Check if data is saved or not.
		if ( is_wp_error( $inserted_id ) ) {
			wp_die( $inserted_id->get_error_message() );
		}

		$redirected_to = admin_url( 'admin.php?page=crud-team-members&insert=true' );
		// wp_redirect( $redirected_to );
		wp_safe_redirect( $redirected_to );
		exit();

	}
}
