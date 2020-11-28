<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo esc_attr( $css_class );?>">
    <?php do_action( 'addonify_vs_start_of_variation_attributes_list', 'button' ); ?>
    <?php foreach ( $options as $option_slug => $option_name ):?>
        <li data-value="<?php echo esc_attr( $option_slug );?>" data-title="<?php echo esc_attr( $option_name );?>" ><span class="adfy-vs adfy-button-vs"><?php echo esc_attr( $option_name );?></span></li>
    <?php endforeach;?>
    <?php do_action( 'addonify_vs_end_of_variation_attributes_list', 'button' ); ?>
</ul>