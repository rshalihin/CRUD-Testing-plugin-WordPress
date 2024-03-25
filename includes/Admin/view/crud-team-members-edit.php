<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'CRUD Team Members', 'crud-test' ); ?></h1>
	<?php
	if ( isset( $_GET['crud-updated'] ) ) {
		?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Information has been updated successfully!!!', 'crud-test' ); ?></p>
		</div>
		<?php
	}
	?>

	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scop="row">
						<label for="team_member_name"><?php esc_html_e( 'Team Member Name', 'crud-test' ); ?></label>
					</th>
					<td>
						<input class="regular-text" type="text" name="team_member_name" id="team_member_name" value="<?php echo esc_attr( $information->name ); ?>">
						<?php
						if ( $this->has_error( 'name' ) ) {
							?>
							<p class="description error"><?php echo esc_html( $this->get_error( 'name' ) ); ?> </p>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_designation"><?php esc_html_e( 'Team Member Designation', 'crud-test' ); ?></label>
					</th>
					<td>
						<input type="text" name="team_member_designation" id="team_member_designation" value="<?php echo esc_attr( $information->designation ); ?>" class="regular-text">
						<?php
						if ( $this->has_error( 'designation' ) ) {
							?>
							<p class="description error"><?php echo esc_html( $this->get_error( 'designation' ) ); ?> </p>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_email"><?php esc_html_e( 'Team Member Email', 'crud-test' ); ?></label>
					</th>
					<td>
						<input type="text" name="team_member_email" id="team_member_email" value="<?php echo esc_attr( $information->email ); ?>" class="regular-text">
						<?php
						if ( $this->has_error( 'email' ) ) {
							?>
							<p class="description error"><?php echo esc_html( $this->get_error( 'email' ) ); ?> </p>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_phone"><?php esc_html_e( 'Team Member Phone', 'crud-test' ); ?></label>
					</th>
					<td>
						<input type="text" name="team_member_phone" id="team_member_phone" value="<?php echo esc_attr( $information->phone ); ?>" class="regular-text">
						<?php
						if ( $this->has_error( 'phone' ) ) {
							?>
							<p class="description error"><?php echo esc_html( $this->get_error( 'phone' ) ); ?> </p>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_address"><?php esc_html_e( 'Team Member Address', 'crud-test' ); ?></label>
					</th>
					<td>
						<input type="text" name="team_member_address" id="team_member_address" value="<?php echo esc_attr( $information->address ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scop="row">
						<label for="team_member_bio"><?php esc_html_e( 'Team Member Bio', 'crud-test' ); ?></label>
					</th>
					<td>
						<textarea name="team_member_bio" id="team_member_bio" class="regular-text"><?php echo esc_textarea( $information->bio ); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="team_id" value="<?php echo esc_attr( $information->id ); ?>">
		<?php wp_nonce_field( 'add_team_member_info' ); ?>
		<?php submit_button( __( 'Update Member Info', 'crud-text' ), 'primary', 'submit_member_info' ); ?>
	</form>
	
</div>
