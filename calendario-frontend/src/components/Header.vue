<template>
    <nav class="header flex items-center justify-between flex-wrap sm:px-10 py-5 px-6 print:hidden">
        <div class="left flex items-center">
            <label class="btn btn-circle btn-ghost swap swap-rotate inline-grid lg:hidden">
                <input v-model="$store.state.open_burger_main" type="checkbox"/>
                <svg class="swap-off fill-current" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 512 512"><path d="M64,384H448V341.33H64Zm0-106.67H448V234.67H64ZM64,128v42.67H448V128Z"/></svg>
                <svg class="swap-on fill-current" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 512 512"><polygon points="400 145.49 366.51 112 256 222.51 145.49 112 112 145.49 222.51 256 112 366.51 145.49 400 256 289.49 366.51 400 400 366.51 289.49 256 400 145.49"/></svg>
            </label>

            <router-link to="/" class="link-home uppercase hover:text-primary mr-6 font-black text-xl text-current">FEYRI LABS</router-link>
            <router-link v-if="$store.state.token_exist" class="uppercase hover:text-primary mr-6 hidden lg:inline-block text-align:center"
                         :to="route.path"
                         v-for="route in [{name:'Labs', path:'/labs'}, {name:'Instructores', path:'/instructors'},
                                          {name:'Materias', path:'/courses'}, {name:'Grupos', path:'/groups'}]">
                {{route.name}}
            </router-link>
        </div>

        <div class="flex items-center">
            <a class="uppercase hover:text-primary hidden lg:inline-block text-align:center mr-6" href="/dashboard">TABLERO</a>
            <slot name="right"></slot>
            <label class="swap swap-rotate ml-4">
                <input type="checkbox" data-toggle-theme="dark,light" data-act-class="ACTIVECLASS"/>
                <svg class="swap-on fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
                <svg class="swap-off fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
            </label>
            <label v-if="$store.state.show_expanded_calendar" class="hidden lg:grid btn btn-sm border-2 btn-outline swap swap-rotate ml-4">
                <input type="checkbox" v-model="expanded_calendar"/>
                <svg class="swap-on fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5 12H3v9h9v-2H5zm7-7h7v7h2V3h-9z"></path></svg>
                <svg class="swap-off fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M2 15h7v7h2v-9H2v2zM15 2h-2v9h9V9h-7V2z"></path></svg>
            </label>
        </div>
    </nav>
</template>

<style>
.header {
    height: 5rem;
}
nav {
    z-index: 9999;
    position: sticky;
    top: 0;
    backdrop-filter: blur(10px);
}
@media screen and (max-width: 420px) {
    .link-home {
        display: none;
    }
}
@media screen and (max-width: 320px) {
    .link-header-text-home {
        font-size: 1rem;
    }
    .link-header-text {
        font-size: .7rem;
    }
}
</style>

<script lang="ts">
import {Component, Vue, Watch} from "vue-property-decorator";

@Component({})
export default class Header extends Vue {
    expanded_calendar = false

    @Watch("expanded_calendar", { immediate: true, deep: true })
    onChangeExpandedStatus() {
        this.$store.state.expanded_calendar = this.expanded_calendar
    }

    mounted() {
        window.onresize = (e: any) => {
            if (e.target.innerWidth < 1024) this.expanded_calendar = false
        }
    }
}
</script>
