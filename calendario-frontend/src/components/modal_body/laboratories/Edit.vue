<template>
    <div class="instructor-modal">
        <h1 class="font-black uppercase text-2xl text-center pb-4">Editar laboratorio</h1>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Nombre:</span> </label>
            <input @keyup="changeIsValid" autocomplete="off" spellcheck="false" v-model="current_name" type="text" placeholder="Nombre del instructor" class="input input-bordered w-full">
        </div>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Descripción:</span> </label>
            <input @keyup="changeIsValid" autocomplete="off" spellcheck="false" v-model="current_description" type="text" placeholder="Descripción del laboratorio" class="input input-bordered w-full">
        </div>

        <div class="mt-2 flex justify-end">
            <button v-if="change_is_valid" @click="save" class="btn mr-4">Guardar</button>
            <button v-else disabled class="btn mr-4">Guardar</button>
            <button @click="close" class="btn">Cancelar</button>
        </div>
    </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
import ILaboratories from "@/services/api/interfaces/ILaboratories";
import APIServices from "@/services/api/APIServices";

@Component({})
export default class Edit extends Vue {
    @Prop({ required: true}) laboratory!: ILaboratories
    close() { this.$emit("close") }

    current_name: string = ""
    current_description: string = ""
    change_is_valid: boolean = false

    mounted() {
        this.current_name = this.laboratory.name
        this.current_description = this.laboratory.description
    }

    changeIsValid() {
        if ((this.current_name + "").length < 1 || (this.current_description + "").length < 1) this.change_is_valid = false
        this.change_is_valid = !(this.laboratory.name == this.current_name && this.laboratory.description == this.current_description);
    }

    async save() {
        let data: ILaboratories = {
            id: this.laboratory.id,
            name: this.current_name,
            description: this.current_description
        }

        await APIServices.UpdateLaboratory(data).then((result: string | null) => {
            if (!result) this.$store.state.alert = { type: "error",  show: true,  message: "Error al actualizar: " + this.laboratory.name }
            this.$store.state.alert = { type: "success",  show: true,  message: "Se actualizo el laboratorio: " + this.laboratory.name }

            let laboratories = this.$store.state.laboratories
            this.$store.state.laboratories = laboratories.filter((item: ILaboratories) => item.id != data.id)
            this.$store.state.laboratories.push(data)
        })

        this.close()
    }
}
</script>
