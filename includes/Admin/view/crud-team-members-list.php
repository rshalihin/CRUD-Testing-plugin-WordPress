<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'CRUD Team Members', 'crud-test' ); ?></h1>
	
	<form action="" method="post">
		<?php
		$table = new MyCrud\Testing\Admin\CrudTeamMemberList();
		$table->prepare_items();
		$table->display();

		?>
	</form>

	
</div>
