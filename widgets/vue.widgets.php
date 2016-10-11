<template id="accordion-template">
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
<template id="alert-template">
    <div class="alert {{ type }}">
        <span class="alert-closebtn" @click="removeAlert">&times;</span>
        <slot name="alert-data"></slot>
    </div>
</template>
<template id="card_template">
    <div class="card" id="{{ card_id }}" style="width: {{ size }}px;">
        <div class="card-header">
            <slot name="header"></slot>
        </div>
        <div class="card-content">
            <slot name="content"></slot>
        </div>
        <div class="card-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>
<template id="chip-template">
    <div class="chip">
        <slot name="chip-data"></slot>
        <span v-if="removable == true" class="chip-closebtn" @click="removeChip">&times;</span>
    </div>
</template>
<template type="text/template" id="modal_template">
    <div class="modal-mask" v-show="show" transition="modal">
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
<template id="notice-template">
    <div class="vue-{{ type }}">
        <slot name="notice-data"></slot>
    </div>
</template>
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