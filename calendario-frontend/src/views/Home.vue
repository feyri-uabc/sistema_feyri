<template>
  <div class="home">
      <div class="mx-2 sm:mx-10 mb-20">
          <Calendario :update-calendar.sync="updateCalendar" start_time="6" end_time="18">
              <template v-slot:subtitle>
                  <div class="grid w-full justify-center">
                      <h2 class="text-xl font-bold text-center" v-if="$store.state.current_lab">
                          {{("Laboratorio " + $store.state.laboratories.filter(item => item.id === $store.state.current_lab)[0].name).toUpperCase()}}
                      </h2>
                      <div class="w-full overflow-hidden">
                          <div class="tags-colors">
                              <div v-for="reservation in reservation_types" class="btn hover:bg-transparent cursor-default mx-2 btn-ghost gap-2">
                                  {{ reservation.value.toString().toUpperCase() }}
                                  <div :class="'badge badge-ghost ' + reservation.color"></div>
                              </div>
                          </div>
                      </div>
                  </div>
              </template>
          </Calendario>
      </div>

      <card-modal :toggle="show_alert_popup">
          <template v-slot:title>Reservacion de laboratorio</template>
          <template v-slot:actions>
              <button v-if="current_instructor" @click="reserved" class="btn">Aceptar</button>
              <button v-else class="btn btn-disabled" tabindex="-1" role="button">Aceptar</button>
              <button @click="show_alert_popup=false" class="btn">Cancelar</button>
          </template>
          <template v-slot:body>
              <select @change="updateCurrentLabInput" class="select select-bordered w-full mt-4">
                  <option disabled selected>Seleccionar Instructor</option>
                  <option :value="instructor.id" v-for="instructor of $store.state.instructors">{{ instructor.name }}</option>
              </select>

              <hr class="mx-4 my-4 opacity-50"/>
              <p class="font-bold opacity-50 text-xs text-center uppercase">Selecciona el tipo de reservacion</p>
              <div class="form-control" v-for="(reservation, index) in reservation_types">
                  <label class="label cursor-pointer">
                      <span class="label-text">{{reservation.value}}</span>
                      <input @click="setReservationType(reservation.value.toLowerCase())" type="radio" name="radio-6" class="radio radio-primary" :checked="index===0">
                  </label>
              </div>

              <hr class="mx-4 my-4 opacity-50"/>
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

                  <div v-if="!reservation_unique">
                      <hr class="mx-4 my-4 opacity-50"/>
                      <p class="font-bold opacity-50 text-xs text-center uppercase">dias a recordar</p>
                      <div class="form-control" v-for="(day, index) in days_week">
                          <label class="cursor-pointer label">
                              <span class="label-text">{{day.charAt(0).toUpperCase() + day.slice(1)}}</span>
                              <input v-model="days_select_week[index]" :aria-describedby="(current_data_element.start_day_week + index - 1)" v-if="current_data_element.title.toLowerCase() === day.toLowerCase()" disabled type="checkbox" checked class="checkbox checkbox-primary">
                              <input v-model="days_select_week[index]" :aria-describedby="(current_data_element.start_day_week + index - 1)" v-else type="checkbox" class="checkbox checkbox-primary">
                          </label>
                      </div>
                  </div>

                  <div class="mx-4 my-4"/>
                  <p v-if="!reservation_unique" class="text-xs">* Solo se realizaran las reservaciones que tengan fecha disponible</p>
              </div>
          </template>
      </card-modal>

      <card-modal :toggle="message_reservation.length > 0">
          <template v-slot:body>
              <h2 class="px-10 w-80 font-black uppercase text-center">Creando reservacion</h2>
              <p class="text-center">{{message_reservation}}</p>
          </template>
      </card-modal>

      <card-modal :toggle="show_delete_reservation">
          <template v-slot:title>¿Desea eliminar la reservacion?</template>
          <template v-slot:body>
              <div class="grid grid-cols-2">
                  <p>Fecha: <span class="font-bold">{{current_data_delete.date}}</span></p>
                  <p class="text-right">Hora: <span class="font-bold">{{current_data_delete.hour}}</span></p>
              </div>
              <p>Tipo: <span class="font-bold">{{current_data_delete.tipo.substring(0, 1).toUpperCase()}}{{current_data_delete.tipo.toLowerCase().substring(1, current_data_delete.tipo.length)}}</span></p>
              <p>Docente: <span class="font-bold">{{current_data_delete.docente_name}}</span></p>
              <p>Contacto: <span class="font-bold">{{current_data_delete.docente_contact}}</span></p>
          </template>
          <template v-slot:actions>
              <button @click="eventClickDelete" class="btn mr-4">Aceptar</button>
              <button @click="eventClickDeleteClose" class="btn">Cancelar</button>
          </template>
      </card-modal>
  </div>
</template>

<style>
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

.tags-colors {
    display: flex;
    overflow-x: auto;
}

.tags-colors::-webkit-scrollbar {
    width: 0.2rem;
    height: 0.3rem;
}

.tags-colors::-webkit-scrollbar-thumb {
    background-color: transparent;
    border-radius: 5rem;
    border: 0.25rem solid rgba(0, 0, 0, 0.1);
}
</style>

<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
import Calendario, {GetCalendarFieldId} from "@/components/Calendario.vue";
import IReservations from "@/services/api/interfaces/IReservations";
import APIServices from "@/services/api/APIServices";
import CardModal from "@/components/CardModal.vue";

