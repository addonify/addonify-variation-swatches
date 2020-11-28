<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo $css_class;?>" >
    <?php do_action( 'addonify_vs_start_of_variation_attributes_list', 'image' ); ?>
    <?php foreach ( $options as $option_slug => $option_name ):?>
        <li data-value="<?php echo $option_slug;?>" data-title="<?php echo $option_name[1];?>" ><img class="adfy-vs adfy-image-vs" src="<?php echo $option_name[0];?>" ></li>
    <?php endforeach;?>
    <?php do_action( 'addonify_vs_end_of_variation_attributes_list', 'image' ); ?>
</ul>