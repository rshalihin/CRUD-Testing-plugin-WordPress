<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'CRUD Team Members', 'crud-test' ); ?></h1>
	
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scop="row">
						<label for="team_member_name"><?php _e( 'Team Member Name', 'crud-test' ); ?></label>
					</th>
					<td>
						<input class="regular-text" type="text" name="team_member_name" id="team_member_name" value="">
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_designation"><?php _e( 'Team Member Designation', 'crud-test' ); ?></label>
					</th>
					<td>
						<input type="text" name="team_member_designation" id="team_member_designation" value="" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_email"><?php esc_html_e( 'Team Member Email', 'crud-test' ); ?></label>
					</th>
					<td>
						<input type="text" name="team_member_email" id="team_member_email" value="" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_phone"><?php esc_html_e( 'Team Member Phone', 'crud-test' ); ?></label>
					</th>
					<td>
						<input type="text" name="team_member_phone" id="team_member_phone" value="" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_address"><?php esc_html_e( 'Team Member Address', 'crud-test' ); ?></label>
					</th>
					<td>
						<input type="text" name="team_member_address" id="team_member_address" value="" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_bio"><?php esc_html_e( 'Team Member Bio', 'crud-test' ); ?></label>
					</th>
					<td>
						<textarea name="team_member_bio" id="team_member_bio" class="regular-text"></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'add_team_member_info' ); ?>
		<?php submit_button( __( 'Add Member Info', 'crud-text' ), 'primary', 'submit_member_info' ); ?>
	</form>
	
</div>
