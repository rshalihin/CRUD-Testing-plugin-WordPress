<div class="enquery-form" id="enquiry-form-id">
	<form action="" method="post">
		<div class="form-group">
			<label for="enquery-name">Name</label>
			<input type="text" class="form-control" id="enquery-name" name="enquery-name" placeholder="Name">
		</div>
		<div class="form-group">
			<label for="enquery-email">Email</label>
			<input type="email" class="form-control" id="enquery-email" name="enquery-email" placeholder="Email">
		</div>
		<div class="form-group">
			<label for="enquery-message">Message</label>
			<textarea class="form-control" id="enquery-message" name="enquery-message" rows="3"></textarea>
		</div>
		<div class="form-group">
			<?php wp_nonce_field( 'crud-enquiry-form' ); ?>

			<input type="hidden" name="action" value="crud_team_enquiry" />
			<input type="submit" name="send_enquiry" value="<?php echo esc_attr_e( 'Submit Enquiry', 'crud-test' ); ?>" />
		</div>
	</form>    
</div>
