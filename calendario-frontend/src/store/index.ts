import Vue from 'vue'
import Vuex from 'vuex'
import APIServices from "@/services/api/APIServices";
import ILaboratories from "@/services/api/interfaces/ILaboratories";

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        alert: {
            show: false,
            type: "success", // success, warning, error
            message: ""
        },
        //
        token_exist: false,
        //
        laboratories: null,
        instructors: null,
        reservations: null,
        //
        current_lab: null,
        //
        calendar_time_hrs: {
            start: 8,
            end: 13
        }
    },
    getters: {
        stateIsEmpty(state: any) {
            return !state.reservations && !state.instructors && !state.laboratories
        }
    },
    mutations: {
        async loadData(state) {
            await APIServices.GetAllLaboratories().then(result => state.laboratories = result)
            await APIServices.GetAllInstructors().then(result => state.instructors = result)
            await APIServices.GetAllReservations().then(result => state.reservations = result)
        }
    },
    actions: {},
    modules: {}
})
