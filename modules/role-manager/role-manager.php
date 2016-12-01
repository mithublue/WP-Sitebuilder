<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Sbrm_Init {

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
     * Instantiate the add on
     * Sbrm_Init constructor.
     */
    public function __construct() {
        add_action('wpsb_admin_menu', array($this,'build_submenu_page'));
        add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts_styles'));
        $this->includes();
    }

    function includes() {
        include_once WPSB_ROOT.'/modules/role-manager/ajaxaction.php';
    }

    /**
     * Submenu page for
     * admin frontend
     */
    public function build_submenu_page($menu_slug) {
        add_submenu_page( $menu_slug, __('Role Manager','sbrm'), __('Role Manager','sbrm'), 'manage_options','sbrm_role_mananger', array($this,'build_role_manager_page') );
    }

    /**
     * Admin frontend page
     */
    public function build_role_manager_page() {
        include_once WPSB_ROOT.'/modules/role-manager/admin/role-manager-panel.php';
    }

    public function admin_enqueue_scripts_styles( $hook ) {

        if( $hook == 'wp-sitebuilder_page_sbrm_role_mananger' ) {
            //style
            wp_enqueue_style('lego-wrapper-css');
            wp_enqueue_style('wpsb-vue-widget-css');// vue ui widget css
            wp_enqueue_style('lego-framework-css');
            wp_enqueue_style('sbrm-admin-css', WPSB_ASSET_PATH.'/css/admin/admin-sbrm.css');
            //script
            wp_enqueue_script('wpsb-vue');
            wp_enqueue_script('lego-components-js');
        }
    }

}

Sbrm_Init::get_instance();