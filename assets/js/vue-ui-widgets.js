/**accordion**/
Vue.component('accordion',{
    template : '#accordion-template',
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
/**alert**/
Vue.component('alert',{
    template : '#alert-template',
    props : ['type'],
    methods : {
        removeAlert : function(e) {
            e.target.parentElement.remove();
        }
    }
});
/**card**/
Vue.component('card',{
    template : '#card_template',
    props : ['size'],
    data : function () {
        return {
            "card_id" : "card_" + new Date().getTime()
        }
    }
});
/**chip**/
Vue.component('chip',{
    "template" : "#chip-template",
    "props" : ["removable"],
    methods : {
        removeChip : function (e) {
            e.target.parentElement.remove();
        }
    }
});
/**modal**/
Vue.component('modal',{
    template : '#modal_template',
    props : ['show','default_button'],
    events : {
        hide_custom_layout_modal : function () {
            this.show = false;
        }
    }
});
/**notice**/
Vue.component('notice',{
    template : '#notice-template',
    props : ['type']
});
/**tabs**/
Vue.component('tabs',{
    template : '#tabs-template',
    props : ['tabnames'],
    data : function () {
        return {
            'tab_id' : 'tab_' + new Date().getTime()
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
        document.querySelector("#"+ this.tab_id + " > ul.tab > li > a").setAttribute('class','active');
        document.querySelector("#"+ this.tab_id +" > .tab-data > div[slot='tabdata'] > div").style.display = "block";
    }
});