@Component({ components: {CardModal, Calendario} })
export default class Home extends Vue {
    reservation_types = [
        {value: "Clase", color: "bg-blue-300"},
        {value: "Taller", color: "bg-green-300"},
        {value: "Examen", color: "bg-red-300"},
        {value: "Externo", color: "bg-orange-300"},
        {value: "Otros", color: "bg-gray-400"}
    ]

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

    current_data_element: any = null
    days_week = ["lunes", "martes", "miercoles", "jueves", "viernes"]
    days_select_week = [false, false, false, false, false]

    @Watch("show_delete_reservation")
    delete_alert_popup_data() {
        if (this.current_delete_item == null) return this.current_data_delete = null
        let reservations = this.$store.state.reservations
        let reservation = reservations.filter((item: any) => item.id == this.current_delete_item.id)[0]

        let docentes = this.$store.state.instructors
        let docente = docentes.filter((item: any) => item.id == reservation.instructor_id)[0]

        let date = reservation.select_day + "/" + (parseInt(reservation.select_month)+1) + "/" + reservation.select_year
        let hour = reservation.select_hour + ":00"

        this.current_data_delete = {
            id: reservation.id,
            tipo: reservation.tipo,
            docente_name: docente.name,
            docente_contact: docente.contact,
            date, hour
        }
    }

    setReservationType(type: string) {
        this.reservation_type = type
    }

    createBoxCalendar(instructor_id: number, _type: string): string {
        let instructor = this.$store.state.instructors.filter((item: any) => item.id == instructor_id)
        if (instructor.length < 1) return ""
        let { name, contact } = instructor[0]

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
        let body: any = document.querySelector('body')
        if (this.show_alert_popup) {
            body.style.overflow = "hidden"
            return
        }
        this.current_data = []
        this.current_instructor = this.current_data_element = null
        this.reservation_weeks = 1
        this.reservation_unique = true
        this.days_select_week = [false, false, false, false, false]
        body.style.overflow = "auto"
        this.reservation_type = "clase"
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
            if (this.$store.state.token_exist) box.onclick = (e: any) => this.registerReservation(e, e.target.id)
        })
    }

    mounted() {
        this.clearCalendar()
        window.onload = () => setTimeout(() => this.updateInfoCalendar(), 3000)
    }

    async reserved() {
        if (!this.current_lab && !this.current_instructor) return
        let date = this.current_data

        let weeks: number = 1
        if (!this.reservation_unique) weeks = this.reservation_weeks

        let selectDate = new Date(date[1], date[2], date[3])
        selectDate.setDate(selectDate.getDate() - selectDate.getDay() + 1)
        for (let week = 0; week < weeks; week++, selectDate.setDate(selectDate.getDate() + 7)) {
            let month = selectDate.getMonth()
            let year = selectDate.getFullYear()

            for(let index = 0; index < this.days_select_week.length; index++) {
                if (this.days_select_week[index]) {
                    let _day = new Date(selectDate)
                    _day.setDate(_day.getDate() + index)
                    const day = _day.getDate()

                    this.message_reservation = `Mes: ${month}, Dia: ${day}, Hora: ${date[4]}:00`
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
                }
            }

            this.message_reservation = ""
        }
        this.show_alert_popup = false
        this.reservation_type = "clase"
        this.updateInfoCalendar()
    }

    registerReservation(e: any, id: any) {
        if (!this.$store.state.current_lab) return
        this.current_data = id.split("_")

        let current = e.target.parentElement
        let _day = current.id.split("_")[1]
        let _index = this.days_week.indexOf(current.title.toLowerCase())
        let start_day_week = _day - _index
        this.current_data_element = {
            title: current.title,
            day: _day,
            start_day_week,
            rest: _index
        }
        this.days_select_week[_index] = true
        this.show_alert_popup = true
    }

    eventClickDeleteClose() {
        this.current_delete_item = null
        this.show_delete_reservation = false
    }

    eventClickDelete() {
        const _id = this.current_delete_item.component.id
        this.current_delete_item.component.onclick = (e: any) => this.registerReservation(e, _id)
        APIServices.DeleteReservation(this.current_delete_item.id)
        this.current_delete_item.component.innerHTML = ""
        let reservation = this.$store.state.reservations

        this.$store.state.reservations = reservation.filter((item: any) => item.id != this.current_delete_item.id)
        this.updateInfoCalendar()
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
        if (!this.$store.state.reservations) return
        for (let reservation = 0; reservation < this.$store.state.reservations.length; reservation++) {
            let {tipo, select_day, select_hour, select_month, select_year, lab_id , instructor_id, id}: IReservations = this.$store.state.reservations[reservation]
            let component = GetCalendarFieldId(select_year, select_month, select_day, select_hour)
            if (component && lab_id == this.current_lab) {
                component.innerHTML = this.createBoxCalendar(instructor_id, tipo)
                let itemClass = component.children[0].classList
                itemClass.remove("bg-base-300")
                let styleClass = this.reservation_types.filter(item => item.value.toLowerCase() == tipo.toLowerCase())
                itemClass.add(styleClass[0].color)
                if (this.$store.state.token_exist) component.onclick = () => this.eventClickBoxCalendar(component, id, lab_id)
            }
        }
    }
}
</script>
