import Vue from 'vue'
import Vuex from 'vuex'
import APIServices from "@/services/api/APIServices";
import ILaboratories from "@/services/api/interfaces/ILaboratories";
import IInstructors from "@/services/api/interfaces/IInstructors";
import IReservations from "@/services/api/interfaces/IReservations";
import ICourses from "@/services/api/interfaces/Courses";
import IGroups from "@/services/api/interfaces/Groups";

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        alert: {
            show: false,
            type: "success", // success, warning, error
            message: "",
            time: null
        },
        //
        token_exist: false,
        //
        laboratories: null,
        instructors: null,
        reservations: null,
        courses: null,
        groups: null,
        //
        current_lab: null,
        //
        calendar_time_hrs: {
            start: 8,
            end: 12
        },
        is_load: false
    },
    getters: {
        stateIsEmpty(state: any) {
            return !state.reservations && !state.instructors && !state.laboratories
        },
        loadDate: (state: any) => async (): Promise<void> => {
            await APIServices.GetAllLaboratories().then(result => state.laboratories = result)
            await APIServices.GetAllInstructors().then(result => state.instructors = result)
            await APIServices.GetAllReservations().then(result => state.reservations = result)
            await APIServices.GetAllCourses().then(result => state.courses = result)
            await APIServices.GetAllGroups().then(result => state.groups = result)
        }
    },
    mutations: {},
    actions: {},
    modules: {}
})
