<?php

class Lego_Pagebuilder_Panel {

    protected $lego_layout;
    protected $is_lego_enabled;

    public function __construct(){
        add_action( 'add_meta_boxes', array( $this, 'pagebuilder_meta_box' ), 10, 2 );
        add_action( 'edit_form_after_title' , array( $this, 'pagebuilder_button' ) );
        add_action( 'save_post', array( $this, 'save_lego_data' ) );
    }


    /**
     * Enable lego pagebuilder button
     */
    function pagebuilder_button(){
        ?>
        <div class="bs-container" v-cloak>
            <input type="button" class="btn btn-primary mt20 br0" v-if="is_lego_enabled != 'true'" @click="enable_lego()" value="<?php _e( 'Enable Pagebuilder', 'wp-sitebuilder' ); ?>">
            <input type="button" class="btn btn-red mt20 br0" v-else @click="is_lego_enabled = 'false'" value="<?php _e( 'Enable Default Editor', 'wp-sitebuilder' );?>">
            <input type="hidden" name="is_lego_enabled" value="{{ is_lego_enabled }}" />
        </div>

    <?php
    }

    function pagebuilder_meta_box( $post_type, $post ) {
        add_meta_box( 'lego-pagebuilder-editor', 'Pagebuilder', array( $this, 'pagebuilder_editor_content' ) , $post_type, 'advanced', 'default' );
    }

