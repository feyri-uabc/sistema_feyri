<template>
    <div class="labs-modal">
        <div v-if="createRegisterDataForm" class="fixed left-0 top-0 w-full h-full">
            <CardModal :toggle="createRegisterDataForm">
                <template v-slot:body>
                    <CreateInstructor v-if="createRegisterDataFormType === 0" v-on:save="registerDataFormClose(0)" v-on:close="registerDataFormForceClose(0)"/>
                    <CreateCourse v-if="createRegisterDataFormType === 1" v-on:save="registerDataFormClose(1)" v-on:close="registerDataFormForceClose(1)"/>
                    <CreateGroup v-if="createRegisterDataFormType === 2" v-on:save="registerDataFormClose(2)" v-on:close="registerDataFormForceClose(2)"/>
                </template>
            </CardModal>
        </div>

        <h1 class="font-black uppercase text-2xl text-center">Crear reservacion</h1>
        <h3 class="font-bold uppercase text-lg text-center">
            <span class="pr-2">{{data_reservation.day}}</span>{{data_reservation.hour}}
        </h3>

        <ul v-if="!reservation_unique" class="steps w-full">
            <li v-for="(step, index) in options_step"
                @click="(index < current_step || index-1 <= current_step) ?current_step = index :null"
                :class="'step' + (index <= current_step ?' step-primary ':'')">
                {{step}}
            </li>
        </ul>

        <div v-if="current_step === 0" class="list_modal_data pt-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control w-full pb-4">
                    <label class="label">
                        <span :class="'label-text opacity-' + (reservation_data.type!=null ?'100' :'0')">
                            Tipo de reservacion
                        </span>
                    </label>
                    <select v-model="reservation_data.type" @change="changeReservationType" class="select select-bordered">
                        <option disabled selected :value="null">Tipo de reservacion</option>
                        <option :value="type.value" v-for="type in reservations_type">
                            {{type.value}}
                        </option>
                    </select>
                </div>
                <div class="form-control w-full pb-4">
                    <label class="label">
                        <span :class="'label-text opacity-' + (reservation_data.group!=null ?'100' :'0')">Selecciona el grupo</span>
                    </label>
                    <select v-model="reservation_data.group" @change="changeReservationGroup" class="select select-bordered">
                        <option disabled selected :value="null">Selecciona el grupo</option>
                        <option :value="group.id" v-for="group in groups">
                            {{group.name}}
                        </option>
                        <option disabled :value="null">----------</option>
                        <option value=""> Crear nuevo grupo </option>
                    </select>
                </div>
            </div>

            <div class="form-control w-full pb-4">
                <label class="label">
                    <span :class="'label-text opacity-' + (reservation_data.course!=null ?'100' :'0')">Selecciona la materia</span>
                </label>
                <select v-model="reservation_data.course" @change="changeReservationCourse" class="select select-bordered">
                    <option disabled selected :value="null">Selecciona la materia</option>
                    <option :value="course.id" v-for="course in courses">
                        {{course.name}}
                    </option>
                    <option disabled :value="null">----------</option>
                    <option value=""> Crear nueva materia </option>
                </select>
            </div>

            <div class="form-control w-full pb-4">
                <label class="label">
                    <span :class="'label-text opacity-' + (reservation_data.instructor!=null ?'100' :'0')">Selecciona al docente</span>
                </label>
                <select v-model="reservation_data.instructor" @change="changeReservationInstructor" class="select select-bordered">
                    <option disabled selected :value="null">Selecciona al docente</option>
                    <option :value="instructor.id" v-for="instructor in instructors">
                        {{instructor.name}}
                    </option>
                    <option disabled :value="null">----------</option>
                    <option value=""> Crear nuevo instructor </option>
                </select>
            </div>

            <div class="flex pb-4">
                <label class="grid grid-cols-2 gap-6 label cursor-pointer">
                    <span class="label-text">Reservacion unica</span>
                    <input v-model="reservation_unique" type="checkbox" checked="checked" class="checkbox checkbox-primary">
                </label>
            </div>
        </div>

        <div v-else-if="current_step === 1" class="list_modal pt-4">
            <div class="mx-10 user-select-none mb-6">
                <h1 class="font-bold uppercase text-md text-center">Semanas a repetir <span class="border-b-2 px-2 border-current">{{weeks_repeat}}</span></h1>
                <input v-model="weeks_repeat" type="range" min="1" :max="$store.state.max_reservation_weeks" value="1" class="range range-xs" step="1">
                <div class="days-step w-full justify-between text-xs">
                    <span v-for="index in $store.state.max_reservation_weeks">{{index}}</span>
                </div>
            </div>

            <div v-for="(day_week, index) in current_days_week" class="form-control mx-10">
                <label class="label cursor-pointer">
                    <span class="label-text text-lg font-bold uppercase">{{day_week.name}}</span>
                    <input :disabled="index === index_day_hour_block.day" v-model="day_week.value" type="checkbox" checked="checked" class="checkbox checkbox-primary">
                </label>
            </div>
        </div>

        <div v-else-if="current_step === 2" class="list_modal pt-4">
            <div v-for="(hours, index) in data_hours" class="form-control mx-10">
                <label class="label cursor-pointer">
                    <span class="label-text text-lg font-bold uppercase">{{hours.hourStr}}</span>
                    <input :disabled="index === index_day_hour_block.hour" v-model="hours.value" type="checkbox" checked="checked" class="checkbox checkbox-primary">
                </label>
            </div>
        </div>

        <div v-if="current_step > 0" class="step-message pt-2">
            <p class="font-bold uppercase mx-10">
                Si ya existe una reservacion en la hora o dia seleccionado, se sobreescribira
            </p>
        </div>

        <div class="mt-2 flex justify-end" v-if="removed">
            <button disabled class="btn mr-4">Guardar</button>
            <button disabled class="btn">Cancelar</button>
        </div>

        <div class="mt-2 flex justify-end" v-else>
            <button v-if="(reservation_unique && form_is_valid) || (form_is_valid && current_step === 2)" @click="save" class="btn mr-4">Guardar</button>
            <button v-else disabled class="btn mr-4">Guardar</button>
            <button @click="close" class="btn">Cancelar</button>
        </div>
    </div>
