<template>
  <div id="app">
    <b-navbar toggleable="sm" variant="fade" type="light" sticky="true" class="mt-0 p-0 w-100">
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
    }
  },
  mounted: function () {
    this.profileLoader()
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Hlvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

#icon {
  max-height: 30px;
  max-width: 30px;
  height: 30px;
  width: auto;
}

.brown {
  color: brown;
}
</style>
