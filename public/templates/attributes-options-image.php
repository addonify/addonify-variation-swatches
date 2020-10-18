<?php defined( 'ABSPATH' ) || exit; ?>
<ul class="<?php echo $css_class;?>" >
    <?php foreach ( $options as $option_slug => $option_name ):?>
        <li data-value="<?php echo $option_slug;?>" ><img src="<?php echo $option_name;?>" style="width:35px; height: 35px;" ></li>
    <?php endforeach;?>
</ul>