<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Rich_Text extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'wpsb_rich_text', // Base ID
            __( 'Rich Text', 'wp-sitebuilder' ), // Name
            array( 'description' => __( 'Rich Text', 'wp-sitebuilder' ), ) // Args
        );
    }


    function form($i){
        if( empty($i) ) {
            $i = array();
        } else {
            $i['text'] = addslashes($i['text']);
        }
        ?>
        <div class="wpsb_widget_form">
            <div class="mb10">
                <label><?php _e('Title','wpsb'); ?></label>
                <input class="form-control" type="text" v-model="instance.title" name="<?php echo $this->get_field_name('title');?>">
            </div>
            <div class="mb10">
                <label><?php _e('Text','wpsb'); ?></label>
                <?php wp_editor('{{ instance.text }}',$this->get_field_id('text'),array(
                    'textarea_name' => $this->get_field_name('text'),
                    'tinymce' => false,
                ))?>
            </div>
        </div>
        <script>
            var formapp = new Vue({
                el: '.wpsb_widget_form',
                data : {
                    instance : JSON.parse('<?php echo json_encode($i)?>'),
                },
                ready : function () {
                    if( this.instance.length == 0 ) {
                        this.instance = {
                            'title' : 'Text title',
                            'text' : 'Some text'
                        }
                    }
                }
            });
        </script>
        <?php
    }

    function widget($args, $i) {
        echo $args['before_widget'];
        echo $args['before_title'];
        echo $i['title'];
        echo $args['after_title'];
        ?>
        <div class="Wpsb_Rich_Text">
            <?php nl2br($i['text']); ?>
        </div>
        <?php
        echo $args['after_widget'];
    }
}

add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Rich Text',
        'name' => 'Wpsb_Rich_Text',
        'id_base' => 'wpsb_rich_text'
    ) );
} );