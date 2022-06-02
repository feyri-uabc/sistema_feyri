<template>
    <div class="group-modal">
        <div class="instructor-modal">
            <h1 class="font-black uppercase text-2xl text-center pb-4">Eliminar grupo</h1>

            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Nombre:</span> </label>
                <input disabled v-model="group.name" type="text" placeholder="Nombre del instructor"  class="input input-bordered w-full">
            </div>

            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Descripción:</span> </label>
                <input disabled v-model="group.description" type="text" placeholder="Ej. Código del grupo" class="input input-bordered w-full">
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
import Igroups from "@/services/api/interfaces/groups";
import IInstructors from "@/services/api/interfaces/IInstructors";

@Component({})
export default class Remove extends Vue {
    @Prop({ required: true}) group!: Igroups

    close() {
        this.$emit("close")
    }

    async remove() {
        if (this.group.id) await APIServices.DeleteGroup(this.group.id)
            .then(result => {
                if (result == null) return
                this.$store.state.alert = { type: "error",  show: true,  message: "Se elimino al instructor: " + this.group.name }
                let groups = this.$store.state.groups
                this.$store.state.groups = groups.filter((item: IInstructors) => item.id != this.group.id)
            })
        this.close()
    }

}
</script>
