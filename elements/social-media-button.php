<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Social_Button extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'wpsb_social_button', // Base ID
            __( 'Social Media Buttons', 'wp-sitebuilder' ), // Name
            array( 'description' => __( 'Social Media Buttons', 'wp-sitebuilder' ), ) // Args
        );
    }


    function form($i){
        if( empty($i) ) {
            $i = array();
        }
        $media_buttons = apply_filters('filter_Wpsb_Social_Button_media_buttons',array(
            'facebook' => array(
                'label' => 'Facebook',
                'src' => 'https://www.facebook.com/'
            ),
            'google' => array(
                'label' => 'Google',
                'src' => 'https://plus.google.com/'
            ),
            'linkedin' => array(
                'label' => 'LinkedIn',
                'src' => 'https://www.linkedin.com/'
            ),
            'youtube' => array(
                'label' => 'Youtube',
                'src' => 'https://www.youtube.com/'
            ),
            'rss' => array(
                'label' => 'RSS',
                'src' => get_site_url().'/feed/rss/'
            ),
            'github' => array(
                'label' => 'Github',
                'src' => 'https://github.com/'
            ),
            'twitter' => array(
                'label' => 'Twitter',
                'src' => 'https://twitter.com/'
            )
        ));
        ?>
        <div class="wpsb_widget_form">
            <div class="mb10">
                <a href="javascript:" class="btn btn-default br0" @click="selected_tab = 'data'"><?php _e('Media Buttons', 'wpsb'); ?></a>
                <a href="javascript:" class="btn btn-default br0" @click="selected_tab = 'settings'"><?php _e('Settings', 'wpsb'); ?></a>
            </div>
            <!--data-->
            <div v-show="selected_tab == 'data'" style="border: 1px solid #cccccc; padding: 5px;">
                <div class="mb10">
                    <a href="javascript:" class="btn btn-default br0" @click="add_button()"><?php _e('Add Social Button','wpsb'); ?></a>
                </div>
                <div class="mb10">
                    <div v-for="(k, each_data) in instance.data" class="mb10" style="border: 1px solid #cccccc; padding: 5px;">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="mb10">
                                    <select v-model="each_data.name" class="form-control" name="<?php echo $this->get_field_name('data');?>[{{ k }}][name]" @change="populate_media_data(each_data)">
                                        <option value="{{ m_name }}" v-for="( m_name,  button ) in media_buttons" >{{ button.label }}</option>
                                    </select>
                                </div>
                                <div class="mb10">
                                    <input type="url" v-model="each_data.src" class="form-control" name="<?php echo $this->get_field_name('data');?>[{{ k }}][src]">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <a href="javascript:" class="btn btn-default pull-right" @click="remove_media_button(k)"><i href="javascript:" class="glyphicon glyphicon-remove"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="selected_tab == 'settings'">
                <div class="mb10">
                    <label><?php _e('Button size','wpsb');?></label>
                    <input type="number" class="form-control" v-model="instance.settings.size" name="<?php echo $this->get_field_name('settings');?>[size]">
                </div>
            </div>
        </div>
        <script>
            var formapp = new Vue({
                el: '.wpsb_widget_form',
                data : {
                    instance : JSON.parse('<?php echo json_encode($i)?>'),
                    media_buttons : JSON.parse('<?php echo json_encode($media_buttons)?>'),
                    selected_tab : 'data'
                },
                methods : {
                    populate_media_data : function (each_data) {
                        each_data.src = this.media_buttons[each_data.name].src;
                    },
                    add_button : function () {
                        var media_name = Object.keys(this.media_buttons)[0];
                        console.log(media_name);
                        this.instance.data.push({
                            name : media_name,
                            src : this.media_buttons[media_name].src
                        });
                    },
                    remove_media_button : function (k) {
                        this.instance.data.splice(k,1);
                    },
                },
                ready : function () {
                    if( typeof this.instance.settings == 'undefined') {
                        Vue.set(this.instance,'settings',{
                            size : 2
                        });
                    };
                    if( typeof this.instance.data == 'undefined') {
                        Vue.set(this.instance,'data',[]);
                    };
                }
            });
        </script>
        <?php
    }

    function widget($args, $i) {
        ?>
        <div class="Wpsb_Social_Button">
            <?php
            foreach ( $i['data'] as $key => $data ) {
                ?>
                <a href="<?php echo $data['src']; ?>" target="_blank"><i class="fa fa-<?php echo $i['settings']['size']; ?>x fa-<?php echo $data['name']; ?>"></i></a>
                <?php
            }
            ?>
        </div>
        <?php
    }
}

add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Social Media Button',
        'name' => 'Wpsb_Social_Button',
        'id_base' => 'wpsb_social_button'
    ) );
} );