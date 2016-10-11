<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Button extends Wpsb_Base_Element{

    function __construct(){
        parent::__construct(
            __( 'Button', 'wp-sitebuilder'),
            'wpsb_button',
            array(
                'description' => __('Button', 'wp-sitebuilder'),
                'default_style' => 'simple',
            ),
            array(),
            array(
                'text' => array(
                    'type' => 'text',
                    'label' => __('Text', 'wp-sitebuilder'),
                ),
                'url' => array(
                    'type' => 'text',
                    'label' => __('Destination URL', 'wp-sitebuilder'),
                ),
                'new_window' => array(
                    'type' => 'checkbox',
                    'label' => __('Open In New Window', 'wp-sitebuilder'),
                ),
                'align' => array(
                    'type' => 'select',
                    'label' => __('Button Alignment', 'wp-sitebuilder'),
                    'options' => array(
                        'left' => __('Left', 'wp-sitebuilder'),
                        'right' => __('Right', 'wp-sitebuilder'),
                        'center' => __('Center', 'wp-sitebuilder'),
                        'justify' => __('Justify', 'wp-sitebuilder'),
                    )
                ),
                'type' => array(
                    'type' => 'select',
                    'label' => __('Button Type', 'wp-sitebuilder'),
                    'options' => array(
                        'red' => __('Danger', 'wp-sitebuilder'),
                        'green' => __('Success', 'wp-sitebuilder'),
                        'yellow' => __('Warning', 'wp-sitebuilder'),
                        'blue' => __('Primary', 'wp-sitebuilder'),
                        'aqua' => __('Info', 'wp-sitebuilder'),
                        'default' => __('Default', 'wp-sitebuilder'),
                    )
                ),
                'rounded_corner' => array(
                    'type' => 'checkbox',
                    'label' => __('Rounded corner button', 'wp-sitebuilder'),
                ),
            )

        );
    }

    function widget($args, $instance) {

        switch ($instance['align']){
            case 'right':
                $classname = 'text-right';
                break;
            case 'left':
                $classname = 'text-left';
                break;
            case 'center':
                $classname = 'text-center';
                break;
        }

        ?>
        <div class="<?php echo $classname; ?>">
            <a href="<?php echo $instance['url']; ?>" target="<?php echo $instance['new_window']; ?>" class="btn btn-default btn-<?php echo $instance['type']; ?> <?php echo $instance['rounded_corner']?'':'br0'?>"><?php echo $instance['text']; ?></a>
        </div>
        <?php
    }
}

add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Button',
        'name' => 'Wpsb_Button',
        'id_base' => 'wpsb_button'
    ) );
} );