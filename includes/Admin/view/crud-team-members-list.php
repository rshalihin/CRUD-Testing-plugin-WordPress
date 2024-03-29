<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'CRUD Team Members', 'crud-test' ); ?></h1>
	<a href="<?php echo esc_html( admin_url( 'admin.php?page=crud-team-members&action=new' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New CRUD Team Members', 'crud-test' ); ?></a>

	<?php
	if ( isset( $_GET['inserted'] ) ) {
		?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Information has been saved', 'crud-test' ); ?></p>
		</div>
		<?php
	}
	if ( isset( $_GET['crud-info-deleted'] ) && $_GET['crud-info-deleted'] == 'true' ) {
		?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Information has been Deleted successfully', 'crud-test' ); ?></p>
		</div>
		<?php
	}
	?>

	<form action="" method="post">
		<?php
		$table = new MyCrud\Testing\Admin\CrudTeamMemberList();
		$table->prepare_items();
		$table->display();

		?>
	</form>

	
</div>
