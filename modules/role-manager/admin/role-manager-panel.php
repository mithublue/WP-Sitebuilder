<?php include_once WPSB_ROOT.'/components/element-components.php'; ?>
<div id="sbrm_app" class="bs-container m20" v-cloak>
    <?php
    global $wp_roles;
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 mb10">
                <a href="javascript:" class="btn btn-green br0" @click="show_add_role_panel = true"><?php _e( 'Create Role', 'sbrm' ); ?></a>
            </div>
            <div class="col-sm-12 mb10" v-if="show_add_role_panel == true">
                <div class="sbrm-add-role-panel">
                    <div class="mb10">
                        <a href="javascript:" class="pull-right btn-red btn btn-xs br0" @click="show_add_role_panel = false"><i class="glyphicon glyphicon-remove"></i></a>
                    </div>
                    <div class="mb10">
                        <label><?php _e( 'Role name (without space)', 'sbrm'); ?></label>
                        <input class="form-control br0" type="text" v-model="newrole.name">
                    </div>
                    <div class="mb10">
                        <label><?php _e( 'Role label', 'sbrm'); ?></label>
                        <input class="form-control br0" type="text" v-model="newrole.label">
                    </div>
                    <div class="mb10">
                        <a href="javascript:" class="btn btn-default br0" @click="add_role()"><?php _e( 'Add', 'sbrm' ); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mb10">
                <h4><?php _e( 'Roles', 'sbrm'); ?></h4>
                <div class="sbrm-role-list btn-group">
                    <a href="javascript:" class="btn btn-default" v-bind:class="{active : ( targeted_role == rolename )}" v-for="(rolename,role_array) in saved_role_caps.roles" @click="targeted_role = rolename"> {{ role_array.name }}
                        <i class="glyphicon glyphicon-remove" @click="remove_role(rolename)"></i>
                    </a>
                </div>
            </div>
            <div class="col-sm-12 mb10">
                <h4><?php _e( 'Capabilities', 'sbrm'); ?></h4>
                <div class="sbrm-capabilities-list">
                    <div class="row">
                        <label class="col-sm-3" v-for="(capname, capval) in all_caps">
                            <div v-if="saved_role_caps.roles[targeted_role].capabilities[capname] == 'false'">
                                {{ saved_role_caps.roles[targeted_role].capabilities[capname]  = 0 }}
                            </div>
                            <div v-if="saved_role_caps.roles[targeted_role].capabilities[capname] == 'true'">
                                {{ saved_role_caps.roles[targeted_role].capabilities[capname]  = 1 }}
                            </div>
                            <input type="checkbox" v-model="saved_role_caps.roles[targeted_role].capabilities[capname]" :true-value="1" :false-value="0"> {{ capname }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <a href="javascript:" class="btn btn-green br0" @click="save_role_data()"><?php _e( 'Save' , 'sbrm' ); ?></a>
            </div>
        </div>
    </div>
    <notice_modal :show.sync="show_save_result_modal" default_button="false" disappear="auto">
        <div slot="header">
            <div v-if="saved_result.success" class="alert alert-success">
                {{ saved_result.success }}
            </div>
        </div>
        <div slot="body">

        </div>
    </notice_modal>
</div>
<script>
    jQuery(document).ready(function () {
        var sbrm_app = new Vue({
            el : '#sbrm_app',
            data : {
                saved_role_caps : JSON.parse('<?php echo json_encode($wp_roles)?>'),
                targeted_role : 'administrator',
                all_caps : {},
                newrole : {
                    name : '',
                    label : ''
                },
                show_add_role_panel : false,
                current_user_role : JSON.parse('<?php echo json_encode(wp_get_current_user()->roles); ?>'),
                show_save_result_modal : false,
                saved_result : {},
                test: 'false'
            },
            methods : {
                remove_role : function (rolename) {
                    if ( this.current_user_role.indexOf(rolename) == '-1' ) {
                        Vue.delete( this.saved_role_caps.roles, rolename )
                    } else {
                        alert( 'You cannot delete the role that is assigned to you !' );
                    }

                },
                add_role : function () {
                    console.log(this.newrole);
                    if( this.newrole.name && this.newrole.label ) {
                        Vue.set( this.saved_role_caps.roles, this.newrole.name , {
                            name : this.newrole.label,
                            capabilities : {}
                        } );
                        this.reset();
                    }
                },
                reset : function () {
                    this.newrole = {
                        name : '',
                        label : ''
                    }
                },
                save_role_data : function () {
                    var role_caps_data = this.saved_role_caps.roles;
                    jQuery.post(
                        ajaxurl,
                        {
                            action : 'sbrm_save_role_caps_data',
                            role_caps_data : role_caps_data
                        },
                        function (data) {
                            sbrm_app.saved_result = data = JSON.parse( data );
                            if( sbrm_app.saved_result.success ) {
                                sbrm_app.show_save_result_modal = true;
                            }

                        }
                    )
                }
            },
            ready : function () {
                for( rolename in this.saved_role_caps.roles ) {
                    this.all_caps = jQuery.extend( {} , this.all_caps, this.saved_role_caps.roles[rolename]['capabilities'] );
                }
            }

        })
    });

</script>