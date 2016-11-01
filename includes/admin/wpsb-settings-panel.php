<?php include_once WPSB_ROOT.'/components/element-components.php'; ?>
<div class="bs-container">
    <div class="container" id="wpsb-settings" v-cloak>
        <div class="row">
            <div class="col-sm-12 mb10">
                <h4><?php _e( 'Disable pagebuilder for : ', 'wpsb'); ?></h4>
            </div>
            <div class="col-sm-12 mb10">
                <?php
                $post_types = get_post_types();
                $post_types = apply_filters( 'wpsb_pb_disablitiy_list', $post_types );
                ?>
                <div v-for="( typename, typelabel ) in post_types">
                    <div v-if="disable_post_types[typename] == 'false'"> aaa
                        {{ disable_post_types[typename] = false }}
                    </div>
                    <div v-if="disable_post_types[typename] == 'true'">
                        {{ disable_post_types[typename] = true }}
                    </div>
                    <label><input type="checkbox" v-model="disable_post_types[typename]" > {{ typelabel }} </label>
                </div>
            </div>
            <div class="col-sm-12">
                <input type="submit" name="save_wpsb_settings" @click="save_wpsb_settings" value="<?php _e( 'Save', 'wpsb' ); ?>">
            </div>
        </div>
        <notice_modal :show.sync="show_result_panel" default_button="true" disappear="auto">
            <div slot="header">
                <div v-if="result.success" class="alert alert-success">
                    {{ result.success }}
                </div>
            </div>
        </notice_modal>
    </div>
    <script>
        jQuery(document).ready(function () {
            var wpsb_settings = new Vue({
                el : '#wpsb-settings',
                data : {
                    post_types : JSON.parse('<?php echo json_encode($post_types); ?>'),
                    disable_post_types : JSON.parse('<?php echo json_encode( get_non_pagebuilder_post_types() ); ?>'),
                    result : {},
                    show_result_panel : false
                },
                methods : {
                    save_wpsb_settings : function () {
                        jQuery.post(
                            ajaxurl,
                            {
                                action : 'wpsb_disabled_pagebuilder_for_post_types',
                                disabled_for_post_types : wpsb_settings.disable_post_types
                            },
                            function (data) {
                                wpsb_settings.result = JSON.parse(data);
                                if( wpsb_settings.result.success ) {
                                    wpsb_settings.show_result_panel = true;
                                }

                            }
                        )
                    }
                }
            })
        });
    </script>
</div>