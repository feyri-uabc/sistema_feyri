<template>
    <div class="calendar">
        <div class="col-start-1 col-end-3 mb-10 mt-2">
            <h1 class="font-black uppercase text-2xl text-center">Horario semanal del mes de {{ months[current_month] }}</h1>
        </div>
        <div class="time">
            <div class="row-head"></div>
            <div class="box" v-for="time in (end_time - start_time)">
                {{ time + 1*start_time }}:00
            </div>
        </div>
        <div class="days px-2">
            <div class="row-days" v-if="selectValidDays(index)" v-for="(day, index) in days" :title="day" :key="'_' + index"
                 :aria-selected="isDateValid(index)" :id="current_week + '_' + days_week[index]">
                <div class="row-head">{{ getDaysFormat(index) }}</div>
                <div v-for="time in (end_time - start_time)" :id="createDateID(index, time)" class="box"/>
            </div>
        </div>
        <div class="flex flex-wrap md:flex-wrap w-full col-start-1 col-end-3 align-center justify-between mt-10 print:hidden">
            <h2 class="font-bold opacity-75 text-xl md:text-2xl uppercase">
                {{ months[current_month] }} {{ current_year }}, Semana {{ current_week }} ({{ days_week[0] }} - {{ days_week[5] }})
            </h2>
            <div class="flex align-center my-3">
                <button class="btn mr-2" @click="backWeek">
                    <svg class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"></path></svg>
                    ANTERIOR
                </button>
                <button class="btn ml-2" @click="nextWeek">
                    SIGUIENTE
                    <svg class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" /></svg>
                </button>
            </div>
        </div>
    </div>
</template>

<style>
/* TODO MIGRAR A TAILWIND */
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
    background-color: rgba(0, 0, 0, 0.03);
    overflow: hidden;
    min-width: 8rem;
    user-select: none;
}

.days .box:hover:before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.05);
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
    height: 4rem;
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
export default class Calendario extends Vue {
    @Prop({required: true}) start_time!: number
    @Prop({required: true}) end_time!: number

    months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
    days = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"]
    current_date?: number
    current_year!: number
    current_month!: number
    current_week = this.week
    days_week: number[] = []
    update = false

    mounted() {
        this.days_for_week()
        this.current_date = (this.current_week * 7) + (new Date().getDate() % 7)
    }

    createDateID(index: number, time: number) {
        let incrementForChangeMonth = 0
        if (this.days_week[index - 1] > this.days_week[index]) incrementForChangeMonth = 1
        return '_' + this.current_year + '_' + (this.current_month + incrementForChangeMonth) + '_' + this.days_week[index] + '_' + (time + parseInt(this.start_time + ""))
    }

    isDateValid(date: number) {
        return (this.current_date) ?(this.current_week * 7) + date > this.current_date :false
    }

    getDaysFormat(date: number, length?: number) {
        if ( !length ) length = this.days[date].length
        return `${this.days[date].substr(0, length)} (${this.days_week[date]})`
    }

    selectValidDays(index: number) {
        return index !== 0 && index !== 6
    }


    get week() {
        let current_date: any = new Date()
        let one_jan: any = new Date(current_date.getFullYear(), 0, 1)
        let numberOfDays = Math.floor((current_date - one_jan) / (24 * 60 * 60 * 1000))
        // Render: Load Current Year
        this.updateDate(current_date)
        return Math.ceil(((current_date.getDay() + 1 + numberOfDays) / 7) - 1)
    }

    updateDate(current_date: Date) {
        this.current_year = current_date.getUTCFullYear()
        this.current_month = current_date.getMonth()
    }

    days_for_week() {
        this.$emit("update:update-calendar", this.update)
        this.update = !this.update

        let current_date = new Date(new Date(new Date().getFullYear(), 0, 1))
        // Get first day for the week
        current_date.setDate(7 * (this.current_week - 1) + 2)
        let start_date = current_date.getDate()
        this.current_month = current_date.getMonth()
        // this.updateDate(current_date)

        // Get last day for the week
        current_date.setDate(7 * (this.current_week - 1) + 10)
        const end_date = current_date.getDate()

        // Last day watch for change month
        const top_day_month = new Date(new Date(new Date().getFullYear(), this.current_month + 1, 0)).getDate()

        // Create Array for days of week
        let result = []
        if (start_date > end_date) {
            for (let current = start_date; current <= top_day_month; current++) result.push(current)
            start_date = 1 // Change month
        }
        for (let current = start_date; current <= end_date + 1; current++) result.push(current)

        this.days_week = result
    }

    backWeek() {
        if (this.current_week <= 1) {
            this.current_week = 52
            this.current_year--
        } else this.current_week--
        this.days_for_week()
    }

    nextWeek() {
        if (this.current_week >= 52) {
            this.current_week = 1
            this.current_year++
        } else this.current_week++
        this.days_for_week()
    }
}

</script>
