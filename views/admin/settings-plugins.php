<div class="postbox">
    <h3 class="hndle"><span><?php _e('Use template for mails sent by your installed plugins','wp-html-mail'); ?></span></h3>
    <ul style="" class="inside haet-mail-plugins clearfix">
        <?php 
        foreach ($active_plugins as $plugin_name => $plugin): 
            include HAET_MAIL_PATH.'views/admin/single-plugin.php';
        endforeach;

        foreach ($available_plugins as $plugin_name => $plugin): 
            if( !array_key_exists($plugin_name, $active_plugins) )
                include HAET_MAIL_PATH.'views/admin/single-plugin.php';
        endforeach;

        $buyable_plugins = array(
            'woocommerce'   =>  array(
                'name'      =>  'woocommerce',
                'file'      =>  'woocommerce/woocommerce.php',
                'class'     =>  'Haet_Sender_Plugin_WooCommerce',
                'display_name' => 'WooCommerce'
            ),
            'easy-digital-downloads'    =>  array(
                'name'      =>  'easy-digital-downloads',
                'file'      =>  'easy-digital-downloads/easy-digital-downloads.php',
                'class'     =>  'Haet_Sender_Plugin_EDD',
                'display_name' => 'Easy Digital Downloads'
            )
            );
        foreach ($buyable_plugins as $plugin_name => $plugin): 
            if( !array_key_exists($plugin_name, $available_plugins) )
                include HAET_MAIL_PATH.'views/admin/single-plugin.php';
        endforeach;
        ?>
    </ul>
</div>