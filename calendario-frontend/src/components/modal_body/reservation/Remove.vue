<template>
    <div class="labs-modal">
        <h1 class="font-black uppercase text-2xl text-center pb-4">reservacion</h1>

        <div class="flex w-full">
            <div class="grid flex-grow rounded-box place-items-center">
                <button v-if="disable_btn" class="btn w-full text-sm btn-sm gap-2 btn-disabled">
                    EDITAR
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="m18.988 2.012 3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287-3-3L8 13z"></path><path d="M19 19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z"></path></svg>
                </button>
                <button v-else :class="'btn w-full text-sm btn-sm gap-2 ' + (current_step === 0 ?'btn-disabled' :'')" @click="current_step = 0">
                    EDITAR
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="m18.988 2.012 3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287-3-3L8 13z"></path><path d="M19 19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z"></path></svg>
                </button>
            </div>
            <div class="divider divider-horizontal"/>
            <div class="grid flex-grow rounded-box place-items-center">
                <button :class="'btn w-full text-sm btn-sm gap-2 ' + (current_step === 1 ?'btn-disabled' :'')" @click="current_step = 1">
                    ELIMINAR
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>
                </button>
            </div>
        </div>

        <div v-if="current_step === 0" class="pt-4">
            <div class="grid grid-cols-3 gap-4">
                <div class="form-control w-full pb-4">
                    <label class="label"> <span class="label-text">Identificador:</span> </label>
                    <input disabled :value="current_reservation.id" type="text" class="input input-bordered w-full">
                </div>

                <div class="form-control w-full pb-4">
                    <label class="label"> <span class="label-text">Tipo:</span> </label>
                    <input disabled :value="current_reservation.tipo" type="text" class="input input-bordered w-full">
                </div>

                <div class="form-control w-full pb-4">
                    <label class="label"> <span class="label-text">Fecha y hora:</span> </label>
                    <input disabled :value="reservation_date.replace('/20', '/') + ' - ' + reservation_hour" type="text" class="input input-bordered w-full px-0 text-center">
                </div>
            </div>

            <div class="form-control w-full pb-4">
                <label class="label">
                        <span class="label-text">
                            Seleccionar grupo
                        </span>
                </label>
                <select v-model="reservation_data.group" class="select select-bordered" @change="changeReservationGroup">
                    <option :selected="group.id === current_reservation.group_id" :value="group.id" v-for="group in groups">
                        {{group.name}}
                    </option>
                </select>
            </div>

            <div class="form-control w-full pb-4">
                <label class="label">
                        <span class="label-text">
                            Seleccionar materia
                        </span>
                </label>
                <select v-model="reservation_data.course" class="select select-bordered" @change="changeReservationCourse">
                    <option :selected="course.id === current_reservation.course_id" :value="course.id" v-for="course in courses">
                        {{course.name}}
                    </option>
                </select>
            </div>

            <div class="form-control w-full pb-2">
                <label class="label">
                        <span class="label-text">
                            Seleccionar docente
                        </span>
                </label>
                <select v-model="reservation_data.instructor" class="select select-bordered" @change="changeReservationInstructor">
                    <option :selected="instructor.id === current_reservation.instructor_id" :value="instructor.id" v-for="instructor in instructors">
                        {{instructor.name}}
                    </option>
                </select>
            </div>

            <div class="form-control mb-4 px-2">
                <label class="label">
                    <span class="label-text text-justify text-xs"><b class="uppercase">Importante: </b>La edicion afectara a todas las reservaciones vinculadas, para verlas, presiona la prestaña de eliminar en la parte superior.</span>
                </label>
            </div>

            <div class="mt-2 flex justify-end">
                <button @click="update" class="btn mr-5">Aceptar</button>
                <button @click="close" class="btn">Cancelar</button>
            </div>
        </div>

        <div v-else>
            <h3 class="text-center uppercase font-bold mt-4">Reservaciones vinculadas</h3>
            <div :class="'reservations-remove my-4 ' + (unique_remove ?'opacity-20':'opacity-100')">
                <div class="grid grid-cols-3">
                    <span class="text-center uppercase font-bold">ID</span>
                    <span class="text-center uppercase font-bold">Fecha</span>
                    <span class="text-center uppercase font-bold">Hora</span>
                </div>
                <div class="grid grid-cols-3" v-for="reservation in reservations_group">
                    <span class="text-center">{{reservation.id}}</span>
                    <span class="text-center">{{f2d(reservation.select_day)}}/{{f2d(reservation.select_month)}}/{{reservation.select_year}}</span>
                    <span class="text-center">{{f2d(reservation.select_hour)}}:00</span>
                </div>
            </div>
            <div class="form-control mb-8 mx-5">
                <label class="label cursor-pointer">
                    <span class="label-text">Eliminar solo la reservacion: <b>{{current_reservation.id}}</b></span>
                    <input :disabled="disable_btn" v-model="unique_remove" type="checkbox" checked="checked" class="checkbox checkbox-primary">
                </label>
            </div>
            <div class="mt-2 flex justify-end" v-if="disable_btn">
                <button disabled class="btn mr-5">Aceptar</button>
                <button disabled class="btn">Cancelar</button>
            </div>
            <div class="mt-2 flex justify-end" v-else>
                <button @click="remove" class="btn mr-5">Aceptar</button>
                <button @click="close" class="btn">Cancelar</button>
            </div>
        </div>
    </div>
