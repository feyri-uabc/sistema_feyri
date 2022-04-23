<template>
  <section class="section-home">
      <div class="mx-2 sm:mx-10 mb-20">
          <Calendar v-on:update-calendar="updateCalendar" :start_time="$store.state.calendar_time_hrs.start" :end_time="$store.state.calendar_time_hrs.end">
              <template v-slot:subtitle>
                  <div class="grid w-full justify-center">
                      <h2 class="text-xl font-bold text-center" v-if="$store.state.current_lab">
                          {{("Laboratorio " + $store.state.current_lab.name).toUpperCase()}}
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
          </Calendar>
      </div>

      <CardModal :toggle="open_modal" v-on:escape="closeModal">
          <template v-slot:body>
              <Create :reservations_type="reservation_types" :item_html="current_item_box" v-if="modalType==='create'" v-on:close="closeModal"/>
              <Remove :item_html="current_item_box" v-if="modalType==='remove'" v-on:close="closeModal"/>
          </template>
      </CardModal>
  </section>
</template>

<style>
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
import {Component, Vue, Watch} from 'vue-property-decorator';
import Calendar, {GetCalendarFieldId} from "@/components/Calendar.vue";
import CardModal from "@/components/CardModal.vue";
import Create from "@/components/modal_body/reservation/Create.vue";
import Remove from "@/components/modal_body/reservation/Remove.vue";
import ILaboratories from "@/services/api/interfaces/ILaboratories";
import IReservations from "@/services/api/interfaces/IReservations";
import IInstructors from "@/services/api/interfaces/IInstructors";
import ICourses from "@/services/api/interfaces/Courses";
import IGroups from "@/services/api/interfaces/Groups";

@Component({ components: {Create, Remove, Calendar, CardModal} })
export default class Home extends Vue {
    reservation_types: Array<{value: string, color: string}> = [
        {value: "Clase", color: "bg-blue-300"},
        {value: "Taller", color: "bg-green-300"},
        {value: "Examen", color: "bg-red-300"},
        {value: "Externo", color: "bg-orange-300"},
        {value: "Otros", color: "bg-gray-400"}
    ]
    open_modal: boolean = false
    modalType: string | null = null // create, delete
    current_item_box: HTMLElement | null = null

    get current_lab(): ILaboratories | null {
        return this.$store.state.current_lab
    }

    get current_reservations(): Array<IReservations> | null {
        return this.$store.state.reservations
    }

    openModal(type: string) {
        this.open_modal = true
        this.modalType = type
    }

    closeModal() {
        this.open_modal = false
        this.modalType = null
    }

    reservation_type_data(type: string): {value: string, color: string} {
        return this.reservation_types.filter(item => item.value.toLowerCase() == type.toLowerCase())[0]
    }

    // TODO: Resolver insercion, colocando un dispatch en el store al finalizar todas las incersiones
    @Watch("current_reservations", { immediate: true, deep: true })
    async loadReservations() {
        if (!this.current_reservations || !this.$store.state.current_lab) return

        let reservations: Array<IReservations> | null = this.current_reservations
        reservations = reservations.filter((item: IReservations) => item.lab_id == this.$store.state.current_lab.id)
        if (reservations && reservations.length > 0) {
            let instructor_ = this.$store.state.instructors
            let courses_ = this.$store.state.courses
            let groups_ = this.$store.state.groups

            for (const item of reservations) {
                let {id, instructor_id, course_id, group_id ,tipo, select_day, select_month, select_year, select_hour} = item
                let element: HTMLElement | null = GetCalendarFieldId(select_year, select_month, select_day, select_hour)

                let instructor: IInstructors[] = instructor_.filter((item: any) => item.id = instructor_id)
                let course: ICourses[] = courses_.filter((item: any) => item.id = course_id)
                let group: IGroups[] = groups_.filter((item: any) => item.id = group_id)

                if (element) {
                    element.onclick = (e: any) => {
                        this.current_item_box = element
                        this.openModal("remove")
                    }
                    element.innerHTML = `
                        <div class="w-full p-2 h-full text-gray-900 ${this.reservation_type_data(tipo).color}" id="${id}">
                            <span>${ instructor[0].name }</span>
                            <span>${ course[0].name }</span>
                            <span>${ group[0].name }</span>
                        </div>
                    `.trim()
                }

            }
        }
    }

    clearBoxes() {
        let boxes = document.querySelectorAll(".row-days .box")
        boxes.forEach((item: any) => {
            item.innerHTML = ""
            item.onclick = (e: any) => {
                this.current_item_box = item
                this.openModal("create")
            }
        })
    }

    @Watch("current_lab", { immediate: true, deep: true })
    updateCalendar() {
        this.clearBoxes()
        if (!this.current_lab) return
        setTimeout(() => this.loadReservations(), 100)
    }
}
</script>
