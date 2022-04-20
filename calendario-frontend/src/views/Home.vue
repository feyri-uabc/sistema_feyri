<template>
  <div class="section-home">
      <div class="mx-2 sm:mx-10 mb-20">
          <Calendario :update-calendar.sync="updateCalendar" :start_time="$store.state.calendar_time_hrs.start" :end_time="$store.state.calendar_time_hrs.end">
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
  </div>
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
import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
import Calendario from "@/components/Calendario.vue";

@Component({ components: {Calendario} })
export default class Home extends Vue {
    reservation_types: Array<{value: string, color: string}> = [
        {value: "Clase", color: "bg-blue-300"},
        {value: "Taller", color: "bg-green-300"},
        {value: "Examen", color: "bg-red-300"},
        {value: "Externo", color: "bg-orange-300"},
        {value: "Otros", color: "bg-gray-400"}
    ]

    updateCalendar() {}
}
</script>
