<template type="text/template" id="widget-modal">
    <div class="modal-mask" v-show="show_element_modal == true" transition="modal">

        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <slot name="header"></slot>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>

                <div class="modal-footer">
                    <slot name="footer"></slot>
                    <button v-if="default_button == 'true'" class="modal-default-button"
                            @click="show = false">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
</template>
<template id="widget-list-accordion-template">
    <div class="accordion" id="{{ accordion_id }}">
        <slot name="acc-panel">
            <button>Section 1</button>
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>

            <button>Section 2</button>
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
            <button>Section 3</button>
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
        </slot>
    </div>
</template>
<template id="property-modal-template">
    <div id="{{ tab_id }}">
        <ul class="tab">
            <li v-for="( i, property_name ) in active_property_names"><a href="javascript:" @click="open_tab(i)">{{ property_name }}</a></li>
        </ul>
        <div class="tab-data">
            <div slot="tabdata">
                <div v-for=" each_property in active_property_names">
                    <div class="form-group" v-for="property_name in active_properties[each_property]">
                        <label>{{ all_property[each_property][property_name].label }}</label>
                        <input type="text" class="form-control" v-if="all_property[each_property][property_name].type == 'text'" v-model="target_property[each_property][property_name]">
                        <textarea class="form-control" v-if="all_property[each_property][property_name].type == 'textarea'" v-model="target_property[each_property][property_name]"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<template id="wpsb-section-layout">
    <div class="section_layout" v-if="show_section_layout == 'true'">
        <h5><?php _e('Select section layout','wpsb'); ?></h5>
        <ul class="flul">
            <li><a href="javascript:" @click="dispatch_make_section([10,2]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-five_sixth.png'?>" alt=""></a></li>
            <li><a href="javascript:" @click="dispatch_make_section([3,3,3,3]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-fourth.png'?>" alt=""></a></li>
            <li><a href="javascript:" @click="dispatch_make_section([6,6]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-half.png'?>" alt=""></a></li>
            <li><a href="javascript:" @click="dispatch_make_section([12]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-full.png'?>" alt=""></a></li>
            <li><a href="javascript:" @click="dispatch_make_section([4,4,4]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-third.png'?>" alt=""></a></li>
            <li><a href="javascript:" @click="dispatch_make_section([6,3,3]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-three_fifth.png'?>" alt=""></a></li>
            <li><a href="javascript:" @click="dispatch_make_section([9,3]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-three_fourth.png'?>" alt=""></a></li>
            <li><a href="javascript:" @click="dispatch_make_section([6,2,2,2]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-two_fifth.png'?>" alt=""></a></li>
            <li><a href="javascript:" @click="dispatch_make_section([8,4]);"><img src="<?php echo WPSB_ASSET_PATH.'/images/wpsb-two_third.png'?>" alt=""></a></li>
            <li><a href="javascript:" class="btn btn-default" @click="dispatch_bring_custom_layout();">Custom</a></li>
        </ul>
    </div>
</template>
<template id="wpsb_custom_layout_input">
    <label><?php _e( 'Number of placeholders/divisions'); ?></label>
    <div class="mb10 mt10">
        <input type="number" v-model="placeholder_nums" class="form-control" min="0" @change="build_array()">
    </div>
    <div v-if="placeholder_nums > 0">
        <label><?php _e( 'Enter the width of each placeholder below' ); ?></label>
    </div>
    <label v-for="( k,v ) in span_values" class="mb10 mr10">
        <input type="number" class="form-control" v-model="v" min="0" max="{{ maxspan }}">
    </label>
    <div>
        <a href="javascript:" class="btn btn-default pull-right br0" @click="dispatch_make_section(span_values)"><?php _e( 'Insert', 'wpsb' ); ?></a>
        <a href="javascript:" class="btn btn-default pull-right br0" @click="dispatch_cancel_modal()"><?php _e( 'Cancel', 'wpsb' ); ?></a>
    </div>
</template>
<!--element preview modal-->
<template type="text/template" id="wpsb_preview_modal">
    <div id="preview_modal">
        <div class="modal-mask" v-show="show" transition="modal">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-content-wrapper">
                        <div class="modal-header">
                            <slot name="header"></slot>
                        </div>

                        <div class="modal-body">
                            <slot name="body"></slot>
                        </div>

                        <div class="modal-footer">
                            <slot name="footer"></slot>
                            <button v-if="default_button == 'true'" class="modal-default-button"
                                    @click="show = false">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<!--end element preview modal-->