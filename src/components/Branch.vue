<template>
  <b-tab :title="office.office">
    <b-card-text>
      <Cashier v-for="worker in office.employees" :key="worker.num" :salesperson="worker" />
    </b-card-text>
  </b-tab>
</template>

<script>
import Cashier from './Cashier.vue'

export default {
  name: 'Staff Member',
  components: {
    Cashier
  },
  props: ['office'],
  data: function () {
    return {

    }
  },
  methods: {
  }
}
</script>

<style scoped>
</style>
