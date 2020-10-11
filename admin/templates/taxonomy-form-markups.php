<?php if( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) == 1 ): ?>
	<tr class="form-field form-required">
		<th scope="row" valign="top">
			<label for="<?php echo esc_attr( $name );?>"><?php echo esc_html( $label );?></label>
		</th>
		<td>
			<?php echo $input_field_markups;?>
			<p class="description"><?php echo esc_html( $description );?></p>
		</td>
	</tr>

<?php else: ?>
	
	<div class="form-field">
		<label for="<?php echo esc_attr( $name );?>"><?php echo esc_html( $label );?></label>
		<?php echo $input_field_markups;?>
		<p class="description"><?php echo esc_html( $description );?></p>
	</div>

<?php endif; ?>