<template>
    <nav class="flex items-center justify-between flex-wrap p-5 mx-8 print:hidden">
        <div class="left">
            <router-link class="hover:text-primary mr-6 font-black text-xl text-current" to="/">FEYRI LABS</router-link>
            <a class="hover:text-primary mr-6" href="/dashboard">Dashboard</a>
        </div>
        <slot name="right"></slot>
    </nav>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";

@Component({})
export default class Header extends Vue {}
</script>
