/**modal**/
Vue.component('lego_element_modal',{
    template : '#widget-modal',
    props : ['show_element_modal']
});
/** widget list accordion**/
Vue.component('widget_list',{
    template : '#widget-list-accordion-template',
    data : function () {
        return {
            accordion_id : 'accord_' + new Date().getTime()
        }
    },
    ready : function(){
        var acc = document.querySelectorAll("#" + this.accordion_id + " > div[slot='acc-panel'] > button");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].onclick = function(){
                this.classList.toggle("active");
                this.nextElementSibling.classList.toggle("show");
            }
        }
    }
});
/** element attributes tabs**/
Vue.component('property_modal',{
    template : '#property-modal-template',
    props : ['target_property' ],
    data : function () {
        return {
            tab_id : 'tab_' + new Date().getTime(),
            all_property : {
                attribute : {
                    class : {
                        label : 'Class',
                        type : 'text'
                    }
                },
                style : {
                    style : {
                        label : 'Style',
                        type : 'textarea'
                    }
                }
            },
        }
    },
    computed : {
        active_properties : function () {
            var array = {};
            for( property_name in this.target_property ) {
                array[property_name] = Object.keys(this.target_property[property_name]);
            }
            return array;
        },
        active_property_names : function () {
            return Object.keys(this.target_property);
        }
    },
    methods: {
        open_tab : function( i ){

            var tabcontent = document.querySelectorAll("#"+ this.tab_id +" > .tab-data > div[slot='tabdata'] > div");
            var tabs = document.querySelectorAll("#"+ this.tab_id + " > ul.tab > li");

            if( !tabcontent.length ) {
                tabcontent = document.querySelectorAll("#"+ this.tab_id +" > .tab-data > div");
            }
            for (r = 0; r < tabcontent.length; r++) {
                if( r == i ) {
                    tabcontent[r].style.display = "block";
                    tabs[r].childNodes[0].setAttribute('class','active');
                } else {
                    tabcontent[r].style.display = "none";
                    tabs[r].childNodes[0].removeAttribute('class','active');
                }
            }

        }
    },
    ready : function () {
    }
});
/** section component **/
Vue.component('section_layout',{
    template : '#wpsb-section-layout',
    props : [ 'show_section_layout', 'total_spans' ],
    methods : {
        dispatch_make_section : function ( divisions ) {
            this.$dispatch( 'event_make_section', divisions );
            this.show_section_layout = false;
        },
        dispatch_bring_custom_layout : function () {
            this.$dispatch( 'event_bring_custom_layout', 'true' );
            this.show_section_layout = false;
        }
    },
    ready : function () {

    }
});
/** custom section layout **/
Vue.component('wpsb_custom_layout_input',{
    template : '#wpsb_custom_layout_input',
    props : ['maxspan'],
    data : function () {
        return {
            placeholder_nums : 1,
            span_values : [1]
        }
    },
    methods : {
        build_array : function () {
            this.span_values = [];
            for( var i = 1; i <= this.placeholder_nums; i++ ) {
                this.span_values.push(i);
            }
        },
        clear_data : function () {
            this.placeholder_nums = 1;
            this.span_values = [1];
        },
        dispatch_make_section : function ( divisions ) {
            this.$dispatch( 'event_make_section', divisions );
            this.clear_data();
            this.dispatch_cancel_modal();
        },
        dispatch_cancel_modal : function () {
            this.clear_data();
            this.$dispatch( 'hide_custom_layout_modal', 'false' ); //to modal component
        }
    }
});
Vue.component('wpsb_preview_modal',{
    template : '#wpsb_preview_modal',
    props : ['show','default_button'],
});

/**notice modal**/
Vue.component('notice_modal',{
    template : '#notice_modal',
    props : ['show','default_button','disappear'],
    events : {
        hide_custom_layout_modal : function () {
            this.show = false;
        }
    },
    methods : {
        modal_disappear : function () {
            var temp = this;
            if( this.disappear == 'auto' ) {
                setTimeout(function () {
                    temp.show = false;
                },1000);
            }
        }
    }
});