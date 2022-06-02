<template>
    <div class="instructor-modal">
        <h1 class="font-black uppercase text-2xl text-center pb-4">Editar instructor</h1>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Nombre:</span> </label>
            <input @keyup="changeIsValid" autocomplete="off" spellcheck="false" v-model="current_name" type="text" placeholder="Nombre del instructor" class="input input-bordered w-full">
        </div>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Contacto:</span> </label>
            <input @keyup="changeIsValid" autocomplete="off" spellcheck="false" v-model="current_contact" type="text" placeholder="No. Telefónico, dirección o correo-e" class="input input-bordered w-full">
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

@Component({})
export default class Edit extends Vue {
    @Prop({ required: true}) instructor!: IInstructors
    close() { this.$emit("close") }

    current_name: string = ""
    current_contact: string = ""
    change_is_valid: boolean = false

    mounted() {
        this.current_name = this.instructor.name
        this.current_contact = this.instructor.contact
    }

    changeIsValid() {
        if ((this.current_name + "").length < 1 || (this.current_contact + "").length < 1) this.change_is_valid = false
        this.change_is_valid = !(this.instructor.name == this.current_name && this.instructor.contact == this.current_contact);
    }

    async save() {
        let data: IInstructors = {
            id: this.instructor.id,
            name: this.current_name,
            contact: this.current_contact
        }

        await APIServices.UpdateInstructor(data).then((result: string | null) => {
            if (!result) this.$store.state.alert = { type: "error",  show: true,  message: "Error al actualizar: " + this.instructor.name }
            this.$store.state.alert = { type: "success",  show: true,  message: "Se actualizo el instructor: " + this.instructor.name }

            let instructors = this.$store.state.instructors
            this.$store.state.instructors = instructors.filter((item: IInstructors) => item.id != data.id)
            this.$store.state.instructors.push(data)
        })

        this.close()
    }
}
</script>
