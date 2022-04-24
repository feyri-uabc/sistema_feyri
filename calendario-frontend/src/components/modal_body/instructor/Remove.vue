<template>
    <div class="instructor-modal">
        <div class="instructor-modal">
            <h1 class="font-black uppercase text-2xl text-center pb-4">Eliminar instructor</h1>

            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Nombre</span> </label>
                <input disabled v-model="instructor.name" type="text" placeholder="Nombre del instructor"  class="input input-bordered w-full">
            </div>

            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Contacto</span> </label>
                <input disabled v-model="instructor.contact" type="text" placeholder="No. Telefonico, direccion o correo-e" class="input input-bordered w-full">
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
import IInstructors from "@/services/api/interfaces/IInstructors";
import APIServices from "@/services/api/APIServices";

@Component({})
export default class Remove extends Vue {
    @Prop({ required: true}) instructor!: IInstructors

    close() {
        this.$emit("close")
    }

    async remove() {
        if (this.instructor.id) await APIServices.DeleteInstructor(this.instructor.id)
            .then(result => {
                if (result == null) return
                this.$store.state.alert = { type: "error",  show: true,  message: "Se elimino al instructor: " + this.instructor.name }
                let instructors = this.$store.state.instructors
                this.$store.state.instructors = instructors.filter((item: IInstructors) => item.id != this.instructor.id)
            })
        this.close()
    }
}
</script>
