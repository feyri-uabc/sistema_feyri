<template>
  <div class="home">
      <div class="mx-2 sm:mx-10 mb-20">
          <Calendario :update-calendar.sync="updateCalendar" start_time="6" end_time="18"/>
      </div>

      <div v-if="show_alert_popup" class="alert_popup w-full h-full flex">
          <div class="card bg-base-100 m-auto">
              <div class="card-body">
                  <h2 class="card-title">Card title!</h2>
                  <p>If a dog chews shoes whose shoes does he choose?</p>
                  <select @change="updateCurrentLabInput" class="select select-bordered w-full">
                      <option disabled selected>Seleccionar Instructor</option>
                      <option :value="instructor.id" v-for="instructor of $store.state.instructors">{{ instructor.name }}</option>
                  </select>
                  <div class="card-actions justify-end pt-6">
                      <button @click="reserved" class="btn">Aceptar</button>
                      <button @click="show_alert_popup=false; current_data=[]" class="btn">Cancelar</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<style>
.alert_popup {
    top: 0;
    z-index: 99999999;
    position: fixed;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(15px);
}

.alert_popup::before {
    content: "";
    position: absolute;
    z-index: -1;
    background-color: rgba(0, 0, 0, 0.05);
    width: 100%;
    height: 100%;
}
</style>

<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
import Calendario, {GetCalendarFieldId} from "@/components/Calendario.vue";
import IReservations from "@/services/api/interfaces/IReservations";
import ILaboratories from "@/services/api/interfaces/ILaboratories";
import APIServices from "@/services/api/APIServices";
import Cookie from "@/services/Cookie";
import store from "@/store";

@Component({ components: {Calendario} })
export default class Home extends Vue {
    updateCalendar = false
    show_alert_popup = false
    current_data = []
    before_current_lab: any = null
    current_instructor: any = null

    updateCurrentLabInput(e: any) {
        this.current_instructor = e.target.value
    }

    @Watch("updateCalendar")
    updateCalendarComponent() {
        this.clearCalendar()
        setTimeout(() => this.updateInfoCalendar() , 100)
    }

    @Watch("current_lab")
    updateCurrentLab() {
        if (this.before_current_lab != this.$store.state.current_lab) {
            this.before_current_lab = this.$store.state.current_lab
            this.clearCalendar()
            setTimeout(() => this.updateInfoCalendar() , 100)
        }
    }

    get current_lab() {
        return this.$store.state.current_lab
    }

    clearCalendar() {
        let all = document.querySelectorAll(".row-days .box")
        all.forEach((box: any) => {
            box.innerHTML = ""
            if (this.$store.state.token_exist){
                box.onclick = (e: any) => this.registerReservation(e.target.id)
            }
        })
    }

    mounted() {
        this.clearCalendar()
        window.onload = () => setTimeout(() => this.updateInfoCalendar(), 3000)
        window.onkeydown = (e: any) => {
            if (e.keyCode == 27) this.show_alert_popup = false
        }
    }

    reserved() {
        // TODO AGREGAR LA POSIBILIDAD DE REPETIR POR SEMANA
        if (!this.current_lab && !this.current_instructor) return
        let date = this.current_data
        APIServices.CreateReservation({
            lab_id: this.current_lab,
            instructor_id: this.current_instructor,
            select_year: date[1],
            select_month: date[2],
            select_day: date[3],
            select_hour: date[4]
        }).then((result: IReservations | null) => {
            if (!result) return; this.$store.state.reservations.push(result)
            this.show_alert_popup = false
            this.updateInfoCalendar()
            this.current_data = []
            this.current_instructor = null
        })
    }

    registerReservation(id: any) {
        if (!this.$store.state.current_lab) return
        this.show_alert_popup = true
        this.current_data = id.split("_")
    }

    eventClickBoxCalendar(component: any, id: any, lab_id: any) {
        if (!this.current_lab) return alert("Selecciona un laboratorio para eliminar")
        if (this.current_lab != lab_id) return alert("Selecciona el laboratorio correcto")

        component.onclick = () => this.registerReservation(component.id)
        component.innerHTML = ""
        APIServices.DeleteReservation(id)

        let reservation = this.$store.state.reservations
        this.$store.state.reservations = reservation.filter((item: any) => item.id != id)
        this.updateInfoCalendar()
    }

    createBoxCalendar(name: string): string {
        return `<div class="w-full h-full bg-red-500">${name}</div>`
    }

    updateInfoCalendar() {
        for (let reservation = 0; reservation < this.$store.state.reservations.length; reservation++) {
            let {select_day, select_hour, select_month, select_year, lab_id , instructor_id, id}: IReservations = this.$store.state.reservations[reservation]
            let component = GetCalendarFieldId(select_year, select_month, select_day, select_hour)
            if (component && lab_id == this.current_lab) {
                component.innerHTML = this.createBoxCalendar(instructor_id + "-" + id + "-" + lab_id)
                if (this.$store.state.token_exist) {
                    // TODO cambiar !Cookie a Cookie
                    component.onclick = () => this.eventClickBoxCalendar(component, id, lab_id)
                }
            }
        }
    }
}
</script>
