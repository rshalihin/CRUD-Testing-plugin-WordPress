<?php
/**
 * CRUD Team Members List handler
 *
 * @package crud-testing
 */

namespace MyCrud\Testing\Admin;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
/**
 * Class TeamMembers
 *
 * @package crud-testing
 */

class CrudTeamMemberList extends \WP_List_Table {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'Team Member', // singular name of the listed records.
				'plural'   => 'Team Members', // plural name of the listed records.
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get Columns Name
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'          => '<input type="checkbox" />',
			'name'        => 'Name',
			'designation' => 'Designation',
			'email'       => 'Email',
			'phone'       => 'Phone',
			'address'     => 'Address',
		);
		return $columns;
	}

	/**
	 * Get Sortable Columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'name'       => array( 'name', true ),
			'created_at' => array( 'created_at', true ),
		);
		return $sortable_columns;
	}

	/**
	 * Prepare items for display.
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;
		$args         = array(
			'numbers' => $per_page,
			'offset'  => $offset,
		);
		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
			$args['order']   = $_REQUEST['order'];
			$args['orderby'] = $_REQUEST['orderby'];
		}

		$this->items = get_crud_team_members_info( $args );

		$this->set_pagination_args(
			array(
				'total_items' => get_crud_team_members_table_row_count(),
				'per_page'    => $per_page,
			)
		);
	}

	/**
	 * Default column
	 *
	 * @param array  $item Column.
	 * @param string $column_name Name of the column.
	 * @return array
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'value':
				break;
			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}


	/**
	 * Customize column name for checkbox
	 *
	 * @return array
	 */
	public function column_name( $item ) {
		$action = array();

		$action['edit'] = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>', admin_url( 'admin.php?page=crud-team-members&action=edit&id=' . $item->id ), $item->id, __( 'Edit', 'crud-test' ) );

		$action['delete'] = sprintf( '<a href="#" class="submitdelete" data-id="%s">%s</a>', $item->id, __( 'Delete', 'crud-test' ) );

		$item = sprintf(
			'<a href="%1$s"><strong>%2$s</strong></a>%3$s',
			admin_url( 'admin.php?page=crud-team-members&action=view&id=' . $item->id ),
			$item->name,
			$this->row_actions( $action )
		);
		return $item;
	}

	/**
	 * Customize column name for checkbox
	 *
	 * @return array
	 */
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="address_id[]" value="%d" />',
			$item->id
		);
	}



}
