<template>
    <transition name="fade">
        <section class="burger-main">
            <div class="grid pt-10 gap-6 sm:mx-20 mx-10 mb-20">
                <router-link to="/" class="link-home-burger text-2xl hidden uppercase hover:text-primary mr-6 font-black text-xl text-current">FEYRI LABS</router-link>
                <router-link v-if="$store.state.token_exist" class="text-2xl uppercase hover:text-primary mr-6 text-align:center"
                             :to="route.path"
                             v-for="route in [{name:'Labs', path:'/labs'}, {name:'Instructores', path:'/instructors'},
                                          {name:'Materias', path:'/courses'}, {name:'Grupos', path:'/groups'}]">
                    {{route.name}}
                </router-link>
                <hr class="opacity-50 border-current">
                <a class="text-2xl uppercase hover:text-primary mr-6 text-align:center" href="/dashboard">Tablero</a>
            </div>
        </section>
    </transition>
</template>

<style>
.burger-main {
    position: fixed;
    z-index: 99999;
    width: 100vw;
    height: calc(100vh - 5rem);
    bottom: 0;
    overflow-y: auto;
    backdrop-filter: blur(10px);
}
@media screen and (max-width: 420px) {
    .link-home-burger {
        display: inline-block;
    }
}
</style>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";

@Component
export default class BurgerMain extends Vue {}
</script>
