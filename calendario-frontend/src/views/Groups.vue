<template>
    <section class="section-labs">
        <div class="mx-2 sm:mx-10 mb-20">
            <h1 class="text-center mt-2 mb-10 uppercase font-black text-2xl">Listado de grupos</h1>

            <div class="overflow-x-auto">
                <h1 v-if="!$store.state.groups[0]" class="text-center text-gray-500 mt-2 mb-10 uppercase font-black text-2xl">sin registros</h1>
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
                    <tr v-for="group in groups">
                        <th>{{ group.id }}</th>
                        <td>{{ group.name }}</td>
                        <td>{{ group.description }}</td>
                        <td class="justify-center flex print:hidden">
                            <button class="btn mx-2 btn-ghost hover:text-violet-500 btn-xs" @click="edit(group)">
                                Editar
                            </button>
                            <button class="btn mx-2 btn-ghost hover:text-red-400 btn-xs" @click="remove(group)">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-5 print:hidden">
                <button class="btn" @click="openModal('create')">registrar grupo</button>
            </div>
        </div>

        <CardModal :toggle="open_modal" v-on:escape="closeModal">
            <template v-slot:body>
                <Create v-if="modalType==='create'" v-on:close="closeModal"/>
                <Edit v-if="modalType==='edit'" :group="current_group" v-on:close="closeModal"/>
                <Remove v-if="modalType==='remove'" :group="current_group" v-on:close="closeModal"/>
            </template>
        </CardModal>
    </section>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator';
import IGroups from "@/services/api/interfaces/Groups";
import CardModal from "@/components/CardModal.vue";
import Create from "@/components/modal_body/group/Create.vue";
import Remove from "@/components/modal_body/group/Remove.vue";
import Edit from "@/components/modal_body/group/Edit.vue";

@Component({
    components: {CardModal, Create, Remove, Edit}
})
export default class Groups extends Vue {
    current_group: IGroups | null = null
    open_modal: boolean = false
    modalType: string | null = null // edit, remove

    get groups() {
        return this.$store.state.groups
    }

    openModal(type: string) {
        this.open_modal = true
        this.modalType = type
    }

    closeModal() {
        this.open_modal = false
        this.modalType = null
        this.current_group = null
    }

    edit(course: IGroups) {
        this.openModal("edit")
        this.current_group = course
    }

    remove(course: IGroups) {
        this.openModal("remove")
        this.current_group = course
    }
}
</script>
