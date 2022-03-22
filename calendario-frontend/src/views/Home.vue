<template>
  <div class="home">
      <div class="mx-2 sm:mx-10 mb-20">
          <Calendario :update-calendar.sync="updateCalendar" start_time="6" end_time="14"/>
      </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue, Watch} from 'vue-property-decorator';
import Calendario, {GetCalendarFieldId} from "@/components/Calendario.vue";
import IReservations from "@/services/api/interfaces/IReservations";
import ILaboratories from "@/services/api/interfaces/ILaboratories";
import APIServices from "@/services/api/APIServices";
import Cookie from "@/services/Cookie";

@Component({ components: {Calendario} })
export default class Home extends Vue {
    updateCalendar = false

    @Watch("updateCalendar")
    updateCalendarComponent() {
        this.clearCalendar()
        setTimeout(() => this.updateInfoCalendar() , 100)
    }

    clearCalendar() {
        let all = document.querySelectorAll(".row-days .box")
        all.forEach((box: any) => {
            box.innerHTML = ""
            box.onclick = () => null
        })
    }

    mounted() {
        window.onload = () => setTimeout(() => this.updateInfoCalendar(), 3000)
    }

    createBoxCalendar(name: string): string {
        return `<div class="w-full h-full bg-red-500">${name}</div>`
    }

    eventClickBoxCalendar() {
        alert()
    }

    eventClickBoxCalendarAdmin() {
        alert("admin")
    }

    updateInfoCalendar() {
        for (let reservation = 0; reservation < this.$store.state.reservations.length; reservation++) {
            let {select_day, select_hour, select_month, select_year, lab_id , instructor_id, id}: IReservations = this.$store.state.reservations[reservation]
            let component = GetCalendarFieldId(select_year, select_month, select_day, select_hour)
            if (component) {
                component.innerHTML = this.createBoxCalendar(instructor_id + "-" + id + "-" + lab_id)
                component.onclick = () => Cookie.containKey("remember_web")
                    ?this.eventClickBoxCalendarAdmin()
                    :this.eventClickBoxCalendar()
            }
        }
    }
}
</script>
