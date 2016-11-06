<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Title extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'wpsb_title', // Base ID
            __( 'Title', 'wp-sitebuilder' ), // Name
            array( 'description' => __( 'Title', 'wp-sitebuilder' ), ) // Args
        );
    }


    function form($i){
        if( empty($i) ) {
            $i = array();
        }
        ?>
        <div class="wpsb_widget_form">
            <div class="mb10">
                <label><?php _e('Title','wpsb'); ?></label>
                <input class="form-control" type="text" v-model="instance.title" name="<?php echo $this->get_field_name('title');?>">
            </div>
            <div class="mb10">
                <label><?php _e('Title tag','wpsb'); ?></label>
                <select class="form-control" v-model="instance.tag" name="<?php echo $this->get_field_name('tag');?>">
                    <option value="{{ tag }}" v-for="tag in tags">{{ tag }}</option>
                </select>
            </div>
            <div class="mb10">
                <label><?php _e('Align','wpsb'); ?></label>
                <select class="form-control" v-model="instance.align" name="<?php echo $this->get_field_name('align');?>">
                    <option value="{{ name }}" v-for="(name,label) in aligns">{{ label }}</option>
                </select>
            </div>
        </div>
        <script>
            var formapp = new Vue({
                el: '.wpsb_widget_form',
                data : {
                    instance : JSON.parse('<?php echo json_encode($i)?>'),
                    tags : ['h1','h2','h3','h4','h5','h6'],
                    aligns : {
                        'right' : 'Right',
                        'center' : 'Center',
                        'left' : 'Left'
                    }
                },
                ready : function () {
                    if( this.instance.length == 0 ) {
                        this.instance = {
                            'tag' : 'h2',
                            'align' : 'center'
                        }
                    }
                }
            });
        </script>
        <?php
    }

    function widget($args, $i) {
        ?>
        <div class="Wpsb_Title">
            <?php
            $alignclass = '';
            switch ( $i['align'] ) {
                case 'right' :
                    $alignclass = 'text-right';
                    break;
                case 'left' :
                    $alignclass = 'text-left';
                    break;
                case 'center' :
                    $alignclass = 'text-center';
                    break;
            }
            echo '<'.$i['tag'].' class="'.$alignclass.'">'.esc_attr($i['title']).'</'.$i.'>';
            ?>
        </div>
        <?php
    }
}

add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Title',
        'name' => 'Wpsb_Title',
        'id_base' => 'wpsb_title'
    ) );
} );