<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo $css_class;?>">
    <?php foreach ( $options as $option ):?>
        <li><?php echo $option;?></li>
    <?php endforeach;?>
</ul>