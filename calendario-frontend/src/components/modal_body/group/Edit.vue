<template>
    <div class="group-modal">
        <h1 class="font-black uppercase text-2xl text-center pb-4">Editar grupo</h1>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Nombre:</span> </label>
            <input @keyup="changeIsValid" autocomplete="off" spellcheck="false" v-model="current_name" type="text" placeholder="Nombre del group" class="input input-bordered w-full">
        </div>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Descripción:</span> </label>
            <input @keyup="changeIsValid" autocomplete="off" spellcheck="false" v-model="current_description" type="text" placeholder="Ej. Código del grupo" class="input input-bordered w-full">
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
import IGroups from "@/services/api/interfaces/Groups";
import APIServices from "@/services/api/APIServices";

@Component({})
export default class Edit extends Vue {
    @Prop({ required: true}) group!: IGroups
    close() { this.$emit("close") }

    current_name: string = ""
    current_description: string = ""
    change_is_valid: boolean = false

    mounted() {
        this.current_name = this.group.name
        this.current_description = this.group.description
    }

    changeIsValid() {
        if ((this.current_name + "").length < 1 || (this.current_description + "").length < 1) this.change_is_valid = false
        this.change_is_valid = !(this.group.name == this.current_name && this.group.description == this.current_description);
    }

    async save() {
        let data: IGroups = {
            id: this.group.id,
            name: this.current_name,
            description: this.current_description
        }

        await APIServices.UpdateGroups(data).then((result: string | null) => {
            if (!result) this.$store.state.alert = { type: "error",  show: true,  message: "Error al actualizar: " + this.group.name }
            this.$store.state.alert = { type: "success",  show: true,  message: "Se actualizo el grupo: " + this.group.name }

            let groups = this.$store.state.groups
            this.$store.state.groups = groups.filter((item: IGroups) => item.id != data.id)
            this.$store.state.groups.push(data)
        })

        this.close()
    }
}
</script>
