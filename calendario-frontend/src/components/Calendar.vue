<template>
    <div class="calendar">
        <div class="col-start-1 col-end-3 my-4">
            <h1 class="font-black uppercase text-2xl text-center">Horario semanal del mes de {{ months[current_month] + " " +  current_start_date.getFullYear() }}</h1>
            <slot name="subtitle"/>

            <div class="flex items-center my-4 justify-between md:justify-end print:hidden">
                <button class="btn btn-sm mx-1 btn-ghost btn-outline" @click="backWeek">
                    <svg class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"></path></svg>
                    ANTERIOR
                </button>
                <button class="btn btn-sm mx-1 btn-ghost btn-outline" @click="nextWeek">
                    SIGUIENTE
                    <svg class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" /></svg>
                </button>
            </div>
        </div>

        <div class="time">
            <div class="row-head"></div>
            <div class="box" v-for="time in (end_time - start_time)">
                {{ time + parseInt(start_time+"") }}:00
            </div>
        </div>

        <div class="days px-2">
            <div class="row-days" v-for="(day, index) in days_week" :title="day.name">
                <div class="row-head">{{ day.name + " (" + day.day + ")" }}</div>
                <div v-for="time in (end_time - start_time)" :id="createDateID(day, time)" class="box"/>
            </div>
        </div>
    </div>
</template>

<style>
.calendar {
    margin: auto;
    display: grid;
    width: calc(100% - 2rem);
    grid-template-columns: 4rem 1fr;
}

.days {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    overflow-x: auto;
}

.days .box {
    transition: 200ms;
    background-color: rgba(0, 0, 0, 0.08);
    overflow: hidden;
    min-width: 14rem;
    user-select: none;
}

.days .box:hover:before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.08);
    box-shadow: 2px 2px 20px 5px rgba(0, 0, 0, 0.08);
}

.days .box:hover {
    transition: 200ms;
    transform: scale(1.1);
    background-color: white;
}

.row-head {
    height: 2rem;
    text-align: center;
}

.box {
    height: 5rem;
    margin: 0.3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script lang="ts">
import {Component, Prop, Vue, Watch} from "vue-property-decorator";

export const GetCalendarFieldId = (year: number, month: number, day: number, hour: number): HTMLElement | null => {
    let id = `_${year}_${month}_${day}_${hour}`
    return document.getElementById(id)
}

@Component({})
export default class Calendar extends Vue {
    @Prop({required: true}) start_time!: number
    @Prop({required: true}) end_time!: number

    months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
    days = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"]
    current_month = 0
    current_start_date = new Date()
    days_week: any = null

    mounted() {
        this.current_start_date = new Date()
        this.days_for_week()
    }

    days_for_week() {
        this.$emit("update-calendar", true)

        this.current_month = this.current_start_date.getMonth()
        this.current_start_date.setDate(this.current_start_date.getDate() - this.current_start_date.getDay() + 1)

        let result = []
        let maxDays = 5
        for (let index = 0; index < maxDays; index++) {
            result.push({
                name: this.days[index+1],
                year: this.current_start_date.getFullYear(),
                month: this.current_start_date.getMonth(),
                day: this.current_start_date.getDate()
            })
            this.current_start_date.setDate(this.current_start_date.getDate() + 1)
        }
        this.current_start_date.setDate(this.current_start_date.getDate() - (maxDays - 1))
        this.days_week = result

        this.$forceUpdate()
    }

    createDateID(day: any, time: number) {
        return `_${day.year}_${day.month}_${day.day}_${(time + parseInt(this.start_time+""))}`
    }

    backWeek() {
        this.current_start_date.setDate(this.current_start_date.getDate() - 7)
        this.days_for_week()
    }

    nextWeek() {
        this.current_start_date.setDate(this.current_start_date.getDate() + 7)
        this.days_for_week()
    }
}

</script>
