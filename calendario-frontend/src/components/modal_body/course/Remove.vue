<template>
    <div class="course-modal">
        <div class="instructor-modal">
            <h1 class="font-black uppercase text-2xl text-center pb-4">Eliminar materia</h1>

            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Nombre:</span> </label>
                <input disabled v-model="course.name" type="text" placeholder="Nombre del instructor"  class="input input-bordered w-full">
            </div>

            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Descripción:</span> </label>
                <input disabled v-model="course.description" type="text" placeholder="Ej. Código del curso" class="input input-bordered w-full">
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
import ICourses from "@/services/api/interfaces/Courses";
import IInstructors from "@/services/api/interfaces/IInstructors";

@Component({})
export default class Remove extends Vue {
    @Prop({ required: true}) course!: ICourses

    close() {
        this.$emit("close")
    }

    async remove() {
        if (this.course.id) await APIServices.DeleteCourse(this.course.id)
            .then(result => {
                if (result == null) return
                this.$store.state.alert = { type: "error",  show: true,  message: "Se elimino al instructor: " + this.course.name }
                let courses = this.$store.state.courses
                this.$store.state.courses = courses.filter((item: IInstructors) => item.id != this.course.id)
            })
        this.close()
    }

}
</script>
