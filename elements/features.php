<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpsb_Features extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'wpsb_features', // Base ID
            __( 'Features', 'wp-sitebuilder' ), // Name
            array( 'description' => __( 'Features', 'wp-sitebuilder' ), ) // Args
        );
    }


    public function form( $i ){
        if( empty($i) ) {
            $i = array();
        }

        //Grab contents of css file
        $file = file_get_contents(WPSB_ASSET_PATH.'/css/font-awesome.min.css');

        $selectors = array();
        $pattern = '/(?<=\}.fa)(.*?)(?=[\{\:]{1}?)/';
        $matches = preg_match_all($pattern, $file, $selectors);
        $selectors = array_unique($selectors[0]);
        ?>
        <div class="wpsb_widget_form">
            <div class="mb10">
                <a href="javascript:" data-target="feature" class="btn btn-default br0" @click="selected_tab = 'feature'"><?php _e('Features','wpsb'); ?></a>
                <a href="javascript:" data-target="settings" class="btn btn-default br0" @click="selected_tab = 'settings'"><?php _e('Settings','wpsb'); ?></a>
            </div>
            <!--Features-->
            <div v-show="selected_tab == 'feature'" style="border: 1px solid #ccc; padding: 5px;">
                <a href="javascript:" class="btn btn-default" @click="add_feature_panel()"><?php _e('Add Feature','wpsb'); ?></a>
                <div v-for="( k ,d ) in instance.data" class="container-fluid">
                    <div class="row wpsb_element_panel" :data-key="k">
                        <div class="col-sm-8" :class="{ wpsb_shrink : data_shrink[k] }">
                            <input type="text" class="form-control mb5" v-model="d.label" name="<?php echo $this->get_field_name('data');?>[{{ k }}][label]">
                            <label><input type="radio" class="mb5" v-model="d.img.type" name="<?php echo $this->get_field_name('data');?>[{{ k }}][img][type]" value="icon"> <?php _e( 'Icon','wpsb'); ?></label>
                            <label><input type="radio" class="mb5" v-model="d.img.type" name="<?php echo $this->get_field_name('data');?>[{{ k }}][img][type]" value="image"> <?php _e( 'Image','wpsb'); ?></label>
                            <div v-show="d.img.type == 'image'" class="mb10">
                                <div v-if="d.img.type == 'image'" class="mb10">
                                    <img src="{{ d.img.src }}" width="200" alt="image" v-if="d.img.src">
                                </div>
                                <input type="text" class="form-control mb5" v-model="d.img.src" :value="{{ d.img.src }}" name="<?php echo $this->get_field_name('data');?>[{{ k }}][img][src]">
                                <button class="wpsb_set_custom_images button" @click="popup_media_panel(d)">Change Image</button>
                            </div>
                            <!--icon-->
                            <div v-show="d.img.type == 'icon'" class="mb10">
                                <div>
                                    <span><i class="fa fa-{{ d.img.icon_size }}x fa{{ d.img.icon }}"></i></span>
                                </div>
                                <div>
                                    <label><?php _e('Icon Size', 'wpsb' ); ?></label>
                                    <input type="number" max="5" class="form-control" v-model="d.img.icon_size" name="<?php echo $this->get_field_name('data');?>[{{ k }}][img][icon_size]">
                                </div>

                                <a href="javascript:" class="btn btn-default br0 mb10" @click="icon_panel = true">Change Icon</a>
                                <input type="hidden" v-model="d.img.icon" name="<?php echo $this->get_field_name('data');?>[{{ k }}][img][icon]">
                                <div style="height: 200px; overflow-y: scroll;" v-if="icon_panel == true">
                                    <?php foreach ( $selectors as $selector ): ?>
                                        <?php if( strstr( $selector, '{' ) || strstr( $selector, '.' ) || strstr( $selector, '>' ) || strstr( $selector, ':' ) ): continue ;?>
                                        <?php endif;?>
                                        <a href="javascript:" @click="add_icon(d,'<?php echo $selector; ?>')"><i style="border: 1px solid #ccc" class="m5 oh p5 fa fa-2x fa<?php echo $selector; ?>"></i></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <textarea v-model="d.text" class="form-control mb5" cols="30" rows="10" name="<?php echo $this->get_field_name('data');?>[{{ k }}][text]"></textarea>
                        </div>
                        <div class="col-sm-4">
                            <span class="btn btn-default" @click="place_up(k)"><i class="glyphicon glyphicon-arrow-up"></i></span>
                            <span class="btn btn-default" @click="place_down(k)"><i class="glyphicon glyphicon-arrow-down"></i></span>
                            <span class="btn btn-default" @click="expand_featured_panel(k)" v-if="data_shrink[k] == true"><i class="glyphicon glyphicon-collapse-down"></i></span>
                            <span class="btn btn-default" @click="shrink_featured_panel(k)" v-if="data_shrink[k] == false"><i class="glyphicon glyphicon-collapse-up"></i></span>
                            <span class="btn btn-default" @click="remove_feature_panel(k)"><i class="glyphicon glyphicon-remove"></i></span>

                        </div>
                    </div>
                </div>
            </div>
            <!--Settings-->
            <div v-show="selected_tab == 'settings'" style="border: 1px solid #ccc; padding: 5px;">
                <div>
                    <label><?php _e('Feature per Column','wpsb'); ?></label>
                    <input class="form-control" type="number" v-model="instance.settings.num_per_col" name="<?php echo $this->get_field_name('settings');?>[num_per_col]" />
                </div>
                <div>
                    <label><?php _e('Space between Features','wpsb');?></label>
                    <input type="number" class="form-control" v-model="instance.settings.feature_space" name="<?php echo $this->get_field_name('settings');?>[feature_space]">
                </div>
            </div>
        </div>
        <script>
            var formapp = new Vue({
                el : '.wpsb_widget_form',
                data : {
                    instance : JSON.parse('<?php echo json_encode($i)?>'),
                    default_data : {
                        settings : {
                            num_per_col : 3,
                            feature_space : 15,
                        },
                        data : []
                    },
                    data_template : {
                        label : 'Feature label',
                        img : {
                            type : 'icon',//image
                            src : '',
                            icon : '',
                            icon_size : 3,

                        },
                        text : 'Lorem ipsum text'
                    },
                    data_shrink : {},
                    icon_panel : false,
                    icons : JSON.parse('<?php echo json_encode($selectors)?>'),
                    selected_tab : 'feature'
                },
                methods : {
                    add_feature_panel : function () {
                        formapp.instance.data.push( JSON.parse( JSON.stringify(formapp.data_template) ));
                        this.process_data_shrink();
                    },
                    remove_feature_panel : function (k) {
                        formapp.instance.data.splice(k,1);
                        Vue.delete(this.data_shrink,k);
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
                    expand_featured_panel : function (k) {
                        Vue.set( this.data_shrink, k , false );
                    },
                    shrink_featured_panel : function (k) {
                        Vue.set( this.data_shrink, k , true );
                    },
                    process_data_shrink : function () {
                        for( k in JSON.parse(JSON.stringify(this.instance.data)) ) {
                            if(typeof this.data_shrink[k] == 'undefined') {
                                Vue.set(this.data_shrink,k,true);
                            }
                        }
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
                    add_icon : function (d,icon_class) { console.log(d);
                        d.img.icon = icon_class;
                        console.log(d);
                    }


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
        <div class="bs-container">
            <div class="container-fluid">
                <div class="row">
                    <?php
                    $span = ( 12/$i['settings']['num_per_col'] );
                    foreach ( $i['data'] as $key => $panel ) {
                        ?>
                        <div class="col-sm-<?php echo $span;?>" style="padding:<?php echo $i['settings']['feature_space']; ?>px;">
                            <div style="border: 1px solid #cccccc; padding: 15px;">
                                <h3><?php echo $panel['label']; ?></h3>
                                <div class="text-center">
                                    <?php if( $panel['img']['type'] == 'icon' ): ?>
                                        <span><i class="fa fa-<?php echo $panel['img']['icon_size']; ?>x fa<?php echo $panel['img']['icon']; ?>"></i></span>
                                    <?php elseif( $panel['img']['type'] == 'image' ): ?>
                                        <img src="<?php echo $panel['img']['src']; ?>" alt="<?php echo $panel['label']; ?>" width="100" class="img-responsive img-circle">
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php echo $panel['text']; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }
}

add_action( 'widgets_init', function () {
    wpsb_register_widget( array(
        'label' => 'Features',
        'name' => 'Wpsb_Features',
        'id_base' => 'wpsb_features'
    ) );
} );