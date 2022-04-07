<template>
  <div id="app">
      <Header>
          <template v-slot:right>
              <div class="flex items-center justify-center">
                  <div class="dropdown dropdown-end mr-5">
                      <label tabindex="0" class="btn btn-sm px-5">LABORATORIO {{$store.state.current_lab}}</label>
                      <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                          <li><a @click="select_lab(null)">Deseleccionar</a></li>
                          <li v-for="lab of $store.state.laboratories"><a @click="select_lab(lab.id)">Lab {{lab.name}}</a></li>
                          <hr v-if="$store.state.token_exist"/>
                          <li v-if="$store.state.token_exist"><a @click="registerInstructor">Agregar Instructor</a></li>
                          <li v-if="$store.state.token_exist"><a @click="registerLab">Agregar Laboratorio</a></li>
                      </ul>
                  </div>
                  <label class="swap swap-rotate">
                      <input type="checkbox" data-toggle-theme="dark,light" data-act-class="ACTIVECLASS"/>
                      <svg class="swap-on fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
                      <svg class="swap-off fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
                  </label>
              </div>
          </template>
      </Header>

      <div v-if="create_lab_popup" class="alert_popup w-full h-full flex">
          <div class="m-auto">
              <div class="card bg-base-100 m-2">
                  <div class="card-body">
                      <h2 class="card-title">Agregar Laboratorio</h2>

                      <div class="form-control mt-5">
                          <label class="label cursor-pointer">
                              <span class="label-text">Nombre: </span>
                              <input class="input input-bordered ml-3 w-md" type="text" v-model="lab_name">
                          </label>
                      </div>

                      <div class="form-control mt-5">
                          <label class="label cursor-pointer">
                              <span class="label-text">Description: </span>
                              <input class="input input-bordered ml-3 w-md" type="text" v-model="lab_description">
                          </label>
                      </div>

                      <div class="card-actions justify-end pt-6">
                          <button v-if="lab_name.length <= 1 && lab_description.length <= 1" disabled class="btn">Aceptar</button>
                          <button v-else @click="reservedLab" class="btn">Aceptar</button>
                          <button @click="create_lab_popup=false" class="btn">Cancelar</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div v-if="create_lab_message.length > 0" class="alert_popup w-full h-full flex">
          <div  class="m-auto">
              <div class="card bg-base-100 m-2 p-8">
                  <h2 class="px-10 w-80 font-black uppercase text-center">Creando laboratorio</h2>
                  <p class="text-center">{{create_lab_message}}</p>
              </div>
          </div>
      </div>

      <div v-if="create_instructor_popup" class="alert_popup w-full h-full flex">
          <div class="m-auto">
              <div class="card bg-base-100 m-2">
                  <div class="card-body">
                      <h2 class="card-title">Agregar Instructor</h2>

                      <div class="form-control mt-5">
                          <label class="label cursor-pointer">
                              <span class="label-text">Nombre: </span>
                              <input class="input input-bordered ml-3 w-md" type="text" v-model="instructor_name">
                          </label>
                      </div>

                      <div class="form-control mt-5">
                          <label class="label cursor-pointer">
                              <span class="label-text">Contacto: </span>
                              <input class="input input-bordered ml-3 w-md" type="text" v-model="instructor_contact">
                          </label>
                      </div>

                      <div class="card-actions justify-end pt-6">
                          <button v-if="instructor_name.length <= 1 && instructor_contact.length <= 1" disabled class="btn">Aceptar</button>
                          <button v-else @click="reservedInstructor" class="btn">Aceptar</button>
                          <button @click="create_instructor_popup=false" class="btn">Cancelar</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div v-if="create_instructor_message.length > 0" class="alert_popup w-full h-full flex">
          <div  class="m-auto">
              <div class="card bg-base-100 m-2 p-8">
                  <h2 class="px-10 w-80 font-black uppercase text-center">Creando Instructor</h2>
                  <p class="text-center">{{create_instructor_message}}</p>
              </div>
          </div>
      </div>

      <router-view/>
      <footer class="pb-10 m-auto text-center">
          <p>Copyright © 2022 - All right reserved by UABC FEyRI</p>
      </footer>
  </div>
</template>

<style>
*::-webkit-scrollbar {
    width: 0.5rem;
    height: 0.5rem;
}

*::-webkit-scrollbar-track {
    background: transparent;
}

*::-webkit-scrollbar-thumb {
     background-color: transparent;
     border-radius: 5rem;
     border: 0.25rem solid rgba(0, 0, 0, 0.35);
 }

.alert_popup {
    top: 0;
    z-index: 99999999;
    position: fixed;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(15px);
}

.alert_popup::before {
    content: "";
    position: absolute;
    z-index: -1;
    background-color: rgba(0, 0, 0, 0.05);
    width: 100%;
    height: 100%;
}
</style>

<script lang="ts">
import Header from "@/components/Header.vue";
import {Component, Vue, Watch} from "vue-property-decorator";
import Cookie from "@/services/Cookie";
import APIServices from "@/services/api/APIServices";

@Component({
    components: { Header }
})
export default class App extends Vue {
    lab_name = ""
    lab_description = ""
    create_lab_popup = false
    create_lab_message = ""

    instructor_name = ""
    instructor_contact = ""
    create_instructor_popup = false
    create_instructor_message = ""

    mounted() {
        if ( this.$store.getters.stateIsEmpty ) this.$store.commit("loadData")
        this.$store.state.token_exist = Cookie.containKey('remember_session')
        window.onkeydown = (e: any) => {
            if (e.keyCode == 27) {
                this.create_instructor_popup = false
                this.create_lab_popup = false
            }
        }
    }

    select_lab(id: any) {
        this.$store.state.current_lab = id
    }

    registerLab() {
        this.create_lab_popup = true
    }

    registerInstructor() {
        this.create_instructor_popup = true
    }

    @Watch("create_lab_popup")
    updateLabPopUp() {
        if (this.create_lab_popup) return
        this.lab_name = ""
        this.lab_description = ""
    }

    @Watch("create_instructor_popup")
    updateInstructorPopUp() {
        if (this.create_instructor_popup) return
        this.instructor_name = ""
        this.instructor_contact = ""
    }

    async reservedLab() {
        this.create_lab_message = "Laboratorio" + " " + this.lab_name + " " +"creado"
        let result = await APIServices.CreateLaboratory({
            name : this.lab_name,
            description : this.lab_description
        })
        if (result) this.$store.state.laboratories.push(result)
        this.create_lab_message = ""
        this.create_lab_popup = false
    }
    async reservedInstructor() {
        this.create_instructor_message = "Instructor" + " " + this.instructor_name + " " +"creado"
        let result = await APIServices.CreateIInstructor({
            name : this.instructor_name,
            contact : this.instructor_contact
        })
        if (result) this.$store.state.instructors.push(result)
        this.create_instructor_message = ""
        this.create_instructor_popup = false
    }

}
</script>
