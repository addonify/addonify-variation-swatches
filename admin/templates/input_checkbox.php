<?php
	defined( 'ABSPATH' ) || exit;

	printf(
		'<input type="checkbox"  name="%1$s" id="%1$s" value="1" %2$s %3$s /><span class="label-after-input">%4$s</span>',
		esc_attr( $args['name'] ),
		$is_checked,
		$attr,
		$end_label
	);