</template>

<style>
.days-step {
    display: grid;
    grid-template-columns: repeat(var(--max-columns), 1fr);
    text-align: center;
}
.list_modal, .list_modal_data {
    overflow-y: auto;
}
.list_modal {
    height: 24rem;
}
.list_modal_data {
    height: 28rem;
}
.step-message {
    height: 4rem;
    font-size: 0.75rem;
    text-align: justify;
    opacity: 0.75;
}
</style>

<script lang="ts">
import {Component, Prop, Vue, Watch} from "vue-property-decorator";
import IReservations from "@/services/api/interfaces/IReservations";
import APIServices from "@/services/api/APIServices";
import IInstructors from "@/services/api/interfaces/IInstructors";
import ICourses from "@/services/api/interfaces/Courses";
import IGroups from "@/services/api/interfaces/Groups";
import CardModal from "@/components/CardModal.vue"
import CreateInstructor from "@/components/modal_body/instructor/Create.vue"
import CreateCourse from "@/components/modal_body/course/Create.vue"
import CreateGroup from "@/components/modal_body/group/Create.vue"

interface day_week {
    name: string
    day: number
    month: number
    year: number
    value: boolean
}

interface hour_day {
    hourInt: number
    hourStr: string
    value: boolean
}

interface index_block {
    day: number
    hour: number
}

interface ISubInstructors extends IInstructors {
    subname: string
}

@Component({
    components: {
        CardModal,
        CreateInstructor,
        CreateCourse,
        CreateGroup
    }
})
export default class Create extends Vue {
    @Prop({ required: true}) item_html!: HTMLElement
    @Prop({ required: true}) reservations_type!: Array<Object>
    current_days_week: Array<day_week> = [
        {name: "lunes", day: 0, value: false, year: 0, month: 0},
        {name: "martes", day: 0, value: false, year: 0, month: 0},
        {name: "miercoles", day: 0, value: false, year: 0, month: 0},
        {name: "jueves", day: 0, value: false, year: 0, month: 0},
        {name: "viernes", day: 0, value: false, year: 0, month: 0}
    ]
    options_step: Array<string> = ['Datos', 'Dias', 'Horas']
    current_step: number = 0
    weeks_repeat: number = 1

