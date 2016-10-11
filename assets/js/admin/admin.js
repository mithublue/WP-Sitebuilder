/** post edit page **/
(function ($) {
    /** partials components js**/
    Vue.component('lego_container',{
        template : '#container-template',
        props : ['cont_id', 'cont_data','lego_layout',
            'parent_type','parent_id','child_type' ,
            'preview_data', //for elem
            'type',
            'grid_number'
        ],
        ready : function () {
            lego_obj.make_intarectable();
        },
        methods : {
            dispatch_add_row : function (cont_id) {
                this.$dispatch( 'event_add_row', cont_id );
            }
        }
    });
    Vue.component('lego_row',{
        template : '#row-template',
        props : ['row_id','row_data','lego_layout','parent_type','parent_id','child_type',
            'preview_data', //for elem
            'type',
            'grid_number'
        ],
        methods : {
            dispatch_remove : function (obj) {
                this.$dispatch( 'remove_item', obj );
            },
            dispatch_add_placeholder : function (row_id) {
                this.$dispatch( 'event_add_placeholder', row_id );
            },
            dispatch_render_row_form : function (obj) {
                this.$dispatch( 'event_render_property_form', { id: obj.id, type : obj.type, property : JSON.parse(JSON.stringify(obj.property)) }  );
            }
        },
        ready : function () {
            lego_obj.make_intarectable();
        }
    });
    Vue.component('lego_col',{
        template : '#col-template',
        props : ['col_id','col_data','lego_layout','parent_type','parent_id','child_type',
            'preview_data',// for elem
            'type',
            'grid_number'
        ],
        data : function () {
            return {
                grid_array : [],
                show_grid_number_panel : false,
                test_data : true
            }
        },
        methods : {
            popup_widgetslist  : function (col_id) {
                //show widgetlist popup
                lego_pagebuilder.show = true;
                //set col to place widget
                lego_pagebuilder.target_col = col_id;
            },
            dispatch_remove : function (obj) {
                this.$dispatch( 'remove_item', obj );
            },
            grid_enlarge : function ( obj ) {
                if( lego_pagebuilder.lego_layout.col[obj.id].span < lego_pagebuilder.grid_number ) {
                    lego_pagebuilder.lego_layout.col[obj.id].span++;
                }
            },
            grid_shorten : function ( obj ) {
                if( lego_pagebuilder.lego_layout.col[obj.id].span > 1 ) {
                    lego_pagebuilder.lego_layout.col[obj.id].span--;
                }
            },
            change_grid_width : function (obj) {
                this.show_grid_number_panel = false;
                lego_pagebuilder.lego_layout[obj.type][obj.id].span = obj.span;
            },
            dispatch_render_col_form : function ( obj ) {
                this.$dispatch( 'event_render_property_form', obj );
            },
            dispatch_add_section_in_placeholder : function (obj) {
                this.$dispatch( 'event_add_section_in_placeholder', obj );
            },
        },
        ready : function () {
            lego_obj.make_intarectable();
            for( i = 1; i <= this.grid_number; i++ ) {
                this.grid_array.push(i);
            }
        }
    });
    Vue.component('lego_element',{
        template : '#element-template',
        props : ['elem_id','elem_data','lego_layout','parent_id','parent_type','child_type',
            'preview_data', //for elem
            'type'
        ],
        computed : {
                widget_view : function () {
                    return ;
                }
        },
        methods : {
            dispatch_render_element_form : function ( elem_id, label, type, name, instance , id_base ) {
                this.$dispatch( 'event_render_element_form', {  elem_id : elem_id, parent_type : this.parent_type, parent_id : this.parent_id , label : label, type : type, name : name, instance : instance, id_base : id_base } );
            },
            dispatch_remove : function (obj) {
                this.$dispatch( 'remove_item', obj );
            },
            dispatch_preview_element : function (elem_id) {
                this.$dispatch( 'event_preview_element', elem_id );
            }
        },
        ready : function () {
            lego_obj.make_intarectable();
        }
    });
    /*** partials components js end **/
    var lego_obj = {
        init : function () {
            lego_obj.make_intarectable();
        },
        make_intarectable : function () {
            $('.lego-ground .container-fluid,.lego-ground .container').sortable({
                items : '.row',
                connectWith : '.lego-ground .container-fluid,.lego-ground .container',
                start: function( event, ui ){
                    lego_obj.sort_start( event, ui );
                },
                receive : function( event, ui ) {
                    lego_obj.sort_receive( event, ui );
                },
                stop : function ( event, ui ) {
                    lego_obj.sort_stop( event, ui );
                }
            });
            $('.lego-ground .row').sortable({
                items : '.lego-placeholder',
                connectWith : '.lego-ground .row',
                start: function( event, ui ){
                    lego_obj.sort_start( event, ui );
                },
                receive : function( event, ui ) {
                    lego_obj.sort_receive( event, ui );
                },
                stop : function ( event, ui ) {
                    lego_obj.sort_stop( event, ui );
                }
            });
            $('.lego-ground .lego-placeholder').sortable({
                items : '.lego-elem',
                connectWith : '.lego-ground .lego-placeholder',
                start: function( event, ui ){
                    lego_obj.sort_start( event, ui );
                },
                receive : function( event, ui ) {
                    lego_obj.sort_receive( event, ui );
                },
                stop : function ( event, ui ) {
                    lego_obj.sort_stop( event, ui );
                }
            });
        },

        sort_start : function ( event, ui ) {
            lego_pagebuilder.sorting_obj.obj_id = $(ui.item).attr('id');
            lego_pagebuilder.sorting_obj.type = $(ui.item).data('type');

            lego_pagebuilder.sorting_obj.parent_type = $(ui.item).data('parent_type');
            lego_pagebuilder.sorting_obj.parent_id = $(ui.item).data('parent_id');
            lego_pagebuilder.sorting_obj.order = lego_pagebuilder.lego_layout[lego_pagebuilder.sorting_obj.parent_type][lego_pagebuilder.sorting_obj.parent_id].child[lego_pagebuilder.sorting_obj.type].indexOf(lego_pagebuilder.sorting_obj.obj_id);
        },
        sort_receive : function ( event, ui ) {
            lego_pagebuilder.sorting_obj.new_parent_id =  $(event.target).attr('id');
            lego_pagebuilder.sorting_obj.new_parent_type = $(event.target).data('type');
            lego_pagebuilder.sorting_obj.new_order = $(ui.item).index() - 1;
        },
        sort_stop : function ( event, ui ) {
            if( !lego_pagebuilder.sorting_obj.new_parent_id ) {
                lego_pagebuilder.sorting_obj.new_parent_id =  $(event.target).attr('id');
                lego_pagebuilder.sorting_obj.new_parent_type = $(event.target).data('type');
                lego_pagebuilder.sorting_obj.new_order = $(ui.item).index() - 1;
            } else {
                $(ui.item).remove();
            }

            lego_pagebuilder.lego_layout[lego_pagebuilder.sorting_obj.parent_type][lego_pagebuilder.sorting_obj.parent_id].child[lego_pagebuilder.sorting_obj.type].splice(lego_pagebuilder.sorting_obj.order,1);
            lego_pagebuilder.lego_layout[lego_pagebuilder.sorting_obj.new_parent_type][lego_pagebuilder.sorting_obj.new_parent_id].child[lego_pagebuilder.sorting_obj.type].splice( lego_pagebuilder.sorting_obj.new_order, 0 , lego_pagebuilder.sorting_obj.obj_id );

            //lego_pagebuilder.lego_layout[lego_pagebuilder.sorting_obj.parent_type][lego_pagebuilder.sorting_obj.parent_id].child[lego_pagebuilder.sorting_obj.type] = [];
            lego_pagebuilder.reset_sorting_object();
        },
    };

    /** main **/
    var lego_pagebuilder = new Vue({
        el : '#wpwrap',
        data : {
            is_lego_enabled : is_lego_enabled, // check if lego editor is enabled or not
            ground_data_id : 'ground-' + new Date().getTime(),

            //grid
            grid_number : 12,
            //default placeholder padding
            placeholder_padding : 15,
            //property form show or hide
            show_property_form : false,

            //section layout
            show_section_layout : false,
            show_custom_layout_input : false,

            //default row property
            default_row_property : {
                attribute : {
                    class : ''
                },
                style : {
                    style : ''
                }
            },
            //placeholder default property
            default_col_property : {
                attribute : {
                    class : ''
                },
                style : {
                    style : ''
                }
            },

            //element default property
            default_elem_property : {
                attribute : {
                    class : ''
                },
                style : {
                    style : ''
                }
            },

            show : false,
            lego_layout : lego_layout,
            target_cont : '',
            target_col : '',
            target_row : '',
            target_elem : {
                id : '', // if id is null, the element is new
                label : '',
                type : '',
                name : '',
                widgetized : '',
                property : ''
            },
            show_widget_modal : false, //for widget modal

            /** preview data **/
            preview_data : {},
            preview_modal : false,
            target_preview : '',

            /** interaction **/
            sorting_obj : {
                obj_id : '',
                type : '',
                order : '',
                parent_type : '',
                parent_id : '',
                new_parent_id : '',
                new_parent_type : '',
                new_order : ''
            },

            /** targeted object data **/
            target_obj_property : {
                id : '',
                type : '',
                property : ''
            },
        },
        computed : {
            lego_layout_min : function() {
                return JSON.stringify( this.lego_layout );
            }
        },

        methods : {
            /**
             * add element to placceholder
             */
            insert_into_placeholder : function () {

                lego_pagebuilder.target_elem.instance = jQuery('.widget-body *').serialize();

                //edit
                var element_id = lego_pagebuilder.target_elem.id;

                if ( lego_pagebuilder.lego_layout.col[lego_pagebuilder.target_col].child.element.indexOf(element_id) == '-1' ) {
                    lego_pagebuilder.lego_layout.col[lego_pagebuilder.target_col].child.element.push(element_id);
                }


                Vue.set( lego_pagebuilder.lego_layout.element, element_id, {
                    label : lego_pagebuilder.target_elem.label,
                    type : lego_pagebuilder.target_elem.type, //widget
                    name : lego_pagebuilder.target_elem.name,
                    instance : lego_pagebuilder.target_elem.instance,
                    id_base : lego_pagebuilder.target_elem.id_base,
                    widgetized : lego_pagebuilder.target_elem.widgetized,
                    property : lego_pagebuilder.target_elem.property
                } );


                /**
                 * update preview
                 */
                //lego_pagebuilder.update_preview(element_id);

                /*** reset to default **/
                lego_pagebuilder.target_col = '';
                lego_pagebuilder.show_widget_modal = false;
                lego_pagebuilder.show = false;
                lego_pagebuilder.reset_target_elem();
                /******** **********/
            },

            /**
             * reset target_elem
             */
            reset_target_elem : function () {
                lego_pagebuilder.target_elem = {
                    label : '',
                    type : '', //widget
                    name : '',
                    instance : '',
                    id_base : '',
                    widgetized : '',
                    property : ''

                };
                lego_pagebuilder.target_obj_property = '';
            },

            reset_all  : function () {
                lego_pagebuilder.target_col = '';
                lego_pagebuilder.target_row = '';
                lego_pagebuilder.target_cont = '';
                lego_pagebuilder.target_elem = {
                    label : '',
                    type : '', //widget
                    name : '',
                    instance : '',
                    id_base : '',
                    property : ''
                };
            },

            /**
             * reset sorting objects
             */
            reset_sorting_object : function () {
                lego_pagebuilder.sorting_obj = {
                    obj_id : '',
                    type : '',
                    order : '',
                    parent_type : '',
                    parent_id : '',
                    new_parent_id : '',
                    new_parent_type : '',
                    new_order : ''
                }
            },

            /*render_col_form : function ( col_id ) {
                lego_pagebuilder.target_obj_property = lego_pagebuilder.lego_layout.col[col_id].property;
                lego_pagebuilder.show_property_form = true;
            },*/

            render_property_form : function (obj) {
                lego_pagebuilder.target_obj_property = obj;
                lego_pagebuilder.show_property_form = true;
            },
            cancel_property_form : function () {
                lego_pagebuilder.reset_property_pointers();
                lego_pagebuilder.show_property_form = false;
            },
            update_property : function () {
                lego_pagebuilder.lego_layout[lego_pagebuilder.target_obj_property.type][lego_pagebuilder.target_obj_property.id].property = lego_pagebuilder.target_obj_property.property;
                lego_pagebuilder.reset_property_pointers();
                lego_pagebuilder.show_property_form = false;
            },
            reset_property_pointers : function () {
                lego_pagebuilder.target_obj_property = {
                    id : '',
                    type : '',
                    property : ''
                };
            },

            /**
             *
             * @param elem_label
             * @param elem_type
             * @param elem_name
             * @param elem_instance
             * @param id_base
             * @param widgetized
             */
            render_element_form : function ( elem_label, elem_type, elem_name, elem_instance, id_base, widgetized ) {

                var editable_target_obj_property = '';

                if ( !lego_pagebuilder.target_elem.id ) {
                    lego_pagebuilder.target_elem.id = 'elem_' + new Date().getTime();
                } else {
                    editable_target_obj_property = lego_pagebuilder.lego_layout.element[lego_pagebuilder.target_elem.id].property;
                    var widgetized = lego_pagebuilder.lego_layout.element[lego_pagebuilder.target_elem.id].widgetized;
                }

                lego_pagebuilder.target_obj_property = {
                    id : lego_pagebuilder.target_elem.id,
                    type : 'element',
                    property :  editable_target_obj_property == '' ? lego_pagebuilder.default_elem_property : editable_target_obj_property
                };

                (function ($) {
                    $.post(
                        ajaxurl,
                        {
                            action : 'wpsb_grab_element_data',
                            type : elem_type,
                            name : elem_name,
                            id : lego_pagebuilder.target_elem.id,
                            instance : elem_instance,
                            id_base : id_base,
                            nonce : wpsb_obj.ajaxnonce
                        },
                        function (data) {

                            lego_pagebuilder.target_elem.label = elem_label;
                            lego_pagebuilder.target_elem.type = elem_type;
                            lego_pagebuilder.target_elem.name = elem_name;
                            lego_pagebuilder.target_elem.id_base = id_base;
                            lego_pagebuilder.target_elem.widgetized = widgetized;
                            lego_pagebuilder.target_elem.property = lego_pagebuilder.target_obj_property.property;

                            $('.widget-label').html(elem_label);
                            $('.widget-body').html(data);
                            lego_pagebuilder.show_widget_modal = true;
                        }
                    )
                }(jQuery))

            },

            /**
             * Cancel element insertion
             * into placeholder
             */
            cancel_element_insert : function () {
                lego_pagebuilder.reset_target_elem();
                lego_pagebuilder.show_widget_modal = false;
            },
            /**
             * grab preview of element
             */
            update_preview : function (elem_id) {
                /*var elem_data = lego_pagebuilder.lego_layout.element[elem_id];*/
                (function ($) {
                    $.post(
                        ajaxurl,
                        {
                            action : 'wpsb_update_preview',
                            id : elem_id,
                            type : 'widget',
                            lego_layout : lego_pagebuilder.lego_layout,
                            nonce : wpsb_obj.ajaxnonce
                        },
                        function (data) {
                            Vue.set(lego_pagebuilder.preview_data,elem_id,data);
                            lego_pagebuilder.preview_element(elem_id);
                        }
                    )
                }(jQuery))
            },

            /**
             * Element preview
             */
            preview_element : function (elem_id) {
                lego_pagebuilder.target_preview = lego_pagebuilder.preview_data[elem_id];
                lego_pagebuilder.preview_modal = true;
            },

            /** preview modal cancelled **/
            cancel_preview : function () {
                lego_pagebuilder.target_preview = false;
                lego_pagebuilder.preview_modal = false;
            },

            /**
             * add section in targeted placeholder
             * @param obj
             */
            add_section_in_placeholder : function (obj) {
                lego_pagebuilder.target_col = obj.id;
                //show custom layout input modal
                lego_pagebuilder.show_custom_layout_input = true;
            },
            /**
             * Add row to container
             */
            add_row : function ( cont_id ) {

                if( !lego_pagebuilder.target_col ) {

                    if( !lego_pagebuilder.target_cont ) {

                        var container_keys = Object.keys(lego_pagebuilder.lego_layout.container);
                        if ( !container_keys.length > 0 ) {
                            alert( 'You must add a container to add row !' );
                            return;
                        }

                        if( typeof cont_id == 'undefined' ) {
                            lego_pagebuilder.target_cont = container_keys[container_keys.length - 1];
                        } else {
                            lego_pagebuilder.target_cont = cont_id;
                        }

                    }
                }


                var row_id = lego_pagebuilder.generate_row_id();

                if( lego_pagebuilder.target_cont ) {
                    lego_pagebuilder.lego_layout.container[lego_pagebuilder.target_cont].child.row.push(row_id);
                } else {
                    // it is for a placeholder
                    lego_pagebuilder.lego_layout.col[lego_pagebuilder.target_col].child.row.push(row_id);
                }


                Vue.set(lego_pagebuilder.lego_layout.row,row_id,{
                    "child" : {
                        "col" : []
                    },
                    "property" : lego_pagebuilder.default_row_property
                });

                lego_pagebuilder.reset_all();
                return row_id;
            },
            /**
             * Add col (placeholder) to container
             */
            add_placeholder : function ( target_row, col_id, span  ) {

                if( !lego_pagebuilder.target_row ) {
                    var row_keys = Object.keys(lego_pagebuilder.lego_layout.row);
                    if ( !row_keys.length > 0 ) {
                        alert( 'You must add a row to add palceholder !' );
                        return;
                    }

                    if( typeof target_row == 'undefined' ) {
                        lego_pagebuilder.target_row = row_keys[row_keys.length - 1];
                    } else {
                        lego_pagebuilder.target_row = target_row;
                    }

                }

                if( typeof col_id == 'undefined' ) {
                    col_id = lego_pagebuilder.generate_col_id();
                }

                if( typeof span == 'undefined' ) {
                    span = lego_pagebuilder.grid_number;
                }

                lego_pagebuilder.lego_layout.row[lego_pagebuilder.target_row].child.col.push(col_id);
                Vue.set(lego_pagebuilder.lego_layout.col,col_id,{
                    "child" : {
                        "element" : [],
                        "row" : []
                    },
                    "span" : span,
                    "property" : lego_pagebuilder.default_col_property
                });

                lego_pagebuilder.reset_all();

                return col_id;
            },

            /**
             * Add col (placeholder) to container
             */
            add_element : function () {
                if( !lego_pagebuilder.target_col ) {
                    var col_keys = Object.keys(lego_pagebuilder.lego_layout.col);
                    if ( !col_keys.length > 0 ) {
                        alert( 'You must add a placeholder to add palceholder !' );
                        return;
                    }

                    lego_pagebuilder.target_col = col_keys[col_keys.length - 1];

                }

                lego_pagebuilder.show = true;
            },


            generate_row_id : function () {
                return 'row_' + new Date().getTime();
            },
            generate_col_id : function () {
                return 'col_' + new Date().getTime();
            },
            generate_elem_id : function () {
                return 'elem_' + new Date().getTime();
            },
            generate_cont_id : function () {
                return 'cont_' + new Date().getTime();
            },

            enable_lego : function () {
                lego_pagebuilder.is_lego_enabled = 'true';

                if( typeof lego_pagebuilder.lego_layout != 'object') {
                    lego_pagebuilder.lego_layout = { 'container' : {}, 'row' : {}, 'col' : {}, 'element' : {} };
                    Vue.set( lego_pagebuilder.lego_layout.container , lego_pagebuilder.generate_cont_id(), {
                        "child" : {
                            "row" : []
                        },
                        "is_fluid" : ""
                    })
                }
            },
        },

        events : {
            event_render_element_form : function (obj) {
                lego_pagebuilder.target_col = obj.parent_id;
                lego_pagebuilder.target_elem.id = obj.elem_id;
                lego_pagebuilder.render_element_form( obj.label, obj.type, obj.name, obj.instance, obj.id_base );
            },

            remove_item : function (obj) {
                var is_child_empty = 1;
                if( obj.child_type ) {
                    for( c in obj.child_type ) {
                        if( lego_pagebuilder.lego_layout[obj.type][obj.id].child[obj.child_type[c]].length ) {
                            is_child_empty = 0;
                            break;
                        }
                    }

                }

                if( is_child_empty == 0 ) {
                    alert( 'Please ! Delete or move the inner items first !' );
                    return;
                }

                if( is_child_empty ) {
                    lego_pagebuilder.lego_layout[obj.parent_type][obj.parent_id].child[obj.type].splice( lego_pagebuilder.lego_layout[obj.parent_type][obj.parent_id].child[obj.type].indexOf(obj.id), 1 );
                    Vue.delete(lego_pagebuilder.lego_layout[obj.type],obj.id);
                    $('#' + obj.id).remove();
                }


            },

            event_add_placeholder : function ( row_id ) {
                lego_pagebuilder.add_placeholder( row_id )
            },

            event_add_row : function ( cont_id ) {
                lego_pagebuilder.add_row( cont_id )
            },

            /**
             * coming from row edit button
             * @param row_id
             * @param property
             */
            event_render_property_form : function (obj) {
                lego_pagebuilder.render_property_form( obj );
            },

            event_add_section_in_placeholder : function (obj) {
                lego_pagebuilder.add_section_in_placeholder(obj);
            },

            event_preview_element : function (elem_id) {
                if ( typeof( lego_pagebuilder.preview_data[elem_id] ) == 'undefined' ) {
                    lego_pagebuilder.update_preview(elem_id);
                } else {
                    lego_pagebuilder.preview_element(elem_id);
                }
            },
            /** chosing a layout from section layout will trigger this **/
            event_make_section : function ( divisions ) {
                //lego_pagebuilder.lego_layout.row['row_' + new Date().getTime()];
                var row_id = lego_pagebuilder.add_row();
                var repeater = 1;
                var col_id = lego_pagebuilder.generate_col_id();
                for( span in divisions ) {
                    col_id = lego_pagebuilder.add_placeholder( row_id, col_id, divisions[span] );
                    col_id = col_id + ( ++repeater ) ;
                }
            },

            event_bring_custom_layout : function ( show_custom_layout_modal ) {
                lego_pagebuilder.show_custom_layout_input = show_custom_layout_modal;
            },

        },

        ready : function () {

            if( typeof this.lego_layout.row == 'object' && !Object.keys(this.lego_layout.row).length ) {
                this.lego_layout.row = {};
            }
            if( typeof this.lego_layout.col == 'object' && !Object.keys(this.lego_layout.col).length ) {
                this.lego_layout.col = {};
            }
            if(  typeof this.lego_layout.element == 'object' && !Object.keys(this.lego_layout.element).length ) {
                this.lego_layout.element = {};
            }
        }

    });
    lego_obj.init();
}(jQuery));

(function($){
    $(document).ready(function () {

        $(document).on('click','button',function (e) {
            e.preventDefault();
        });
    })
}(jQuery));