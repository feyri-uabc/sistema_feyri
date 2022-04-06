<template>
  <div class="home">
      <div class="mx-2 sm:mx-10 mb-20">
          <Calendario :update-calendar.sync="updateCalendar" start_time="6" end_time="18">
              <template v-slot:subtitle>
                  <div class="flex w-full justify-center">
                      <div class="btn hover:bg-transparent cursor-default mx-4 btn-ghost gap-2">
                          CLASE <div class="badge badge-ghost bg-blue-300"></div>
                      </div>
                      <div class="btn hover:bg-transparent cursor-default mx-4 btn-ghost gap-2">
                          TALLER <div class="badge badge-ghost bg-green-300"></div>
                      </div>
                      <div class="btn hover:bg-transparent cursor-default mx-4 btn-ghost gap-2">
                          EXAMEN <div class="badge badge-ghost bg-red-300"></div>
                      </div>
                  </div>
              </template>
          </Calendario>
      </div>

      <div v-if="show_alert_popup" class="alert_popup w-full h-full flex">
          <div class="m-auto">
              <div class="card bg-base-100 m-2">
                  <div class="card-body">
                      <h2 class="card-title">Reservacion de laboratorio</h2>
                      <select @change="updateCurrentLabInput" class="select select-bordered w-full mt-4">
                          <option disabled selected>Seleccionar Instructor</option>
                          <option :value="instructor.id" v-for="instructor of $store.state.instructors">{{ instructor.name }}</option>
                      </select>

                      <div class="form-control">
                          <label class="label cursor-pointer">
                              <span class="label-text">Clase</span>
                              <input @click="setReservationType('clase')" type="radio" name="radio-6" class="radio radio-primary" checked>
                          </label>
                      </div>
                      <div class="form-control">
                          <label class="label cursor-pointer">
                              <span class="label-text">Taller</span>
                              <input @click="setReservationType('taller')" type="radio" name="radio-6" class="radio radio-primary">
                          </label>
                      </div>
                      <div class="form-control">
                          <label class="label cursor-pointer">
                              <span class="label-text">Examen</span>
                              <input @click="setReservationType('examen')" type="radio" name="radio-6" class="radio radio-primary">
                          </label>
                      </div>

                      <hr class="mx-4"/>

                      <div class="form-control">
                          <label class="label cursor-pointer">
                              <span class="label-text">Reservacion unica</span>
                              <input type="checkbox" v-model="reservation_unique" checked="checked" class="checkbox checkbox-primary">
                          </label>
                      </div>

                      <div class="form-control" :style="'opacity: ' + (reservation_unique ? '0.5' : '1')">
                          <label class="label cursor-pointer">
                              <span class="label-text">Semanas a recordar</span>
                              <input type="number" :disabled="reservation_unique" v-model="reservation_weeks" min="1" max="20" placeholder="Cantidad de semanas" class="input input-bordered ml-3 w-md">
                          </label>
                          <p v-if="!reservation_unique" class="text-xs">* Solo se realizaran las reservaciones que tengan fecha disponible</p>
                      </div>

                      <div class="card-actions justify-end pt-6">
                          <button v-if="current_instructor" @click="reserved" class="btn">Aceptar</button>
                          <button v-else class="btn btn-disabled" tabindex="-1" role="button">Aceptar</button>
                          <button @click="show_alert_popup=false" class="btn">Cancelar</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div v-if="message_reservation.length > 0" class="alert_popup w-full h-full flex">
          <div  class="m-auto">
              <div class="card bg-base-100 m-2 p-8">
                  <h2 class="px-10 w-80 font-black uppercase text-center">Creando reservacion</h2>
                  <p class="text-center">{{message_reservation}}</p>
              </div>
          </div>
      </div>

      <div v-if="show_delete_reservation" class="alert_popup w-full h-full flex">
          <div  class="m-auto">
              <div class="card bg-base-100 m-2 p-8">
                  <h2 class="px-10 w-80 font-black uppercase text-center">¿Desea eliminar la reservacion?</h2>
                  <div class="card-body">
                      <div class="grid grid-cols-2">
                          <p>Fecha: <span class="font-bold">{{current_data_delete.date}}</span></p>
                          <p class="text-right">Hora: <span class="font-bold">{{current_data_delete.hour}}</span></p>
                      </div>
                      <p>Tipo: <span class="font-bold">{{current_data_delete.tipo.substring(0, 1).toUpperCase()}}{{current_data_delete.tipo.toLowerCase().substring(1, current_data_delete.tipo.length)}}</span></p>
                      <p>Docente: <span class="font-bold">{{current_data_delete.docente_name}}</span></p>
                      <p>Contacto: <span class="font-bold">{{current_data_delete.docente_contact}}</span></p>
                  </div>
                  <div class="card-actions justify-center pt-6">
                      <button @click="eventClickDelete" class="btn">Aceptar</button>
                      <button @click="eventClickDeleteClose" class="btn">Cancelar</button>
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

.boxCalendar {
    width: 100%;
    height: 100%;
    display: grid;
    padding: 0.2rem 0.5rem;
}

.boxCalendar p {
    line-height: 0.6rem;
}

.boxCalendar span {
    width: 100%;
    line-height: 1.2rem;
}
</style>

<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
import Calendario, {GetCalendarFieldId} from "@/components/Calendario.vue";
import IReservations from "@/services/api/interfaces/IReservations";
import APIServices from "@/services/api/APIServices";

