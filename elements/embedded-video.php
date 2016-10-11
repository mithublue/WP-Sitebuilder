<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Embedded_Video extends Wpsb_Base_Element {

    function __construct() {

        parent::__construct(
            __( 'Embedded Video', 'wp-sitebuilder'),
            'wpsb_embedded_video',
            array(
                'description' => __('Embedded Video', 'wp-sitebuilder'),
                'default_style' => 'simple',
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'wp-sitebuilder'),
                ),
                'url' => array(
                    'type' => 'text',
                    'label' => __('Destination URL', 'wp-sitebuilder'),
                ),
                'width' => array(
                    'type' => 'text',
                    'label' => __('Width', 'wp-sitebuilder'),
                ),
                'height' => array(
                    'type' => 'text',
                    'label' => __('Height', 'wp-sitebuilder'),
                ),
            )

        );
    }


    function widget($args, $instance) {
        ?>
        <?php echo $args['before_widget']; ?>
        <div>
            <?php echo $args['before_title']; ?>
            <?php echo $instance['title']; ?>
            <?php echo $args['after_title']; ?>
            <?php
            $params = array();
            if ( !empty($instance['width']) ) {
                $params['width'] = $instance['width'];
            }
            if( !empty($instance['height'])) {
                $params['height'] = $instance['height'];
            }
            echo wp_oembed_get($instance['url'], $params );?>
        </div>
        <?php echo $args['after_widget']; ?>
        <?php
    }

}


add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Embedded Video',
        'name' => 'Wpsb_Embedded_Video',
        'id_base' => 'wpsb_embedded_video'
    ) );
} );
