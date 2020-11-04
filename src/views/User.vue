<template>
  <div>
    <Cashier v-if="currentUser.role === 'Cashier'"></Cashier>
    <Admin v-if="currentUser.role === 'Admin'"></Admin>
    <b-button block variant="danger" @click="exit">Sign Out</b-button>
  </div>
</template>

<script>
import Admin from '@/components/Admin.vue'
import Cashier from '@/components/Cashier.vue'

export default {
  name: 'User',
  components: { Admin, Cashier },
  data: function () {
    return {
    }
  },
  computed: {
    currentUser: function () {
      return this.$store.getters.CURRENT_USER
    }
  },
  methods: {
    exit: function () {
      this.$store.dispatch('RESET')
      this.$router.push('/')
    }
  }
}
</script>
