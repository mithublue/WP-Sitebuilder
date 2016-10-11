<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Carousel extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'wpsb_carousel_slider', // Base ID
            __( 'Carousel Slider', 'wp-sitebuilder' ), // Name
            array( 'description' => __( 'Carousel slider', 'wp-sitebuilder' ), ) // Args
        );
    }


    function form($i){
        if( empty($i) ) {
            $i = array();
        }
        ?>
        <div class="wpsb_widget_form">
            <input type="hidden" v-model="instance.settings.slider_id" name="<?php echo $this->get_field_name('settings');?>[slider_id]" >
            <div class="mb10">
                <a href="javascript:" data-target="slides" class="btn btn-default br0" @click="selected_tab = 'slides'"><?php _e('Slides','wpsb'); ?></a>
                <a href="javascript:" data-target="settings" class="btn btn-default br0" @click="selected_tab = 'settings'"><?php _e('Settings','wpsb'); ?></a>
            </div>
            <!--Slides-->
            <div v-show="selected_tab == 'slides'" style="border: 1px solid #ccc; padding: 5px;">
                <a href="javascript:" class="btn btn-default" @click="add_slider_panel()"><?php _e('Add Slider','wpsb'); ?></a>
                <div v-for="( k ,d ) in instance.data" class="container-fluid">
                    <div class="row wpsb_element_panel" :data-key="k">
                        <div class="col-sm-8" :class="{ wpsb_shrink : data_shrink[k] }">
                            <div class="mb10">
                                <div class="mb10">
                                    <img src="{{ d.img.src }}" width="200" alt="image" v-if="d.img.src">
                                </div>
                                <input type="text" class="form-control mb5" v-model="d.img.src" :value="{{ d.img.src }}" name="<?php echo $this->get_field_name('data');?>[{{ k }}][img][src]">
                                <button class="wpsb_set_custom_images button" @click="popup_media_panel(d)">Change Image</button>
                            </div>
                            <input type="text" class="form-control mb5" v-model="d.title" name="<?php echo $this->get_field_name('data');?>[{{ k }}][title]">
                            <textarea v-model="d.text" class="form-control mb5" cols="30" rows="10" name="<?php echo $this->get_field_name('data');?>[{{ k }}][text]"></textarea>
                        </div>
                        <div class="col-sm-4">
                            <span class="btn btn-default" @click="place_up(k)"><i class="glyphicon glyphicon-arrow-up"></i></span>
                            <span class="btn btn-default" @click="place_down(k)"><i class="glyphicon glyphicon-arrow-down"></i></span>
                            <span class="btn btn-default" @click="expand_slider_panel(k)" v-if="data_shrink[k] == true"><i class="glyphicon glyphicon-collapse-down"></i></span>
                            <span class="btn btn-default" @click="shrink_slider_panel(k)" v-if="data_shrink[k] == false"><i class="glyphicon glyphicon-collapse-up"></i></span>
                            <span class="btn btn-default" @click="remove_slider_panel(k)"><i class="glyphicon glyphicon-remove"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <!--Settings-->
            <div v-show="selected_tab == 'settings'" style="border: 1px solid #ccc; padding: 5px;">
                <div>
                    <label><?php _e('Slider width (leave blank to cover the entire area of parent)','wpsb'); ?></label>
                    <input class="form-control" type="number" v-model="instance.settings.width" name="<?php echo $this->get_field_name('settings');?>[width]" /><?php _e('px','wpsb');?>
                </div>
                <div>
                    <label><?php _e('Slider height','wpsb'); ?></label>
                    <input class="form-control" type="number" v-model="instance.settings.height" name="<?php echo $this->get_field_name('settings');?>[height]" /><?php _e('px','wpsb');?>
                </div>
                <div>
                    <label><?php _e('Image placement type','wpsb');?></label>
                    <select v-model="instance.settings.img_placement" name="<?php echo $this->get_field_name('settings');?>[img_placement]">
                        <option value="cover"><?php _e('Cover the width','wpsb'); ?></option>
                        <option value="stretch"><?php _e('Stretch smaller images','wpsb'); ?></option>
                        <option value="original"><?php _e('Keep origian size','wpsb'); ?></option>
                    </select>
                </div>
                <div>
                    <label><?php _e('Slider interval','wpsb'); ?></label>
                    <input type="number" class="form-control" v-model="instance.settings.interval" name="<?php echo $this->get_field_name('settings');?>[interval]">
                </div>
                <div>
                    <label>
                        <input type="checkbox" v-model="instance.settings.show_nav" name="<?php echo $this->get_field_name('settings');?>[show_nav]" value="true">
                        <?php _e('Show navigation','wpsb'); ?>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="checkbox" v-model="instance.settings.pause" name="<?php echo $this->get_field_name('settings');?>[pause]" value="hover">
                        <?php _e('Pause on hover','wpsb'); ?>
                    </label>
                </div>
                <div>
                    <label>
                        <input type="checkbox" v-model="instance.settings.keyboard" name="<?php echo $this->get_field_name('settings');?>[keyboard]" value="true">
                        <?php _e('Enable keyboard navigation','wpsb'); ?>
                    </label>
                </div>
            </div>
        </div>
        <script>
            var formapp = new Vue({
                el: '.wpsb_widget_form',
                data : {
                    instance : JSON.parse('<?php echo json_encode($i)?>'),
                    default_data : {
                        settings : {
                            width : '',
                            height : '',
                            img_placement : '', //cover,stretch,original
                            nav_position : '',
                            show_nav : '',
                            interval : 5000,
                            pause : 'hover',
                            wrap : true,
                            keyboard : true,
                            slider_id : 'wpsb_carousel_' + new Date().getTime()
                        },
                        data : []
                    },
                    data_template : {
                        img : {
                            src : '',
                        },
                        title : 'Feature label',
                        text : 'Lorem ipsum text'
                    },
                    data_shrink : {},
                    selected_tab : 'slides'
                },
                methods : {
                    process_data_shrink : function () {
                        for( k in JSON.parse(JSON.stringify(this.instance.data)) ) {
                            if(typeof this.data_shrink[k] == 'undefined') {
                                Vue.set(this.data_shrink,k,true);
                            }
                        }
                    },
                    add_slider_panel : function () {
                        formapp.instance.data.push( JSON.parse( JSON.stringify(formapp.data_template) ));
                        this.process_data_shrink();
                    },
                    popup_media_panel : function (d) {
                        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                            jQuery(document).on('click', '.wpsb_set_custom_images', function(e) {
                                e.preventDefault();
                                var button = jQuery(this);
                                wp.media.editor.send.attachment = function(props, attachment) {
                                    d.img.src = attachment.url
                                };
                                wp.media.editor.open(button);
                                return false;
                            });
                        }
                    },
                    place_up : function (k) {
                        if( formapp.instance.data.length == 0 ) return;
                        if( k == 0 ) return;

                        var swapping_data = formapp.instance.data[k];
                        formapp.instance.data.splice(k,1);
                        formapp.instance.data.splice((k-1),0,swapping_data);
                    },
                    place_down : function (k) {
                        if( k == formapp.instance.data.length ) return;

                        var swapping_data = formapp.instance.data[k];
                        formapp.instance.data.splice(k,1);
                        formapp.instance.data.splice((k+1),0,swapping_data);
                    },
                    expand_slider_panel : function (k) {
                        Vue.set( this.data_shrink, k , false );
                    },
                    shrink_slider_panel : function (k) {
                        Vue.set( this.data_shrink, k , true );
                    },
                    remove_slider_panel : function (k) {
                        formapp.instance.data.splice(k,1);
                        Vue.delete(this.data_shrink,k);
                    },
                },

                ready : function () {
                    if( Object.keys(this.instance).length == 0 ) {
                        this.instance = JSON.parse(JSON.stringify(this.default_data));
                    }
                    this.process_data_shrink();

                }
            });
        </script>
        <?php
    }

    function widget($args, $i) {
        echo $args['before_widget'];
        ?>
        <div id="<?php echo $i['settings']['slider_id']; ?>" class="carousel slide" data-ride="carousel" style="max-height: <?php echo $i['settings']['height']; ?>px;overflow: hidden;">
            <!-- Indicators -->
            <?php
            if( $i['settings']['show_nav'] ) {
                ?>
                <ol class="carousel-indicators">
                    <?php
                    foreach ( $i['data'] as $key => $slide ) {
                        ?>
                        <li data-target="#<?php echo $i['settings']['slider_id']; ?>" data-slide-to="<?php echo $key; ?>" <?php echo $key == 0 ? 'class="active"' : ''; ?> ></li>
                        <?php
                    }
                    ?>
                </ol>
                <?php
            }
            ?>


            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <?php
                foreach ( $i['data'] as $key => $slide ) {
                        ?>
                        <div class="item <?php echo $key == 0 ? 'active' : ''; ?>">
                            <?php
                            if( $slide['img']['src'] ) {
                                ?>
                                <img src="<?php echo $slide['img']['src']; ?>" alt="Slider">
                                <?php
                            }
                            ?>
                            <div class="carousel-caption">
                                <?php
                                if( $slide['title'] ) {
                                    ?>
                                    <h4><?php echo $slide['title']; ?></h4>
                                    <?php
                                }
                                if( $slide['text'] ) {
                                    ?>
                                        <p><?php echo $slide['text'] ;?></p>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                }
                ?>
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#<?php echo $i['settings']['slider_id']; ?>" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#<?php echo $i['settings']['slider_id']; ?>" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <?php
        echo $args['after_widget'];
    }
}

add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Carousel Slider',
        'name' => 'Wpsb_Carousel',
        'id_base' => 'wpsb_carousel_slider'
    ) );
} );