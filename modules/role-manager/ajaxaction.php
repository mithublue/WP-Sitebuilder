<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Sbrm_Ajax_Action {

    public function __construct() {
        add_action( 'wp_ajax_sbrm_save_role_caps_data', array( $this, 'save_role_caps_data'));
    }

    public function save_role_caps_data() {
        global $wpdb;

        if( isset( $_POST['role_caps_data'] ) && is_array( $_POST['role_caps_data'] ) ) {
            $role_caps_data = maybe_serialize( $_POST['role_caps_data'] );

            $result = $wpdb->update(
                $wpdb->prefix.'options',
                array(
                    'option_value' => $role_caps_data	// integer (number)
                ),
                array( 'option_name' => 'wp_user_roles' ),
                array(
                    '%s'
                ),
                array( '%s' )
            );

            $return['success'] = 'Saved successfully !';
        } else {
            $return['error'] = 'Error in saving !';
        }
        echo json_encode( $return );
        exit;
    }

    public static function init() {
        new Sbrm_Ajax_Action();
    }
}

Sbrm_Ajax_Action::init();