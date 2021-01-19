<template>
  <b-container fluid>
    <b-row align-v="stretch">
      <b-col class="w-100">
        <b-tabs justified>
          <b-tab title="Stock">
            <Stock :stock="stock" />
          </b-tab>
          <b-tab title="Staff">
            <Staff :staff="staff" />
          </b-tab>
          <b-tab title="Users">
            <Users :users="users" />
          </b-tab>
        </b-tabs>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import Staff from './Staff.vue'
import Stock from './Stock.vue'
import Users from './Users.vue'

export default {
  name: 'Admin',
  components: { Staff, Stock, Users },
  computed: {
    ...mapGetters({
      users: 'USERS',
      stock: 'DISPLAY_STOCK',
      staff: 'DISPLAY_STAFF'
    })
  },
  methods: {
    loadUsers: function () {
      axios.get('./api/Users.php?action=read').then((response) => {
        this.$store.dispatch('SET_USERS', response.data.users)
        console.log('users: ', response.data.users)
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
    this.loadUsers()
    this.loadStaff()
    this.loadStock()
    this.loadSales()
  }
}
</script>

<style scoped>
</style>
