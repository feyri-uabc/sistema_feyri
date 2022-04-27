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
            start: 6,
            end: 20
        },
        //
        open_burger_main: false,
        //
        box_max_length: 60
    },
    getters: {
        stateIsEmpty(state: any) {
            return !state.reservations && !state.instructors && !state.laboratories
        },
        loadDate: (state: any) => async (): Promise<boolean> => {
            let error_download = false
            try {
                await APIServices.GetAllLaboratories().then(result => {
                    if (!result) return error_download = true
                    state.laboratories = result
                })
                await APIServices.GetAllInstructors().then(result => {
                    if (!result) return error_download = true
                    state.instructors = result
                })
                await APIServices.GetAllReservations().then(result => {
                    if (!result) return error_download = true
                    state.reservations = result
                })
                await APIServices.GetAllCourses().then(result => {
                    if (!result) return error_download = true
                    state.courses = result
                })
                await APIServices.GetAllGroups().then(result => {
                    if (!result) return error_download = true
                    state.groups = result
                })
            } catch (e: any) { error_download = true }
            return error_download
        }
    },
    mutations: {},
    actions: {},
    modules: {}
})