@Component({ components: {Calendario} })
export default class Home extends Vue {
    updateCalendar = false
    show_alert_popup = false
    current_data = []
    before_current_lab: any = null
    current_instructor: any = null
    reservation_unique = true
    reservation_weeks = 1
    message_reservation = ""
    reservation_type = "clase"

    current_delete_item: any = null
    current_data_delete: any = null
    show_delete_reservation = false

    @Watch("show_delete_reservation")
    delete_alert_popup_data() {
        if (this.current_delete_item == null) return this.current_data_delete = null
        let reservations = this.$store.state.reservations
        let reservation = reservations.filter((item: any) => item.id == this.current_delete_item.id)[0]

        let docentes = this.$store.state.instructors
        let docente = docentes.filter((item: any) => item.id == reservation.instructor_id)[0]

        let date = reservation.select_day + "/" + (reservation.select_month+1) + "/" + reservation.select_year
        let hour = reservation.select_hour + ":00"

        this.current_data_delete = {
            id: reservation.id,
            tipo: reservation.tipo,
            docente_name: docente.name,
            docente_contact: docente.contact,
            date, hour
        }
        console.log(reservation, this.current_data_delete)
    }

    setReservationType(type: string) {
        this.reservation_type = type
    }

    createBoxCalendar(instructor_id: number, _type: string): string {
        let instructor = this.$store.state.instructors.filter((item: any) => item.id == instructor_id)
        if (instructor.length < 1) return ""
        let { id, name, contact } = instructor[0]

        return `<div class="boxCalendar bg-base-300">
                    <p class="text-sm text-gray-900 font-bold"><span class="opacity-75 font-regular">Docente:</span><br/>${name}</p>
                    <p class="text-sm text-gray-900 font-bold"><span class="opacity-75 font-regular">Contacto:</span><br/>${contact}</p>
                </div>`
    }

    updateCurrentLabInput(e: any) {
        this.current_instructor = e.target.value
    }

    @Watch("show_alert_popup")
    updateAlertPopUp() {
        if (this.show_alert_popup) return
        this.current_data = []
        this.current_instructor = null
        this.reservation_weeks = 1
        this.reservation_unique = true
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

    async reserved() {
        if (!this.current_lab && !this.current_instructor) return
        let date = this.current_data

        let weeks: number = 1
        if (!this.reservation_unique) weeks = this.reservation_weeks

        let selectDate = new Date(new Date(date[1], date[2], date[3]))
        for (let week = 0; week < weeks; week++, selectDate.setDate(selectDate.getDate() + 7)) {
            let day = selectDate.getDate()
            let month = selectDate.getMonth()

            let year = selectDate.getFullYear()
            if (weeks > 1) this.message_reservation = `Mes: ${month}, Dia: ${day}, Hora: ${date[4]}`

            let result = await APIServices.CreateReservation({
                lab_id: this.current_lab,
                instructor_id: this.current_instructor,
                select_year: year,
                select_month: month,
                select_day: day,
                select_hour: date[4],
                tipo: this.reservation_type
            })

            if (result) this.$store.state.reservations.push(result)
            this.message_reservation = ""
        }
        this.show_alert_popup = false
        this.reservation_type = "clase"
        this.updateInfoCalendar()
    }

    registerReservation(id: any) {
        if (!this.$store.state.current_lab) return
        this.show_alert_popup = true
        this.current_data = id.split("_")
    }

    eventClickDeleteClose() {
        this.current_delete_item = null
        this.show_delete_reservation = false
    }

    eventClickDelete() {
        APIServices.DeleteReservation(this.current_delete_item.id)
        this.current_delete_item.component.onclick = () => this.registerReservation(this.current_delete_item.component.id)
        this.current_delete_item.component.innerHTML = ""
        let reservation = this.$store.state.reservations

        this.$store.state.reservations = reservation.filter((item: any) => item.id != this.current_delete_item.id)
        this.updateInfoCalendar()
        this.eventClickDeleteClose()
    }

    eventClickBoxCalendar(component: any, id: any, lab_id: any) {
        if (!this.current_lab) return alert("Selecciona un laboratorio para eliminar")
        if (this.current_lab != lab_id) return alert("Selecciona el laboratorio correcto")
        this.show_delete_reservation = true
        this.current_delete_item = { component, id }
    }

    updateInfoCalendar() {
        for (let reservation = 0; reservation < this.$store.state.reservations.length; reservation++) {
            let {tipo, select_day, select_hour, select_month, select_year, lab_id , instructor_id, id}: IReservations = this.$store.state.reservations[reservation]
            let component = GetCalendarFieldId(select_year, select_month, select_day, select_hour)
            if (component && lab_id == this.current_lab) {
                component.innerHTML = this.createBoxCalendar(instructor_id, tipo)
                let itemClass = component.children[0].classList
                itemClass.remove("bg-base-300")
                switch (tipo) {
                    case "clase": itemClass.add("bg-blue-300"); break;
                    case "taller": itemClass.add("bg-green-300"); break;
                    case "examen": itemClass.add("bg-red-300"); break;
                }

                if (this.$store.state.token_exist) {
                    // TODO cambiar !Cookie a Cookie
                    component.onclick = () => this.eventClickBoxCalendar(component, id, lab_id)
                }
            }
        }
    }
}
</script>
