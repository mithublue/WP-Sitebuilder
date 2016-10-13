<?php
/*
 * Plugin Name: WP Sitebuilder github
 * Plugin URI: https://wordpress.org/plugins/wp-sitebuilder/
 * Description: Sitebuilder for your site with flexible and easy to use options
 * Author: Mithu A Quayium
 * Author URI: http://cybercraftit.com/
 * Version: 0.0.1.1
 * Text Domain: wpsb
 * License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required minimums and constants
 */
define( 'WPSB_VERSION', '0.1' );
define( 'WPSB_ROOT', dirname(__FILE__) );
define( 'WPSB_ASSET_PATH', plugins_url('assets',__FILE__) );

class Lego_Pagebuilder {

	/**
	 * @var Singleton The reference the *Singleton* instance of this class
	 */
	private static $instance;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_scripts_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this , 'wp_enqueue_scripts_styles' ) );
	    $this->includes();

        /**
         * beta notice remove
         */
        add_action('wp_ajax_wpsb_remove_beta',function (){
            set_transient('wpsb_beta_removed',1);
            exit;
        });
        add_action('admin_notices', array($this,'beta_notice'));
        add_action('admin_footer', array($this,'beta_remove'));

	}

    /**
     * Beta notice
     */
    function beta_notice() {
        if( empty(get_transient('wpsb_beta_removed') ) ) {
            ?>
            <div class="notice notice-warning is-dismissible wpsb-beta-notice">
                <p><?php _e('Important ! WP Sitebuilder is in beta version yet. It is updating continuously. You can check and fork in <a href="https://github.com/mithublue/WP-Sitebuilder" target="_blank">github repo</a> too for update.', 'wpsb'); ?></p>
            </div>
            <?php
        }
    }

	/**
	 * inlcude the necessary files
	 * both admin adn frontend
	 */
	public function includes(){
        include_once WPSB_ROOT.'/wpsb-functions.php';
        include_once WPSB_ROOT.'/includes/ajax-actions.php';
		include_once WPSB_ROOT.'/includes/admin/pagebuilder-panel.php';
        include_once WPSB_ROOT.'/elements/init.php';

        /**
         * frontend
         */
        include_once WPSB_ROOT.'/includes/content.php';
	}

	/**
     * Enqueue scripts and styles
     * in admin panel
     */
	public function enqueue_scripts_styles( $hook ){


        if( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
            //styles
            wp_enqueue_style('lego-wrapper-css', WPSB_ASSET_PATH.'/css/wrapper-bs.min.css' );
            wp_enqueue_style('lego-vue-widget-css', WPSB_ASSET_PATH.'/css/vue-ui-widgets.min.css' );// vue ui widget css
            wp_enqueue_style('lego-framework-css', WPSB_ASSET_PATH.'/css/framework.min.css' );
            wp_enqueue_style('lego-admin-css', WPSB_ASSET_PATH.'/css/admin/admin.min.css' );

            wp_enqueue_style('wpsb-element-css', WPSB_ASSET_PATH.'/css/admin/element.admin.min.css' );

            //scripts
            wp_enqueue_script('lego-vue', WPSB_ASSET_PATH.'/js/vue.min.js', array(), false, true );
            wp_enqueue_script('lego-vue-widget-js', WPSB_ASSET_PATH.'/js/vue-ui-widgets.js', array('lego-vue'), false, true );
            wp_enqueue_script('lego-components-js', WPSB_ASSET_PATH.'/js/components.js', array('lego-vue'), false, true );
            wp_enqueue_script('lego-admin-js', WPSB_ASSET_PATH.'/js/admin/admin.js', array('lego-vue' ), false, true );
            wp_localize_script('lego-admin-js','wpsb_obj',array(
                'ajaxnonce' => wp_create_nonce( "wpsb_builder_nonce" )
            ));
            wp_enqueue_script( 'jquery-ui-sortable' );
        }
    }

    /**
     * Enqueue scripts and styles
     * in frontend
     */
    function wp_enqueue_scripts_styles(){
        global $post;
        if( isset( $post->ID ) ) {
            if( wpsb_is_lego_enabled( $post->ID ) ) {
                //slider
                wp_enqueue_style('lego-wrapper-css', WPSB_ASSET_PATH.'/css/wrapper-bs.min.css' );
                wp_enqueue_style('lego-framework-css', WPSB_ASSET_PATH.'/css/framework.min.css' );
                wp_enqueue_style('lego-elements-css', WPSB_ASSET_PATH.'/css/elements.min.css' );
                //social media buttons
                wp_enqueue_style('lego-fa-css', WPSB_ASSET_PATH.'/css/font-awesome.min.css' );
                //js
                wp_enqueue_script('lego-bs-js', WPSB_ASSET_PATH.'/js/bootstrap.min.js', array('jquery') );
            }
        }
    }


    /**
     * beta remove code
     */
    function beta_remove(){
        ?>
        <script>
            (function ($) {
                $(document).on('click','.wpsb-beta-notice',function () {
                    $.post(
                        ajaxurl,
                        {
                            action : 'wpsb_remove_beta'
                        }
                    )
                })
            }(jQuery))
        </script>
        <?php
    }
}

Lego_Pagebuilder::get_instance();