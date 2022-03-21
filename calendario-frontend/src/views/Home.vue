<template>
  <div class="home">
      <div class="mx-2 sm:mx-10 mb-20">
          <Calendario :update-calendar.sync="updateCalendar" start_time="6" end_time="13"/>
      </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue, Watch} from 'vue-property-decorator';
import Calendario, {GetCalendarFieldId} from "@/components/Calendario.vue";

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

    updateInfoCalendar() {
        let test = GetCalendarFieldId(2022, 3, 16, 9)
        if (test) {
            test.innerHTML = `<div class="bg-red-500 w-full"><h1>5651</h1><h2>materia: matematicas 1</h2><h2>docente: juan carlos paz</h2></div>`
            test.onclick = () => alert()
        }

        let testq = GetCalendarFieldId(2022, 3, 17, 12)
        if (testq) {
            testq.innerHTML = `<div class="bg-red-500 w-full"><h1>5651</h1><h2>materia: matematicas 1</h2><h2>docente: juan carlos paz</h2></div>`
            testq.onclick = () => alert()
        }

        let testq1 = GetCalendarFieldId(2022, 3, 25, 10)
        if (testq1) {
            testq1.innerHTML = `<div class="bg-red-500 w-full"><h1>5651</h1><h2>materia: matematicas 1</h2><h2>docente: juan carlos paz</h2></div>`
            testq1.onclick = () => alert()
        }
    }

    mounted() {
        window.onload = () => this.updateInfoCalendar()
    }
}
</script>
