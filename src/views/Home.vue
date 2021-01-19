<template>
  <b-container fluid>
    <Login></Login>
  </b-container>
</template>

<script>
// @ is an alias to /src
import Login from '@/components/Login.vue'
import { mapGetters } from 'vuex'

export default {
  name: 'Home',
  components: {
    Login
  },
  computed: {
    ...mapGetters({
      user: 'CURRENT_USER'
    })
  },
  mounted: function () {
    if (this.user !== null) {
      this.$router.push({ name: 'User' })
    }
  }
}
</script>