    form_is_valid: boolean = false
    removed: boolean = false

    index_day_hour_block: index_block | null = null
    reservation_unique: boolean = true
    data_hours: Array<hour_day> = []

    createRegisterDataForm: Boolean = false
    createRegisterDataFormType: number = -1
    ignoreCredentials: string[] = ["Dr.", "Dra.", "Psic.", "Mtro.", "Mtra."]
    reservation_data: {instructor?: any, group?: any, course?: any, type?: any} = {instructor: null, group: null, course: null, type: null}

    registerDataForm(type: number) {
        this.createRegisterDataForm = true
        this.createRegisterDataFormType = type
    }

    maxId(array: (IInstructors | ICourses | IGroups)[]): number {
        let _current_max = 0
        for (let item of array) if (item.id && item.id > _current_max) _current_max = item.id
        return _current_max
    }

    registerDataFormForceClose(type: number) {
        if (this.createRegisterDataFormType != -1) {
            if (type === 0) this.reservation_data.instructor = null
            else if (type === 1) this.reservation_data.course = null
            else this.reservation_data.group = null
            this.createRegisterDataFormType = -1
        }
        this.createRegisterDataForm = false
    }

    registerDataFormClose(type: number) {
        if (type === 0) this.reservation_data.instructor = this.maxId(this.instructors)
        else if (type === 1) this.reservation_data.course = this.maxId(this.courses)
        else this.reservation_data.group = this.maxId(this.groups)
        this.createRegisterDataFormType = -1
        this.registerDataFormForceClose(type)
    }

    changeReservationType = (e: any) => {
        this.reservation_data.type = e.target.value
    }

    changeReservationInstructor = (e: any) => {
        if (!e.target.value) this.registerDataForm(0)
        else this.reservation_data.instructor = e.target.value
    }

    changeReservationCourse = (e: any) => {
        if (!e.target.value) this.registerDataForm(1)
        else this.reservation_data.course = e.target.value
    }

    changeReservationGroup = (e: any) => {
        if (!e.target.value) this.registerDataForm(2)
        else this.reservation_data.group = e.target.value
    }

    removeCredential(instructor: IInstructors): ISubInstructors {
        let name = ""
        for (let item of this.ignoreCredentials) {
            if (instructor.name.includes(item)) name = instructor.name.replace(item, "")
        }
        if (name == "") name = instructor.name
        return {...instructor, subname: name.trim() }
    }

    get instructors() {
        const _instructors: IInstructors[] = this.$store.state.instructors
        let instructors: IInstructors[] = []
        _instructors.forEach(item => instructors.push(this.removeCredential(item)))
        return instructors
            .sort(this.sorter("subname", "id"))
            .reverse()
    }

    get courses() {
        const _courses: ICourses[] = this.$store.state.courses
        return _courses.sort(this.sorter("name", "id")).reverse()
    }

    get groups() {
        const _groups: IGroups[] = this.$store.state.groups
        return _groups.sort(this.sorter("name", "id")).reverse()
    }

    mounted() {
        const _reservations = this.data_reservation
        this.removed = false

        // Extract date from element HTML ID
        let _date: Array<string> = _reservations.day.split("/").reverse()
        let date: Date = new Date(parseInt(_date[0]), parseInt(_date[1])-1, parseInt(_date[2]))
        let _hour: number = parseInt(_reservations.hour.split(":")[0])
        let hour = _hour - this.$store.state.calendar_time_hrs.start - 1
        this.index_day_hour_block = { day: date.getDay() - 1,  hour }

        // Mapping week (initialize of start day week)
        date.setDate(date.getDate() - this.index_day_hour_block.day)
        this.current_days_week.forEach((item: day_week, index: number) => {
            item.day = date.getDate()
            item.month = date.getMonth()
            item.year = date.getFullYear()
            item.value = index == this.index_day_hour_block?.day
            date.setDate(date.getDate() + 1)
        })

        // Get hours values and index (assigned values)
        let time = this.$store.state.calendar_time_hrs
        for (let index = time.start + 1; index <= time.end; index++) {
            let hourStr = this.f2d(index) + ":00"
            let value = (index - time.start - 1) === this.index_day_hour_block.hour
            this.data_hours.push({ hourInt: index, hourStr, value })
        }
    }

