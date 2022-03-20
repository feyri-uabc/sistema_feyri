<template>
  <div class="home">
      <h1>This is an home page</h1>
      <img alt="Vue logo" src="../assets/logo.png">
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({ components: {} })
export default class Home extends Vue {}
</script>
