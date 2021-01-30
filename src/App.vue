<template>
  <div id="app">
    <b-navbar toggleable="sm" variant="fade" type="light" sticky="true" class="mt-0 p-0 w-100 navbar">
      <b-navbar-brand class="brown m-2" to="/">
          <img id="icon" :src="require('./assets/logo.jpg')" class="d-inline-block align-top" alt="Kencon" /> Kencon
      </b-navbar-brand>

      <b-navbar-toggle target="navbar-toggle-collapse">
        <template #default="{ expanded }">
          <b-icon v-if="expanded" icon="chevron-bar-up"></b-icon>
          <b-icon v-else icon="chevron-bar-down"></b-icon>
        </template>
      </b-navbar-toggle>

      <b-collapse id="navbar-toggle-collapse" is-nav>
        <b-navbar-nav class="mr-auto w-100 justify-content-end">
          <b-nav-item v-if="user === null" to="/">
            <b-button variant="light" class="sm p-1"><b-icon icon="box-arrow-in-right" aria-hidden="true"></b-icon> Sign In</b-button>
          </b-nav-item>
          <b-nav-item v-if="user !== null">
            <b-button variant="light" class="sm p-1"><b-icon icon="person-circle" aria-hidden="true"></b-icon> {{user.name}}</b-button>
          </b-nav-item>
          <b-nav-item v-if="user !== null">
            <b-button variant="light" class="sm p-1" @click="logOut"><b-icon icon="box-arrow-right" aria-hidden="true"></b-icon> Sign Out</b-button>
          </b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <router-view></router-view>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

export default {
  data: function () {
    return {
      profile: null
    }
  },
  computed: {
    ...mapGetters({
      user: 'CURRENT_USER'
    })
  },
  methods: {
    profileLoader: function () {
      var pr = null
      axios.get('./api/Staff.php?action=read').then((response) => {
        var staff = response.data.staff
        for (var i = 0; i < staff.length; i++) {
          if (this.user.email === staff[i].email) {
            pr = staff[i]
          }
        }
      })
      this.profile = pr
    },
    logOut: function () {
      this.$store.dispatch('RESET')
      this.$router.push({ name: 'Home' })
    }
  },
  mounted: function () {
    this.profileLoader()
  }
}
</script>

<style>
body {                                                            background-color: #f2f1ee;                                      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='152' height='152' viewBox='0 0 152 152'%3E%3Cg fill-rule='evenodd'%3E%3Cg id='temple' fill='%23bd790b' fill-opacity='0.69'%3E%3Cpath d='M152 150v2H0v-2h28v-8H8v-20H0v-2h8V80h42v20h20v42H30v8h90v-8H80v-42h20V80h42v40h8V30h-8v40h-42V50H80V8h40V0h2v8h20v20h8V0h2v150zm-2 0v-28h-8v20h-20v8h28zM82 30v18h18V30H82zm20 18h20v20h18V30h-20V10H82v18h20v20zm0 2v18h18V50h-18zm20-22h18V10h-18v18zm-54 92v-18H50v18h18zm-20-18H28V82H10v38h20v20h38v-18H48v-20zm0-2V82H30v18h18zm-20 22H10v18h18v-18zm54 0v18h38v-20h20V82h-18v20h-20v20H82zm18-20H82v18h18v-18zm2-2h18V82h-18v18zm20 40v-18h18v18h-18zM30 0h-2v8H8v20H0v2h8v40h42V50h20V8H30V0zm20 48h18V30H50v18zm18-20H48v20H28v20H10V30h20V10h38v18zM30 50h18v18H30V50zm-2-40H10v18h18V10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");                                                     }

#app {
  font-family: 'Avenir', Hlvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  background-color: rgba(255,255,255,0.9);
  background-attachment: fixed;
  height: 100vh;
  width: 100vw;
}

#icon {
  max-height: 30px;
  max-width: 30px;
  height: 30px;
  width: auto;
}

.navbar {
  background-color: rgba(199, 146, 40, 0.4)
}
</style>
