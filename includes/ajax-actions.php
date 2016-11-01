<?php

class WPSB_Ajax{

    public static function init(){
        add_action( 'wp_ajax_wpsb_grab_element_data', function(){

            $nonce = $_POST['nonce'];
            if( !wp_verify_nonce( $nonce, 'wpsb_builder_nonce' ) ) return;

            if( $_POST['type'] == 'widget' ) {
                /**
                 * process instance
                 */
                $id_base = sanitize_text_field($_POST['id_base']);
                $id = sanitize_text_field($_POST['id']);
                $widget_name = sanitize_text_field($_POST['name']);

                //pri(unserialize($_POST['instance']));
                parse_str( $_POST['instance'], $i );
                $instance = $i[ 'widget-'.$id_base ][$id];
                /**
                 * something to modify
                 */
                $widget_object = new $widget_name;
                $widget_object->number = $id;
                $widget_object->form($instance);
            }
            exit;
        } );

        /**
         * Update preview of given element id and element data
         */
        add_action( 'wp_ajax_wpsb_update_preview', function (){
            $nonce = $_POST['nonce'];
            if( !wp_verify_nonce( $nonce, 'wpsb_builder_nonce' ) ) return;

            if( $_POST['type'] == 'widget' ) {
                $id = sanitize_text_field($_POST['id']);
                wpsb_preview( $id, $_POST['lego_layout'] );
            }
            exit;
        } );

        /**
         * Selected post types will be
         * disabled for pagebuilders
         */
        add_action( 'wp_ajax_wpsb_disabled_pagebuilder_for_post_types', function() {
            $ret = set_transient( 'non_pagebuilder_post_types', $_POST['disabled_for_post_types'] );
            $result = array();

            if( $ret ) {
                $result['success'] = 'Data saved successfully !';
            } else {
                $result['error'] = 'Data could not be saved !';
            }
            echo json_encode($result);
            exit;
        });



    }
}

WPSB_Ajax::init();