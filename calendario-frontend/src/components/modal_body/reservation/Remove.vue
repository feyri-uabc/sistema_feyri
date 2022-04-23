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

        <div class="reservations-remove my-8" v-if="!unique_remove">
            <div class="grid grid-cols-3">
                <span class="text-center uppercase font-bold">ID</span>
                <span class="text-center uppercase font-bold">Fecha</span>
                <span class="text-center uppercase font-bold">Hora</span>
            </div>
            <div class="grid grid-cols-3" v-for="reservation in reservations_group">
                <span class="text-center">{{reservation.id}}</span>
                <span class="text-center">{{f2d(reservation.select_day)}}/{{f2d(reservation.select_month)}}/{{reservation.select_year}}</span>
                <span class="text-center">{{f2d(reservation.select_hour)}}:00</span>
            </div>
        </div>

        <div class="mt-2 flex justify-end">
            <button @click="remove" class="btn mr-5">Aceptar</button>
            <button @click="close" class="btn">Cancelar</button>
        </div>
    </div>
</template>

<style>
.reservations-remove {
    overflow-y: auto;
    max-height: 6rem;
}
</style>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
import IReservations from "@/services/api/interfaces/IReservations";
import APIServices from "@/services/api/APIServices";
import {GetCalendarFieldId} from "@/components/Calendar.vue";

@Component({})
export default class Remove extends Vue {
    @Prop({ required: true}) item_html!: HTMLElement
    current_reservation!: IReservations
    unique_remove: boolean = true
    get reservations_group(): Array<IReservations> {
        let grouping = this.current_reservation.grouping
        return this.$store.state.reservations.filter((item: IReservations) => item.grouping === grouping)
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
    /**
     * Create 24Hours format (input: 5, output: 05:00)
     * @param num Range 1 - 24
     */
    f2d(num: number): string {
        return (num > 9) ?num+"" :"0"+num
    }

    async remove() {
        if (!this.current_reservation.id) return this.$store.state.alert = {
            type: "error", show: true,
            message: "Error al eliminar la reservacion: " + this.current_reservation.id
        }
        if (this.unique_remove) await this.removeId(this.current_reservation.id, this.item_html)
        else {
            for (let reservation of this.reservations_group) {
                let item = GetCalendarFieldId(reservation.select_year, reservation.select_month, reservation.select_day, reservation.select_hour)
                if (item) if (reservation && reservation.id) await this.removeId(reservation.id, item)
            }
        }
        this.close()
    }

    async removeId(id: number, item: HTMLElement): Promise<void> {
        await APIServices.DeleteReservation(id).then((result: string | null) => {
            if (!result) {
                return this.$store.state.alert = { type: "error",  show: true,  message: "Error al eliminar la reservacion: " + id }
            }
            this.$store.state.reservations = this.$store.state.reservations.filter((item: IReservations) => item.id !== id)
            item.innerHTML = ""
            this.$store.state.alert = { type: "error",  show: true,  message: "Reservacion eliminada: " + id }
        })
    }

    close() {
        this.$emit("close")
    }
}
</script>
