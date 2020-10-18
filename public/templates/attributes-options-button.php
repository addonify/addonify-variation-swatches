<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo $css_class;?>">
    <?php foreach ( $options as $option_slug => $option_name ):?>
        <li data-value="<?php echo $option_slug;?>"><?php echo $option_name;?></li>
    <?php endforeach;?>
</ul>