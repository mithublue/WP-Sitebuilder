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