    function pagebuilder_editor_content( $post ){
        //load components
        include_once WPSB_ROOT.'/includes/admin/partials.php';
        include_once WPSB_ROOT.'/components/element-components.php';
        //saved_lego_data
        $this->lego_layout = get_post_meta( $post->ID, 'lego_layout', true );
        $this->is_lego_enabled = get_post_meta( $post->ID, 'is_lego_enabled', true );
        ?>
        <script>
            var lego_layout = JSON.parse('<?php echo json_encode( maybe_unserialize($this->lego_layout,false ) ) ; ?>');
            var is_lego_enabled = '<?php echo $this->is_lego_enabled; ?>';
        </script>
        <?php
        include_once WPSB_ROOT.'/widgets/vue.widgets.php';
        ?>
        <?php wp_nonce_field('save', '_wpsb_builder_nonce') ?>
        <div class="bs-container" v-cloak>
            <div>
                <div class="row">
                    <div class="col-sm-12 mb20">
                        <div v-if="is_lego_enabled == 'true'">
                            <a href="javascript:" class="btn btn-primary mr5 br0" @click="add_element()"><?php _e('Elements','wpsb');?></a>
                            <a href="javascript:" class="btn btn-red mr5 br0" @click="show_section_layout = 'true'"><?php _e('Section','wpsb');?></a><!--add_row()-->
                            <a href="javascript:" class="btn btn-warning mr5 br0" @click="add_placeholder()"><?php _e('Placeholder');?></a>
                        </div>
                        <section_layout :show_section_layout.sync="show_section_layout"></section_layout>
                        <modal :show.sync="show_custom_layout_input">
                            <div slot="header">
                                <h3><?php _e('Custom Layout Input','wpsb')?></h3>
                            </div>
                            <div slot="body">
                                <div class="bs-container">
                                    <wpsb_custom_layout_input :maxspan="grid_number"></wpsb_custom_layout_input>
                                </div>
                            </div>
                        </modal>
                        <?php $widgets = $GLOBALS['wp_widget_factory'];
                        global $wpsb_widgets;
                        //pri($wpsb_widgets);
                        //pri($widgets);
                        $widget_list = array();
                        foreach( $widgets->widgets as $name => $widget_data ) {
                            if( strstr( $name, 'WP_' ) ) {
                                $widget_list['wp'] .= '<a @click=\'render_element_form( "'.$widget_data->name.'", "widget", "'.$name.'", "", "'.$widget_data->id_base.'", "true" )\' data-type="widget" data-label="'.$widget_data->name.'" data-instance="" data-name="'.$name.'" href="javascript:" class="btn btn-default mt5 mr5 mb5 br0 lego-widget-button">'.$widget_data->name.'</a>';
                            } elseif( strstr( $name, 'WC_' ) ) {
                                $widget_list['woocommerce'] .= '<a @click=\'render_element_form( "'.$widget_data->name.'", "widget", "'.$name.'", "" , "'.$widget_data->id_base.'", "true" )\' data-type="widget" data-label="'.$widget_data->name.'" data-instance="" data-name="'.$name.'" href="javascript:" class="btn btn-default mt5 mr5 mb5 br0 lego-widget-button">'.$widget_data->name.'</a>';
                            } else {
                                $widget_list['custom'] .= '<a @click=\'render_element_form( "'.$widget_data->name.'", "widget", "'.$name.'", "" , "'.$widget_data->id_base.'", "true" )\' data-type="widget" data-label="'.$widget_data->name.'" data-instance="" data-name="'.$name.'" href="javascript:" class="btn btn-default mt5 mr5 mb5 br0 lego-widget-button">'.$widget_data->name.'</a>';
                            }
                        }

                        foreach ( $wpsb_widgets as $widget_name => $lego_widget ) {
                            $widget_list['wp-sitebuilder'] .= '<a @click=\'render_element_form( "' . $lego_widget['label'] . '", "widget", "' . $widget_name . '", "" , "'.$lego_widget['id_base'].'", "false" )\' data-type="widget" data-label="" data-instance="" data-name="" href="javascript:" class="btn btn-default mt5 mr5 mb5 br0 lego-widget-button">' . $lego_widget['label'] . '</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <modal :show.sync="show" default_button="false">
                <div slot="header">
                    <h3><?php _e('Elements','wpsb');?></h3>
                </div>
                <div slot="body">
                    <widget_list>
                        <div slot="acc-panel">
                            <button><?php _e('Wordpress Elements', 'wp-sitebuilder'); ?></button>
                            <div>
                                <div class="p10">
                                    <?php echo $widget_list['wp']; ?>
                                </div>
                            </div>
                            <button><?php _e('WooCommerce Elements', 'wp-sitebuilder'); ?></button>
                            <div>
                                <div class="p10">
                                    <?php echo $widget_list['woocommerce']; ?>
                                </div>
                            </div>
                            <button><?php _e('Custom Elements', 'wp-sitebuilder'); ?></button>
                            <div>
                                <div class="p10">
                                    <?php echo $widget_list['custom']? $widget_list['custom'] : __('There is not custom widgets','wp-sitebuilder'); ?>
                                </div>
                            </div>
                            <button><?php _e('WP Sitebuilder Elements', 'wp-sitebuilder'); ?></button>
                            <div>
                                <div class="p10">
                                    <?php echo $widget_list['wp-sitebuilder']? $widget_list['wp-sitebuilder'] : __('There is not lego widgets','wp-sitebuilder'); ?>
                                </div>
                            </div>
                        </div>
                    </widget_list>
                </div>
                <div slot="footer">
                    <a href="javascript:" class="btn btn-danger br0" @click="show = false"><?php _e('Cancel','wp-sitebuilder');?></a>
                </div>
            </modal>
            <modal
                :show.sync="show_property_form"
            >
                <div slot="header">
                    <h3>Properties</h3>
                </div>
                <div slot="body">
                    <property_modal
                        :target_property="target_obj_property.property"
                    >
                    </property_modal>
                </div>
                <div slot="footer">
                    <a href="javascript:" class="btn btn-red br0" @click="cancel_property_form()"><?php _e('Cancel','wp-sitebuilder');?></a>
                    <a href="javascript:" class="btn btn-green br0" @click="update_property()"><?php _e('Save','wp-sitebuilder');?></a>
                </div>
            </modal>

            <lego_element_modal :show_element_modal.sync="show_widget_modal">
                <div slot="header">

                </div>
                <div slot="body">
                    <div slot="bs-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h3 class="widget-label"></h3>
                                    <div class="widget-body" id="widget-body">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <property_modal
                                        :target_property="target_elem.property"
                                    >
                                    </property_modal>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="widget-footer">
                        <a href="javascript:" class="btn btn-red br0" @click="cancel_element_insert()"><?php _e('Cancel','wp-sitebuilder');?></a>
                        <a href="javascript:" class="btn btn-success br0 insert-element" @click="insert_into_placeholder()"><?php _e('Insert','wp-sitebuilder'); ?></a>
                    </div>
                </div>
            </lego_element_modal>
            <!--element preview-->
            <wpsb_preview_modal :show.sync="preview_modal">
                <div slot="header">
                    <h3><?php _e('Preview','wpsb'); ?></h3>
                </div>
                <div slot="body">
                    {{{ target_preview }}}
                </div>
                <div slot="footer">
                    <div class="">
                        <a href="javascript:" class="btn btn-success br0 insert-element" @click="cancel_preview()"><?php _e('Okay','wp-sitebuilder'); ?></a>
                    </div>
                </div>
            </wpsb_preview_modal>
            <!--end element preview-->
            <div class="lego-ground" v-if="is_lego_enabled == 'true'" v-cloak>
                <input type="hidden" class="lego-builder-data" value="{{ lego_layout_min }}" id="{{ ground_data_id }}" name="lego-builder-data" />
                <div>
                    <template v-for="( cont_id, cont_data ) in lego_layout.container">
                        <lego_container :cont_id="cont_id" :cont_data="cont_data" :lego_layout="lego_layout"
                                        :parent_type="''" :parent_id="''" :child_type="'row'" :preview_data="preview_data" :type="'container'"
                                        :grid_number="grid_number"
                        ></lego_container>
                    </template>
                </div>
            </div>
        </div>
<?php

    }


    /**
     * Save data
     * @param $post_id
     */
    function save_lego_data( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( empty( $_POST['_wpsb_builder_nonce'] ) || !wp_verify_nonce( $_POST['_wpsb_builder_nonce'], 'save' ) ) return;
        if ( !current_user_can( 'edit_post', $post_id ) ) return;

        update_post_meta( $post_id, 'is_lego_enabled', $_POST['is_lego_enabled'] );

        if ( !isset( $_POST['lego-builder-data'] ) ) return;
        update_post_meta( $post_id, 'lego_layout', maybe_serialize( json_decode( wp_unslash( $_POST['lego-builder-data'] ), true ) ) );

    }

    public static function init(){
        new Lego_Pagebuilder_Panel();
    }
}
Lego_Pagebuilder_Panel::init();