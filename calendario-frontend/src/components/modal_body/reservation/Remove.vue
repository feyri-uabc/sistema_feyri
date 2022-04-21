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

@Component({})
export default class Remove extends Vue {
    @Prop({ required: true}) item_html!: HTMLElement
    current_reservation!: IReservations
    unique_remove: boolean = true
    get reservations_group() {
        return this.$store.state.reservations.filter((item: IReservations) => item.id)
    }
    get reservation_date() {
        return this.item_html.id.slice(1)
            .split("_").reverse().join("_").replace("_", "/")
            .split("/")[1].split("_").join("/")
    }
    get reservation_hour() {
        return this.item_html.id.split("_").reverse()[0] + ":00"
    }

    beforeMount() {
        let reservation_id: number = parseInt(`${this.item_html.children[0].id}`)
        this.current_reservation = this.$store.state.reservations.filter((item: IReservations) => item.id == reservation_id)[0]
    }

    async remove() {
        if (!this.current_reservation.id) return this.$store.state.alert = {
            type: "error", show: true,
            message: "Error al eliminar la reservacion: " + this.current_reservation.id
        }

        if (this.unique_remove) await this.removeId(this.current_reservation.id)
    }

    async removeId(id: number): Promise<void> {
        await APIServices.DeleteReservation(id).then((result: string | null) => {
            if (!result) {
                this.$store.state.alert = { type: "error",  show: true,  message: "Error al eliminar la reservacion: " + id }
                this.close()
            }
            this.item_html.innerHTML = ""
            this.$store.state.reservations = this.$store.state.reservations.filter((item: IReservations) => item.id != id)
            this.$store.state.alert = { type: "error",  show: true,  message: "Reservacion eliminada: " + id }
        })
        this.close()
    }

    async removeGroupId(id: number) {
        await APIServices.DeleteReservation(id).then((result: string | null) => {
            if (!result) {
                this.$store.state.alert = { type: "error",  show: true,  message: "Error al eliminar la reservacion: " + id }
                this.close()
            }
            this.item_html.innerHTML = ""
            this.$store.state.reservations = this.$store.state.reservations.filter((item: IReservations) => item.id != id)
            this.$store.state.alert = { type: "error",  show: true,  message: "Reservacion eliminada: " + id }
        })
        this.close()
    }

    close() {
        this.$emit("close")
    }
}
</script>
