<template>
    <div class="labs-modal">
        <h1 class="font-black uppercase text-2xl text-center pb-4">eliminar reservacion</h1>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Reservacion ID:</span> </label>
            <input disabled :value="current_reservation.id" type="text" class="input input-bordered w-full">
        </div>

        <div class="form-control w-full pb-4">
            <label class="label"> <span class="label-text">Tipo:</span> </label>
            <input disabled :value="current_reservation.tipo" type="text" class="input input-bordered w-full">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Fecha:</span> </label>
                <input disabled :value="reservation_date" type="text" class="input input-bordered w-full">
            </div>
            <div class="form-control w-full pb-4">
                <label class="label"> <span class="label-text">Hora:</span> </label>
                <input disabled :value="reservation_hour" type="text" class="input input-bordered w-full">
            </div>
        </div>

        <div class="form-control">
            <label class="label cursor-pointer">
                <span class="label-text">Eliminar solo esta reservacion</span>
                <input v-model="unique_remove" type="checkbox" checked="checked" class="checkbox checkbox-primary">
            </label>
        </div>

        <div v-if="!unique_remove">
            <div v-for="reservation in reservations_group">
                {{reservation.id}}
            </div>
        </div>

        <div class="mt-2 flex justify-end">
            <button @click="remove" class="btn mr-5">Aceptar</button>
            <button @click="close" class="btn">Cancelar</button>
        </div>
    </div>
</template>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
import IReservations from "@/services/api/interfaces/IReservations";
import APIServices from "@/services/api/APIServices";
import IGroups from "@/services/api/interfaces/Groups";

@Component({})
export default class Remove extends Vue {
    @Prop({ required: true}) group!: IGroups
    close() {
        this.$emit("close")
    }

    async remove() {
        if (this.group.id) APIServices.DeleteGroup(this.group.id)
            .then(result => {
                if (result == null) return
                this.$store.state.alert = { type: "error",  show: true,  message: "Se elimino al laboratorio: " + this.group.name }
                let groups = this.$store.state.groups
                this.$store.state.groups = groups.filter((item: IGroups) => item.id != this.group.id)
            })
        this.close()
    }
}
</script>
