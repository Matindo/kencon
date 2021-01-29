<template>
  <div>
    <Terminal v-if="currentUser.role === 'Cashier'" />
    <Admin v-if="currentUser.role === 'Admin'" />
    <b-button block variant="danger" @click="exit">Sign Out</b-button>
  </div>
</template>

<script>
import axios from 'axios'
import Admin from '@/components/Admin.vue'
import Terminal from '@/components/Terminal.vue'
import { mapGetters } from 'vuex'

export default {
  name: 'User',
  components: { Admin, Terminal },
  data: function () {
    return {
    }
  },
  computed: {
    ...mapGetters({
      currentUser: 'CURRENT_USER'
    })
  },
  methods: {
    exit: function () {
      this.$store.dispatch('RESET')
      this.$router.push({ name: 'Home' })
    },
    loadUsers: function () {
      axios.get('./api/Users.php?action=read').then((response) => {
        this.$store.dispatch('SET_USERS', response.data.users)
      })
    },
    loadStaff: function () {
      axios.get('./api/Staff.php?action=read').then((response) => {
        this.$store.dispatch('SET_STAFF', response.data.staff)
      })
    },
    loadStock: function () {
      axios.get('./api/Stock.php?action=read').then((response) => {
        this.$store.dispatch('SET_STOCK', response.data.stock)
      })
    },
    loadSales: function () {
      axios.get('./api/Sales.php?action=read').then((response) => {
        this.$store.dispatch('SET_SALES', response.data.sales)
      })
    }
  },
  mounted: function () {
    if (this.currentUser === null) {
      this.exit()
    }
    this.loadSales()
    this.loadStaff()
    this.loadStock()
    this.loadUsers()
  }
}
</script>
