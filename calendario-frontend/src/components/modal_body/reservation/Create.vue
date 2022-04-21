<template>
    <div class="labs-modal">
        <h1 class="font-black uppercase text-2xl text-center pb-4">Crear reservacion</h1>
        {{item_html}}
        <div class="mt-2 flex justify-end">
            <button @click="close" class="btn">Cancelar</button>
        </div>
    </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";

@Component({})
export default class Create extends Vue {
    @Prop({ required: true}) item_html!: HTMLElement
    close() { this.$emit("close") }
}
</script>
