<template id="alert-template">
    <div class="alert {{ type }}">
        <span class="alert-closebtn" @click="removeAlert">&times;</span>
        <slot name="alert-data"></slot>
    </div>
</template>