<template>
  <div>
    <Terminal></Terminal>
    <b-button variant="info" class="m-3" @click="goBack"><b-icon icon="chevron-left" aria-hidden="true"></b-icon> Go Back</b-button>
  </div>
</template>

<script>
import Terminal from '../components/Terminal.vue'

export default {
  name: 'Trader',
  components: { Terminal },
  methods: {
    goBack: function () {
      this.$router.go(-1)
    }
  }
}
</script>

<style scoped>
</style>
