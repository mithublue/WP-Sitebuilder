<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Call_to_Action extends Wpsb_Base_Element {

    function __construct() {

        parent::__construct(
            __( 'Lego Call to Action', 'wp-sitebuilder'),
            'wpsb_cta',
            array(
                'description' => __('Call to Action Button', 'wp-sitebuilder'),
                'default_style' => 'simple',
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'wp-sitebuilder'),
                ),

                'sub_title' => array(
                    'type' => 'text',
                    'label' => __('Subtitle', 'wp-sitebuilder')
                ),
                'text' => array(
                    'type' => 'text',
                    'label' => __('Button Text', 'wp-sitebuilder'),
                ),
                'url' => array(
                    'type' => 'text',
                    'label' => __('Destination URL', 'wp-sitebuilder'),
                ),
                'new_window' => array(
                    'type' => 'checkbox',
                    'label' => __('Open In New Window', 'wp-sitebuilder'),
                ),
                'type' => array(
                    'type' => 'select',
                    'label' => __('Button Type', 'wp-sitebuilder'),
                    'options' => array(
                        'null' => __( 'Inherit', 'wp-sitebuilder'),
                        'red' => __('Danger', 'wp-sitebuilder'),
                        'green' => __('Success', 'wp-sitebuilder'),
                        'yellow' => __('Warning', 'wp-sitebuilder'),
                        'blue' => __('Primary', 'wp-sitebuilder'),
                        'aqua' => __('Info', 'wp-sitebuilder'),
                        'default' => __('Default', 'wp-sitebuilder'),
                    )
                ),
                'button' => array(
                    'type' => 'widget',
                    'class' => 'SiteOrigin_Widget_Button_Widget',
                    'label' => __('Button', 'wp-sitebuilder'),
                ),
                'rounded_corner' => array(
                    'type' => 'checkbox',
                    'label' => __('Rounded corner button', 'wp-sitebuilder'),
                ),
            )

        );
    }


    function widget($args, $instance) {
        ?>
        <?php echo $args['before_widget']; ?>
        <div class="Wpsb_Call_to_Action">
            <?php echo $args['before_title']; ?>
            <?php echo $instance['title']; ?>
            <?php echo $args['after_title']; ?>
            <div class="row">
                <div class="col-sm-9">
                    <p class="subtitle"><?php echo $instance['sub_title']; ?></p>
                </div>
                <div class="col-sm-3">
                    <a href="<?php echo $instance['url'];?>" target="<?php echo $instance['new_window']; ?>" class="pull-right btn btn-<?php echo $instance['type']; ?> <?php echo $instance['rounded_corner']?'':'br0'?>"><?php echo $instance['text']; ?></a>
                </div>
            </div>
        </div>
        <?php echo $args['after_widget']; ?>
        <?php
    }

}


add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Call to Action',
        'name' => 'Wpsb_Call_to_Action',
        'id_base' => 'wpsb_cta'
    ) );
} );
