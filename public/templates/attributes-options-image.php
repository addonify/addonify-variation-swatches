<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo $css_class;?>" >
    <?php foreach ( $options as $option_slug => $option_name ):?>
        <li data-value="<?php echo $option_slug;?>" data-title="<?php echo $option_name[1];?>" ><img src="<?php echo $option_name[0];?>" ></li>
    <?php endforeach;?>
</ul>