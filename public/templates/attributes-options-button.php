<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo $css_class;?>">
    <?php foreach ( $options as $option_slug => $option_name ):?>
        <li data-value="<?php echo $option_slug;?>" data-title="<?php echo $option_name;?>" ><span><?php echo $option_name;?></span></li>
    <?php endforeach;?>
    <?php do_action( 'addonify_vs_end_of_variation_attributes_list', 'button' ); ?>
</ul>