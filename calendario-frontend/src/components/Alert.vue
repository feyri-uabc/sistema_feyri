<template>
    <transition name="fade" mode="in-out">
        <div v-if="show_alert" class="alert-message fixed top-4 right-2">
            <div :class="'alert shadow-lg alert-' + $store.state.alert.type">
                <div>
                    <svg v-if="$store.state.alert.type === 'success'" xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <svg v-else-if="$store.state.alert.type === 'warning'" xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <svg v-else-if="$store.state.alert.type === 'error'" xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{$store.state.alert.message}}</span>
                </div>
            </div>
        </div>
    </transition>
</template>

<style>
.alert-message {
    z-index: 999999999;
}
.fade-enter-active, .fade-leave-active {
    transition: .3s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";

@Component({})
export default class Alert extends Vue {
    show_alert: boolean = true

    mounted() {
        setTimeout(() => {
            this.show_alert = false
            this.$store.state.alert.show = false
        }, this.$store.state.alert.time ?this.$store.state.alert.time :3500)
    }
}
</script>
