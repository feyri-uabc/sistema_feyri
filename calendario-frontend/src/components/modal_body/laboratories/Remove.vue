<template>
    <div class="instructor-modal">
        <div class="instructor-modal">
            <h1 class="font-black uppercase text-2xl text-center pb-4">Eliminar laboratorio</h1>

            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Nombre</span> </label>
                <input disabled v-model="laboratory.name" type="text" placeholder="Nombre del laboratorio"  class="input input-bordered w-full">
            </div>

            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Descripcion</span> </label>
                <input disabled v-model="laboratory.description" type="text" placeholder="Descripcion del laboratorio" class="input input-bordered w-full">
            </div>

            <div class="mt-2 flex justify-end">
                <button @click="remove" class="btn mr-4 bg-red-500">Eliminar</button>
                <button @click="close" class="btn">Cancelar</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
import APIServices from "@/services/api/APIServices";
import ILaboratories from "@/services/api/interfaces/ILaboratories";

@Component({})
export default class Remove extends Vue {
    @Prop({ required: true}) laboratory!: ILaboratories

    close() {
        this.$emit("close")
    }

    async remove() {
        if (this.laboratory.id) APIServices.DeleteLaboratory(this.laboratory.id)
            .then(result => {
                if (result == null) return
                this.$store.state.alert = { type: "error",  show: true,  message: "Se elimino al laboratorio: " + this.laboratory.name }
                let laboratories = this.$store.state.laboratories
                this.$store.state.laboratories = laboratories.filter((item: ILaboratories) => item.id != this.laboratory.id)
            })
        this.close()
    }
}
</script>