</template>

<style>
.reservations-remove {
    overflow-y: auto;
    max-height: 6rem;
}
</style>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
import IReservations from "@/services/api/interfaces/IReservations";
import APIServices from "@/services/api/APIServices";
import {GetCalendarFieldId} from "@/components/Calendar.vue";
import IInstructors from "@/services/api/interfaces/IInstructors";
import {ISubInstructors} from "@/components/modal_body/reservation/Create.vue";
import ICourses from "@/services/api/interfaces/Courses";
import IGroups from "@/services/api/interfaces/Groups";

@Component({})
export default class Remove extends Vue {
    @Prop({ required: true}) item_html!: HTMLElement
    current_reservation!: IReservations
    unique_remove: boolean = true
    disable_btn = false

    current_step: number = 0
    ignoreCredentials: string[] = ["Dr.", "Dra.", "Psic.", "Mtro.", "Mtra."]
    reservation_data: {instructor?: any, group?: any, course?: any} = {instructor: null, group: null, course: null}

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

    get reservations_group(): Array<IReservations> {
        let grouping = this.current_reservation.grouping
        return this.$store.state.reservations.filter((item: IReservations) => item.grouping === grouping)
    }

    changeReservationInstructor = (e: any) => {
        if (e.target.value) this.reservation_data.instructor = e.target.value
    }

    changeReservationCourse = (e: any) => {
        if (e.target.value) this.reservation_data.course = e.target.value
    }

    changeReservationGroup = (e: any) => {
        if (e.target.value) this.reservation_data.group = e.target.value
    }

    get reservation_date() {
        return this.item_html.id.slice(1)
            .split("_").reverse().join("_").replace("_", "/")
            .split("/")[1].split("_").join("/")
    }
    get reservation_hour() {
        return this.item_html.id.split("_").reverse()[0] + ":00"
    }

    beforeMount() {
        let reservation_id: number = parseInt(`${this.item_html.children[0].id}`)
        this.current_reservation = this.$store.state.reservations.filter((item: IReservations) => item.id == reservation_id)[0]
    }

    mounted() {
        this.reservation_data.instructor = this.current_reservation.instructor_id
        this.reservation_data.group = this.current_reservation.group_id
        this.reservation_data.course = this.current_reservation.course_id
    }
    /**
     * Create 24Hours format (input: 5, output: 05:00)
     * @param num Range 1 - 24
     */
    f2d(num: number): string {
        return (num > 9) ?num+"" :"0"+num
    }

    async update() {
        if (!this.current_reservation.grouping) return this.$store.state.alert = {
            type: "error", show: true,
            message: "Error al actualizar el grupo al que pertenece la reservacion: " + this.current_reservation.id
        }

        this.current_reservation.instructor_id = this.reservation_data.instructor
        this.current_reservation.group_id = this.reservation_data.group
        this.current_reservation.course_id = this.reservation_data.course

        await APIServices.UpdateReservation(this.current_reservation).then((result: string | null) => {
            if (result == null) {
                this.$store.state.alert = {
                    type: "error", show: true,
                    message: "Error al actualizar el grupo al que pertenece la reservacion: " + this.current_reservation.id
                }
            } else {
                this.$store.state.alert = {
                    type: "success", show: true,
                    message: "Actualizacion exitosa en el grupo al que pertenece la reservacion: " + this.current_reservation.id
                }

                for (let item of this.reservations_group) {
                    this.$store.state.reservations = this.$store.state.reservations.filter((r: IReservations) => r.id != item.id)
                    item.instructor_id = this.current_reservation.instructor_id
                    item.group_id = this.current_reservation.group_id
                    item.course_id = this.current_reservation.course_id
                    this.$store.state.reservations.push(item)
                }
            }
        })

        this.close()
    }

    async remove() {
        if (!this.current_reservation.id) return this.$store.state.alert = {
            type: "error", show: true,
            message: "Error al eliminar la reservacion: " + this.current_reservation.id
        }
        this.disable_btn = true
        if (this.unique_remove) await this.removeId(this.current_reservation.id, this.item_html)
        else {
            for (let reservation of this.reservations_group) {
                let item = GetCalendarFieldId(reservation.select_year, reservation.select_month, reservation.select_day, reservation.select_hour)
                if (reservation && reservation.id) await this.removeId(reservation.id, item)
            }
        }
        this.close()
    }

    async removeId(id: number, item: HTMLElement | null): Promise<void> {
        await APIServices.DeleteReservation(id).then((result: string | null) => {
            if (!result) {
                return this.$store.state.alert = { type: "error",  show: true,  message: "Error al eliminar la reservacion: " + id }
            }
            this.$store.state.reservations = this.$store.state.reservations.filter((item: IReservations) => item.id !== id)
            if (item != null) item.innerHTML = ""
            this.$store.state.alert = { type: "error",  show: true,  message: "Reservacion eliminada: " + id }
        })
    }

    removeCredential(instructor: IInstructors): ISubInstructors {
        let name = ""
        for (let item of this.ignoreCredentials) {
            if (instructor.name.includes(item)) name = instructor.name.replace(item, "")
        }
        if (name == "") name = instructor.name
        return {...instructor, subname: name.trim() }
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

    close() {
        this.$emit("close")
    }
}
</script>
