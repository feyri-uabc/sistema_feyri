<template>
    <div class="labs-modal">
        <h1 class="font-black uppercase text-2xl text-center">Crear reservacion</h1>
        <h3 class="font-bold uppercase text-lg text-center">
            <span class="pr-2">{{data_reservation.day}}</span>{{data_reservation.hour}}
        </h3>

        <ul v-if="!reservation_unique" class="steps w-full">
            <li v-for="(step, index) in ['Datos', 'Dias', 'Horas']"
                @click="(index < current_step || index-1 <= current_step) ?current_step = index :null"
                :class="'step' + (index <= current_step ?' step-primary ':'')">
                {{step}}
            </li>
        </ul>

        <div v-if="current_step === 0" class="list_modal pt-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control w-full pb-4">
                    <label class="label">
                        <span class="label-text">Tipo de reservacion</span>
                    </label>
                    <select class="select select-bordered">
                        <option :value="type.id" v-for="(type, index) in reservations_type" :selected="index === 0">
                            {{type.value}}
                        </option>
                    </select>
                </div>
                <div class="form-control w-full pb-4">
                    <label class="label">
                        <span class="label-text">Selecciona el grupo</span>
                    </label>
                    <select class="select select-bordered">
                        <option :value="group.id" v-for="(group, index) in $store.state.groups" :selected="index === 0">
                            {{group.name}}
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-control w-full pb-4">
                <label class="label">
                    <span class="label-text">Selecciona la materia</span>
                </label>
                <select class="select select-bordered">
                    <option :value="course.id" v-for="(course, index) in $store.state.courses" :selected="index === 0">
                        {{course.name}}
                    </option>
                </select>
            </div>

            <div class="form-control w-full pb-4">
                <label class="label">
                    <span class="label-text">Selecciona al docente</span>
                </label>
                <select class="select select-bordered">
                    <option :value="instructor.id" v-for="(instructor, index) in $store.state.instructors" :selected="index === 0">
                        {{instructor.name}}
                    </option>
                </select>
            </div>

            <div class="flex pb-4">
                <label class="grid grid-cols-2 gap-6 label cursor-pointer">
                    <span class="label-text">Reservacion unica</span>
                    <input v-model="reservation_unique" type="checkbox" checked="checked" class="checkbox checkbox-primary">
                </label>
            </div>
        </div>

        <div v-else-if="current_step === 1" class="list_modal py-4">
            <div v-for="day_week in current_days_week" class="form-control pb-4 mx-10">
                <label class="label cursor-pointer">
                    <span class="label-text text-lg font-bold uppercase">{{day_week.name}}</span>
                    <input v-model="day_week.value" type="checkbox" checked="checked" class="checkbox checkbox-primary">
                </label>
            </div>
        </div>

        <div v-else-if="current_step === 2" class="list_modal py-4">
            <div v-for="hours in data_hours" class="form-control pb-4 mx-10">
                <label class="label cursor-pointer">
                    <span class="label-text text-lg font-bold uppercase">{{hours.hourStr}}</span>
                    <input v-model="hours.value" type="checkbox" checked="checked" class="checkbox checkbox-primary">
                </label>
            </div>
        </div>

        <div class="mt-2 flex justify-end">
            <button v-if="reservation_unique || current_step === 2" @click="close" class="btn mr-4">Guardar</button>
            <button v-else disabled class="btn mr-4">Guardar</button>
            <button @click="close" class="btn">Cancelar</button>
        </div>
    </div>
</template>

<style>
.step {
    user-select: none;
}
.step:after {
    cursor: pointer;
}
.list_modal {
    overflow-y: auto;
    height: 25rem;
}
</style>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";

@Component({})
export default class Create extends Vue {
    @Prop({ required: true}) item_html!: HTMLElement
    @Prop({ required: true}) reservations_type!: Array<Object>
    current_days_week: Array<{name: string, day: number, value: boolean}> = [
        {name: "lunes", day: 15, value: false},
        {name: "martes", day: 16, value: false},
        {name: "miercoles", day: 17, value: false},
        {name: "jueves", day: 18, value: false},
        {name: "viernes", day: 19, value: false}
    ]
    reservation_unique: boolean = true
    current_step: number = 0
    data_hours: Array<{hourInt: number, hourStr: string, value: boolean}> = []

    mounted() {
        let time = this.$store.state.calendar_time_hrs
        for (let index = time.start + 1; index <= time.end; index++) {
            this.data_hours.push({ hourInt: index, hourStr: this.f2d(index) + ":00", value: false })
        }
    }

    close() {
        this.reservation_unique = true
        this.current_step = 0
        this.$emit("close")
    }

    get data_reservation(): {day: string, hour: string} {
        let data = this.item_html.id.slice(1).split("_").reverse()
        let day = `${this.f2d(parseInt(data[1]))}/${this.f2d(parseInt(data[2])+1)}/${data[3]}`
        return { day, hour: this.f2d(parseInt(data[0]))+":00" }
    }

    f2d(num: number): string {
        return (num > 9) ?num+"" :"0"+num
    }
}
</script>
