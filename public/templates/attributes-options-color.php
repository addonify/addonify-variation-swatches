<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo $css_class;?>">
    <?php foreach ( $options as $option ):?>
        <li style="height: 35px; width: 35px; background-color:<?php echo esc_attr( $option );?>"></li>
    <?php endforeach;?>
</ul>