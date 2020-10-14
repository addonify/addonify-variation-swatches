<div class="addonify-vs-image-field-wrapper">
    <div class="image-preview"><img data-placeholder="<?php echo $default_img;?>" src="<?php echo ( $img_url ? $img_url : $default_img ); ?>" width="60px" height="60px"/></div>
    <div class="button-wrapper">
        <input type="hidden" id="addonify-vs-term-image-id" name="<?php echo $name;?>" value="<?php echo esc_attr( $img_url ) ?>"/>
        <button type="button" class="addonify-vs_select_image_button button button-primary button-small"><?php esc_html_e( 'Upload / Add image', 'woo-variation-swatches' ); ?></button>
        <button type="button" style="<?php echo ( ! $img_url ? 'display:none' : '' ) ?>" class="addonify-vs_remove_image_button button button-danger button-small"><?php esc_html_e( 'Remove image', 'woo-variation-swatches' ); ?></button>
    </div>
</div>