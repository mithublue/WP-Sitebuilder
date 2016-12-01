<?php

if( !class_exists('WPSB_Addon') ) :

class WPSB_Addon {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'addon_submenu'));
    }

    public function addon_submenu() {
        add_submenu_page( 'wpsb-menu', __('Get Mote Features','wpsb'), __('Get Mote Features','wpsb'), 'manage_options','wpsb_add_ons', array($this,'add_on_page') );
    }

    /**
     * List of add ons
     */
    function add_on_page(){
        ?>
        <h3 style="text-align: center;"><?php _e('Coming Soon with some awesome features !','wpsb'); ?></h3>
        <?php
    }
    public static function init() {
        new WPSB_Addon();
    }
}

WPSB_Addon::init();

endif;