    /**
     * Create 24Hours format (input: 5, output: 05:00)
     * @param num Range 1 - 24
     */
    f2d(num: number): string {
        return (num > 9) ?num+"" :"0"+num
    }

    get data_reservation(): {day: string, hour: string} {
        let data = this.item_html.id.slice(1).split("_").reverse()
        let day = `${this.f2d(parseInt(data[1]))}/${this.f2d(parseInt(data[2])+1)}/${data[3]}`
        return { day, hour: this.f2d(parseInt(data[0]))+":00" }
    }

    close() {
        this.reservation_unique = true
        this.current_step = 0
        this.form_is_valid = false
        this.removed = false
        this.$emit("close")
    }

    sorter(firstKey: any, secondKey: any) {
        return function(a: any, b: any) {
            if (a[firstKey] > b[firstKey]) return -1;
            else if (a[firstKey] < b[firstKey]) return 1;
            else {
                if (a[secondKey] > b[secondKey]) return 1;
                else if (a[secondKey] < b[secondKey]) return -1;
                else return 0;
            }
        }
    }

    @Watch("reservation_data", { immediate: true, deep: true })
    form_data_is_valid() {
        const { type, course, group, instructor } = this.reservation_data
        if (!type || !course || !group || !instructor) return this.form_is_valid = false
        this.form_is_valid = true
    }

    async save() {
        if (!this.form_is_valid) {
            this.$store.state.alert = { type: "error",  show: true,  message: "Datos faltantes" }
        }

        if (!this.index_day_hour_block) {
            this.$store.state.alert = { type: "error",  show: true,  message: "Ocurrio un error al crear la reservacion" }
            return this.close()
        }

        // Filter days and hours
        const days_week = (this.reservation_unique) ?[this.current_days_week[this.index_day_hour_block.day]]
            :this.current_days_week.filter((item: day_week) => item.value)
        const hours_day = (this.reservation_unique) ?[this.data_hours[this.index_day_hour_block.hour]]
            :this.data_hours.filter((item: hour_day) => item.value)

        // Create new group for reservations
        const _reservation: Array<IReservations> = this.$store.state.reservations
        let grouping = 1
        if (_reservation && _reservation[0]) {
            _reservation.sort( function (a, b) {return a.grouping - b.grouping})
            grouping = parseInt(_reservation.reverse()[0].grouping + "") + 1
        }

        // Get lab id and more data of reservations
        const lab_id = this.$store.state.current_lab.id
        const {instructor, course, group, type} = this.reservation_data

        // Create instances IReservations
        let reservations_queue: Array<IReservations> = []

        if (this.reservation_unique) this.weeks_repeat = 1
        for (let week = 0; week < this.weeks_repeat; week++) {
            days_week.forEach((day_week: day_week) => {
                // Get properties for days_week
                let {year, day, month} = day_week
                let date_week: Date = new Date(year, month, day + (week * 7))

                hours_day.forEach((hour_day: hour_day) => {
                    reservations_queue.push({
                        instructor_id: instructor,
                        group_id: group,
                        course_id: course,
                        lab_id: lab_id,
                        select_year: date_week.getFullYear(),
                        select_month: date_week.getMonth(),
                        select_day: date_week.getDate(),
                        select_hour: hour_day.hourInt,
                        tipo: type,
                        grouping: grouping
                    })
                })
            })
        }

        this.removed = true

        if (reservations_queue.length == 1) {
            await APIServices.CreateReservation(reservations_queue[0]).then((result: IReservations | null) => {
                if (result) {
                    this.$store.state.alert = { type: "success", show: true,  message: "Nueva reservacion: " + result.id }
                    this.$store.state.reservations.push(result)
                }
            })

            return this.close()
        }

        await APIServices.CreateReservationMultiple(reservations_queue).then(async result => {
            if (result) {
                this.$store.state.alert = { type: "success", show: true,  message: "Nuevo grupo de reservaciones creado" }
                await APIServices.GetAllReservations().then((response: Array<IReservations | null>) => {
                    if (response) this.$store.state.reservations = response
                })
            } else {
                this.$store.state.alert = { type: "error", show: true,  message: "Ocurrio un error, por favor, recarga la aplicacion" }
            }
        })

        this.close()
    }
}
</script>
