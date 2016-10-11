<template id="chip-template">
    <div class="chip">
        <slot name="chip-data"></slot>
        <span v-if="removable == true" class="chip-closebtn" @click="removeChip">&times;</span>
    </div>
</template>