<div class="wpd-form-selector">
	<p>
		<?php 
			if( is_array( $savedForms ) ) :
		?>
		<label for="wpd-form-selector">Select an existed form bellow </label><br />
		<select name="wpd-form-selector" id="wpd-form-selector">
			<?php 
				foreach( $savedForms as $formkey => $form ) :
					printf( '<option value="%s">%s</option>', $formkey, $form['title'] );
				endforeach;
			?>
		</select>
		<a href="#" class="button button-primary" id="wpd-to-editor">Save</a><br />
	</p>
	<p>
		or Create another one by <a class="button button-small" href="<?php echo admin_url( 'options-general.php?page=wpd-settings'); ?>" target="_blank">clicking here</a> 
		<?php else : ?>
		Sorry!, You need to create forms first, <a target="_blank" class="button button-small" href="<?php echo admin_url( 'options-general.php?page=wpd-settings'); ?>">click here</a>
		<?php endif; ?>
	</p>
</div>