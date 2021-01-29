<template>
  <b-tab>
    <b-card-text>
      <Cashier v-for="(worker, index2) in office.employees" :key="index2" :member="worker" />
    </b-card-text>
  </b-tab>
</template>

<script>
import Cashier from './Cashier.vue'

export default {
  name: 'Branch',
  components: {
    Cashier
  },
  props: ['office']
}
</script>

<style scoped>
</style>
