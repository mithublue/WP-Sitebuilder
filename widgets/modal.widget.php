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
                    <button class="modal-default-button"
                            @click="show = false">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
</template>