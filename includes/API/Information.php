<?php

namespace MyCrud\Testing\API;

use WP_REST_Controller;
use WP_REST_Server;
use WP_Error;

/**
 * API class handling.
 */
class Information extends WP_REST_Controller {
	/**
	 * API constructor.
	 */
	public function __construct() {
		$this->namespace = 'crudteam/v1';
		$this->rest_base = 'information';
	}

	/**
	 * Register API routes.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);

		/**
		 * Register API routes for Readable.
		 */
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for object' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_item_schema' ),
			),
		);
	}

	/**
	 * Check if given request has access to read information.
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if given request has access delete specific information.
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_Error|bool
	 */
	public function delete_item_permissions_check( $request ) {
		return $this->get_item_permissions_check( $request );
	}

	/**
	 *
	 */
	public function delete_item( $request ) {
		$contact  = $this->get_contact( $request['id'] );
		$previous = $this->prepare_item_for_response( $contact, $request );
		$delete   = crud_delete_team_members_info( $request['id'] );
		if ( ! $delete ) {
			return new WP_Error(
				'rest_not_deleted',
				__( 'Sorry, information cannot be deleted.' ),
				array( 'status' => 400 )
			);
		}
		$data     = array(
			'deleted'  => true,
			'previous' => $previous->get_data(),
		);
		$response = rest_ensure_response( $data );
		return $data;
	}

	/**
	 * Check if given request has exist.
	 *
	 * @param int $id
	 */
	protected function get_contact( $id ) {
		$contact = crud_get_team_members_info( $id );
		if ( ! $contact ) {
			return new WP_Error(
				'rest_contact_invalid_id',
				__( 'Sorry, Information is not available' ),
				array( 'status' => 404 ),
			);
		}
		return $contact;
	}

	/**
	 * Check if given request has access to read information for single item (Read).
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function get_item_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		$contact = $this->get_contact( $request['id'] );
		if ( is_wp_error( $contact ) ) {
			return $contact;
		}
		return true;
	}
	/**
	 * Get information.
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response|WP_Error
	 */
	public function get_item( $request ) {
		$contact  = $this->get_contact( $request['id'] );
		$response = $this->prepare_item_for_response( $contact, $request );
		$response = rest_ensure_response( $response );
		return $response;
	}


	/**
	 * Retrieved the list of team members information.
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response|WP_Error
	 */
	public function get_items( $request ) {
		$args = array();

		$params = $this->get_collection_params();

		foreach ( $params as $key => $value ) {
			if ( isset( $request[ $key ] ) ) {
				$args[ $key ] = $request[ $key ];
			}
		}
		// Change per_page to number.
		$args['number'] = $args['per_page'];
		$args['offset'] = $args['number'] * ( $args['page'] - 1 );

		unset( $args['per_page'] );
		unset( $args['page'] );

		$data     = array();
		$contacts = get_crud_team_members_info( $args );

		foreach ( $contacts as $contact ) {
			$response = $this->prepare_item_for_response( $contact, $request );
			$data[]   = $this->prepare_response_for_collection( $response );
		}
		$total    = get_crud_team_members_table_row_count();
		$max_page = ceil( $total / (int) $args['number'] );
		$response = rest_ensure_response( $data );
		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $max_page );

		return $response;
	}

	/**
	 * Item prepare for response.
	 *
	 * @param mixed            $item WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object
	 * @return WP_REST_Response|\WP_Error
	 */
	public function prepare_item_for_response( $item, $request ) {
		$data   = array();
		$fields = $this->get_fields_for_response( $request );

		if ( in_array( 'id', $fields, true ) ) {
			$data['id'] = (int) $item->id;
		}
		if ( in_array( 'name', $fields, true ) ) {
			$data['name'] = $item->name;
		}
		if ( in_array( 'designation', $fields, true ) ) {
			$data['designation'] = $item->designation;
		}
		if ( in_array( 'email', $fields, true ) ) {
			$data['email'] = $item->email;
		}
		if ( in_array( 'phone', $fields, true ) ) {
			$data['phone'] = $item->phone;
		}
		if ( in_array( 'address', $fields, true ) ) {
			$data['address'] = $item->address;
		}
		if ( in_array( 'bio', $fields, true ) ) {
			$data['bio'] = $item->bio;
		}
		if ( in_array( 'date', $fields, true ) ) {
			$data['date'] = mysql_to_rfc3339( $item->created_at );
		}

		$context  = ! empty( $_REQUEST['context'] ) ? $_REQUEST['context'] : 'view';
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		$response->add_links( $this->prepare_links( $item ) );

		return $response;
	}

	/**
	 * Prepares the links for the response.
	 *
	 * @param object $item
	 * @return array
	 */
	protected function prepare_links( $item ) {
		$base  = sprintf(
			'%s/%s',
			$this->namespace,
			$this->rest_base
		);
		$links = array(
			'self'       => array(
				'href' => rest_url( trailingslashit( $base ) . $item->id ),
			),
			'collection' => array(
				'href' => rest_url( $base ),
			),
		);
		return $links;
	}

	/**
	 * Get Schema of item.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema       = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'Information',
			'type'       => 'object',
			'properties' => array(
				'id'          => array(
					'description' => __( 'Unique identifier for the object.' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'        => array(
					'description' => __( 'Name of the team member' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'designation' => array(
					'description' => __( 'Designation of the team member' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'email'       => array(
					'description' => __( 'Email address of a team member' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'phone'       => array(
					'description' => __( 'Phone Number of the team member' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'address'     => array(
					'description' => __( 'Address of the team member' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'bio'         => array(
					'description' => __( 'Bio details of the team member' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_textarea_field',
					),
				),
				'date'        => array(
					'description' => __( 'The date of object was publish' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
			),
		);
		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}

	/**
	 * Retrieve the query params for collection.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();
		unset( $params['search'] );
		return $params;
	}
}
