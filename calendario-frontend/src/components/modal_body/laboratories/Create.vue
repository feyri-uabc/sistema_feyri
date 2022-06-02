<template>
    <div class="labs-modal">
        <h1 class="font-black uppercase text-2xl text-center pb-4">registrar laboratorio</h1>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Nombre:</span> </label>
            <input @keyup="changeIsValid" autocomplete="off" spellcheck="false" v-model="current_name" type="text" placeholder="Nombre del laboratorio" class="input input-bordered w-full">
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
import IInstructors from "@/services/api/interfaces/IInstructors";
import APIServices from "@/services/api/APIServices";
import ILaboratories from "@/services/api/interfaces/ILaboratories";

@Component({})
export default class Create extends Vue {
    close() { this.$emit("close") }

    current_name: string = ""
    current_description: string = ""
    change_is_valid: boolean = false

    changeIsValid() {
        this.change_is_valid = (this.current_name + "").length > 1 && (this.current_description + "").length > 1
    }

    async save() {
        let laboratory: ILaboratories = {
            name: this.current_name,
            description: this.current_description
        }
        await APIServices.CreateLaboratory(laboratory).then((result: ILaboratories | null) => {
            if (result == null) return this.$store.state.alert = { type: "error",  show: true,  message: "Error al registrar el lab: " + this.current_name }
            this.$store.state.alert = { type: "success",  show: true,  message: "Nuevo laboratorio: " + this.current_name }
            this.$store.state.laboratories.push(result)
            this.$emit("save")
        })
        this.close()
    }
}
</script>
