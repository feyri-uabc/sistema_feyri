<template>
    <section class="section-instructors">
        <div class="mx-2 sm:mx-10 mb-20">
            <h1 class="text-center mt-2 mb-10 uppercase font-black text-2xl">Listado de Instructores</h1>

            <div class="overflow-x-auto">
                <h1 v-if="!$store.state.instructors[0]" class="text-center text-gray-500 mt-2 mb-10 uppercase font-black text-2xl">sin registros</h1>
                <table v-else class="table table-zebra w-full">
                    <thead>
                    <tr>
                        <th/>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th class="text-center print:hidden">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="instructor in $store.state.instructors">
                        <th>{{ instructor.id }}</th>
                        <td>{{ instructor.name }}</td>
                        <td>{{ instructor.contact }}</td>
                        <td class="justify-center flex print:hidden">
                            <button class="btn mx-2 btn-ghost hover:text-violet-500 btn-xs" @click="edit(instructor)">
                                Editar
                            </button>
                            <button class="btn mx-2 btn-ghost hover:text-red-400 btn-xs" @click="remove(instructor)">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-5 print:hidden">
                <button class="btn" @click="openModal('create')">registrar instructor</button>
            </div>
        </div>

        <CardModal :toggle="open_modal" v-on:escape="closeModal">
            <template v-slot:body>
                <Create v-if="modalType==='create'" v-on:close="closeModal"/>
                <Edit v-if="modalType==='edit'" :instructor="current_instructor" v-on:close="closeModal"/>
                <Remove v-if="modalType==='remove'" :instructor="current_instructor" v-on:close="closeModal"/>
            </template>
        </CardModal>
    </section>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator';
import CardModal from "@/components/CardModal.vue";
import IInstructors from "@/services/api/interfaces/IInstructors";
import Create from "@/components/modal_body/instructor/Create.vue";
import Remove from "@/components/modal_body/instructor/Remove.vue";
import Edit from "@/components/modal_body/instructor/Edit.vue";

@Component({
    components: {Create, Remove, Edit, CardModal}
})
export default class Instructors extends Vue {
    current_instructor: IInstructors | null = null
    open_modal: boolean = false
    modalType: string | null = null // edit, remove

    openModal(type: string) {
        this.open_modal = true
        this.modalType = type
    }

    closeModal() {
        this.open_modal = false
        this.modalType = null
        this.current_instructor = null
    }

    edit(instructor: IInstructors) {
        this.openModal("edit")
        this.current_instructor = instructor
    }

    remove(instructor: IInstructors) {
        this.openModal("remove")
        this.current_instructor = instructor
    }
}
</script>
