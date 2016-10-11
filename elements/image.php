<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Image extends Wpsb_Base_Element {

    function __construct() {

        parent::__construct(
            __( 'Image', 'wp-sitebuilder'),
            'wpsb_image',
            array(
                'description' => __('Image', 'wp-sitebuilder'),
                'default_style' => 'simple',
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'wp-sitebuilder'),
                ),
                'src' => array(
                    'type' => 'text',
                    'label' => __('Image source', 'wp-sitebuilder'),
                ),
                'width' => array(
                    'type' => 'text',
                    'label' => __('Width (Enter value only)', 'wp-sitebuilder'),
                ),
                'height' => array(
                    'type' => 'text',
                    'label' => __('Height (Enter value only)', 'wp-sitebuilder'),
                ),
                'alt' => array(
                    'type' => 'text',
                    'label' => __('Alt title', 'wp-sitebuilder'),
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
            ?>
            <img src="<?php echo $instance['src']; ?>" alt="<?php echo $instance['alt']?>"
                <?php echo !empty($instance['width']) ? 'width='.$instance['width'] : '' ;?>
                <?php echo !empty($instance['height']) ? 'height='.$instance['height'] : '' ;?>
            />
        </div>
        <?php echo $args['after_widget']; ?>
        <?php
    }

}


add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Image',
        'name' => 'Wpsb_Image',
        'id_base' => 'wpsb_image'
    ) );
} );
