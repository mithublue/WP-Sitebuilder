<template id="tabs-template">
    <div id="{{ tab_id }}">
        <ul class="tab">
            <li v-for="( i, tabname ) in tabnames"><a href="javascript:" @click="open_tab(i)">{{ tabname }}</a></li>
        </ul>

        <div class="tab-data">
            <slot name="tabdata">
                <div>
                    <h3>London</h3>
                    <p>London is the capital city of England.</p>
                </div>
            </slot>
        </div>
    </div>
</template>