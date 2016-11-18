<?php

if( !class_exists('WPSB_Addon') ) :

class WPSB_Addon {

    public function __construct() {
        add_action( 'admin_print_scripts' , array( $this, 'print_product_page_scripts' ) );
        add_action( 'admin_menu', array( $this, 'addon_submenu'));
    }

    public function addon_submenu() {
        add_submenu_page( 'wpsb-menu', __('Get Mote Features','wpsb'), __('Get Mote Features','wpsb'), 'manage_options','wpsb_add_ons', array($this,'add_on_page') );
    }

    /**
     * List of add ons
     */
    function add_on_page(){

        if ( ! function_exists( 'plugins_api' ) ) {
            $admin_path = trailingslashit( str_replace( site_url() . '/', ABSPATH, get_admin_url() ) );
            require_once( $admin_path . 'includes/plugin-install.php' );
        }
        $call_api = array();
        $call_api[] = plugins_api( 'plugin_information', array( 'slug' => 'wp-sitebuilder-role-manager' , 'fields' => array( 'short_description' => true, 'banners' => true ) ) );

        if ( is_wp_error( $call_api ) ) {
            echo '<pre>' . print_r( $call_api->get_error_message(), true ) . '</pre>';
        } else {
            //echo base64_encode(json_encode( $call_api ));
            echo '<div class="cc-product-wrapper">';
            foreach( $call_api as $number => $plugin ) {
                ?>
                <div class="cc-product-holder">
                    <img src="<?php echo isset( $plugin->banners['low'] ) ? $plugin->banners['low'] : '' ?>" width="100%" alt="Banner"/>
                    <h3><a href="https://wordpress.org/plugins/<?php echo $plugin->slug; ?>" target="_blank"><?php echo $plugin->name ?></a></h3>
                    <p><?php echo $plugin->short_description; ?></p>
                    <a class="btn btn-visit" href="https://wordpress.org/plugins/<?php echo $plugin->slug; ?>" target="_blank"">Visit plugin page</a>
                </div>
                <?php
            }
            echo '</div>';

        }
    }

    /**
     * scripts and styles of product page
     */
    function print_product_page_scripts( $hook ) {
        ?>
        <style>
            .cc-product-holder a {
                text-decoration: none;
            }
            .cc-product-wrapper{
                overflow: hidden;
            }
            .cc-product-holder{
                border: 1px solid #cccccc;
                border-radius: 3px;
                width: 300px;
                height: 300px;
                float: left;
                padding: 10px 10px;
                margin: 3px;

            }
            .cc-product-holder .btn-visit{
                padding: 7px 10px;
                background: #05b93a;
                border-radius: 3px;
                color: #ffffff;
            }
        </style>
        <?php
    }

    public static function init() {
        new WPSB_Addon();
    }
}

WPSB_Addon::init();

endif;