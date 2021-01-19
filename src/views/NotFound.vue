<template>
  <b-container fluid>
    <h3>404: Page Not Found</h3>
    <p>
      <b-button variant='primary' block @click="goHome">Go Back Home</b-button>
    </p>
  </b-container>
</template>

<script>
export default {
  name: 'Not Found',
  methods: {
    goHome: function () {
      this.$store.dispatch('SET_ROUTE', 'Home')
      this.$router.push({ name: 'Loader' })
    }
  }
}
</script>

<style scoped>
h3 {
  text-decoration: underline;
  color: red;
}
</style>
