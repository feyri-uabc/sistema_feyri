<template>
    <div class="w-full h-full modal-popup" v-if="toggle">
        <div class="overflow-y-auto h-full flex">
            <div class="card card-contain my-auto bg-base-100">
                <h2 class="card-title m-4">
                    <slot name="title"/>
                </h2>
                <div class="card-body card-body-reset">
                    <slot name="body"/>
                </div>
                <div class="card-actions justify-end pt-6">
                    <slot name="actions"/>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.modal-popup {
    top: 0;
    z-index: 99999999;
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    overflow: hidden;
    backdrop-filter: blur(15px);
}

.modal-popup::before {
    content: "";
    position: absolute;
    z-index: -1;
    background-color: rgba(0, 0, 0, 0.05);
    width: 100%;
    height: 100%;
}

.card-body-reset {
    padding: 0 !important;
}

.card-contain {
    min-width: 15rem;
    min-height: 8rem;
    padding: 2rem;
}
</style>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";

@Component({})
export default class Calendario extends Vue {
    @Prop({required: true}) toggle!: boolean
}
</script>
