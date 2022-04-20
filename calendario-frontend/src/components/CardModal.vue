<template>
    <div v-if="toggle" class="w-full h-full modal-popup">
        <div class="overflow-y-auto h-full flex">
            <div class="border border-current card card-contain my-auto bg-base-100">
                <h2 class="card-title">
                    <slot class="m-4" name="title"/>
                </h2>
                <div class="card-body card-body-reset">
                    <slot name="body"/>
                </div>
                <div class="card-actions justify-end">
                    <slot class="pt-6" name="actions"/>
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
    width: 30rem;
    min-height: 8rem;
    padding: 2rem;
}
</style>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";

@Component({})
export default class Calendario extends Vue {
    @Prop({required: true}) toggle!: boolean

    mounted() {
        window.onkeyup = (e: any) => this.closeModal(e.keyCode)
    }

    closeModal(keyCode: number) {
        if (keyCode != 27) return
        this.$emit("escape")
    }
}
</script>
