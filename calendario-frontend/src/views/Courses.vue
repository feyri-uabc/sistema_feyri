<template>
    <section class="section-labs">
        <div class="mx-2 sm:mx-10 mb-20">
            <h1 class="text-center mt-2 mb-10 uppercase font-black text-2xl">Listado de cursos</h1>

            <div class="overflow-x-auto">
                <h1 v-if="!$store.state.courses[0]" class="text-center text-gray-500 mt-2 mb-10 uppercase font-black text-2xl">sin registros</h1>
                <table v-else class="table table-zebra w-full">
                    <thead>
                    <tr>
                        <th/>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th class="text-center print:hidden">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="course in $store.state.courses">
                        <th>{{ course.id }}</th>
                        <td>{{ course.name }}</td>
                        <td>{{ course.description }}</td>
                        <td class="justify-center flex print:hidden">
                            <button class="btn mx-2 btn-ghost hover:text-violet-500 btn-xs" @click="edit(course)">
                                Editar
                            </button>
                            <button class="btn mx-2 btn-ghost hover:text-red-400 btn-xs" @click="remove(course)">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-5 print:hidden">
                <button class="btn" @click="openModal('create')">registrar materia</button>
            </div>
        </div>

        <CardModal :toggle="open_modal" v-on:escape="closeModal">
            <template v-slot:body>
                <Create v-if="modalType==='create'" v-on:close="closeModal"/>
                <Remove v-if="modalType==='remove'" :laboratory="current_course" v-on:close="closeModal"/>
            </template>
        </CardModal>
    </section>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator';
import ICourses from "@/services/api/interfaces/Courses";
import CardModal from "@/components/CardModal.vue";
import Create from "@/components/modal_body/course/Create.vue";
import Remove from "@/components/modal_body/course/Remove.vue";

@Component({
    components: {CardModal, Create, Remove}
})
export default class Courses extends Vue {
    current_course: ICourses | null = null
    open_modal: boolean = false
    modalType: string | null = null // edit, remove

    openModal(type: string) {
        this.open_modal = true
        this.modalType = type
    }

    closeModal() {
        this.open_modal = false
        this.modalType = null
        this.current_course = null
    }

    edit(course: ICourses) {
        this.openModal("edit")
        this.current_course = course
    }

    remove(course: ICourses) {
        this.openModal("remove")
        this.current_course = course
    }
}
</script>
