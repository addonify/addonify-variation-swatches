<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo $css_class;?>">
    <?php foreach ( $options as $option_slug => $option_name ):?>
        <li data-value="<?php echo $option_slug;?>" data-title="<?php echo $option_name;?>" > <span style="background-color:<?php echo esc_attr( $option_slug );?>" ></span></li>
    <?php endforeach;?>
</ul>