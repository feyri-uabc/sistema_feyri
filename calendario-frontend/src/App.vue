<template>
    <div id="app">
        <transition name="fade">
            <div class="loader bg-base-100" v-if="!show_btn">
                <div class="m-auto grid items-center justify-center">
                    <h1 class="font-bold uppercase text-center">FCQeI Labs</h1>
                    <span class="text-xl uppercase text-center">Cargando</span>
                </div>
            </div>
        </transition>

        <Alert print:hidden v-if="$store.state.alert.show" :type="$store.state.alert.type" :message="$store.state.alert.message"/>

        <Header>
          <template v-slot:right v-if="route_is_home && show_btn">
              <hr class="y-line mr-6 hidden lg:inline-block">
              <div class="flex items-center justify-center">
                  <div class="dropdown dropdown-end">
                      <label tabindex="0" class="btn btn-sm px-3">LABORATORIOS</label>
                      <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                          <li v-for="lab of $store.state.laboratories"><a @click="select_lab(lab.id)">Lab {{lab.name}}</a></li>
                      </ul>
                  </div>
              </div>
          </template>
      </Header>

      <router-view v-if="show_btn"/>

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
.y-line {
    border: 0;
    width: 2px;
    height: 1.5rem;
    background-color: rgba(0, 0, 0, 0.1);
}

.loader {
    display: flex;
    width: 100vw;
    height: 100vh;
    position: fixed;
    z-index: 999999999;
    font-size: 3rem;
}
</style>

<script lang="ts">
import {Component, Vue, Watch} from "vue-property-decorator";
import Header from "@/components/Header.vue";
import Cookie from "@/services/Cookie";
import {Route} from "vue-router";
import Alert from "@/components/Alert.vue";
import ILaboratories from "@/services/api/interfaces/ILaboratories";

@Component({
    components: { Alert, Header }
})
export default class App extends Vue {
    route_is_home: boolean = true
    show_btn = false

    async mounted() {
        await this.$store.getters.loadDate()
        this.show_btn = this.$store.state.laboratories != null && !!this.$store.state.laboratories[0]
        this.$store.state.token_exist = Cookie.containKey('remember_session')
    }

    @Watch("$route", { immediate: true, deep: true })
    onUrlChange(value: Route) {
        this.route_is_home = value.name?.toLowerCase() === "home"
    }

    select_lab(id: any) {
        let laboratories: Array<ILaboratories> = this.$store.state.laboratories
        if (!laboratories) return
        this.$store.state.current_lab = laboratories.filter((item: ILaboratories) => item.id == id)[0]
    }
}
</script>
