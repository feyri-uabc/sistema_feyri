<template>
    <nav class="flex items-center justify-between flex-wrap sm:px-10 py-5 px-6 print:hidden">
        <div class="left">
            <router-link class="hover:text-primary mr-6 font-black text-xl text-current" to="/">FEYRI LABS</router-link>
            <a class="hover:text-primary mr-6 hidden sm:inline-block" href="/dashboard">Dashboard</a>
        </div>
        <slot name="right"></slot>
    </nav>
</template>

<style>
nav {
    z-index: 9999;
    position: sticky;
    top: 0;
    backdrop-filter: blur(10px);
}
</style>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";

@Component({})
export default class Header extends Vue {}
</script>
