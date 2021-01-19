<template>
  <b-container>
    <b-row>
      <b-col>
        <form ref="form" @reset="reset" v-if="show">
          <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We'll never share yoir e-mail address with anyone" :state="Boolean(address)" invalid-feedback="E-mail is required">
            <b-form-input id="mail" v-model="address" type="email" :state="Boolean(address)" required></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-2" label="Password:" label-for="pass" :state="Boolean(pword)" invalid-feedback="Password is required">
            <b-form-input id="pass" v-model="pword" type="password" :state="Boolean(pword)" required></b-form-input>
          </b-form-group>
          <b-button @click="handleSubmit" variant="primary">Sign In</b-button>
          <b-button type="reset" variant="danger">Reset</b-button>
        </form>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  name: 'Login',
  data: function () {
    return {
      address: '',
      pword: '',
      show: true
    }
  },
  computed: {
    ...mapGetters({
      user: 'CURRENT_USER'
    })
  },
  methods: {
    handleSubmit: function () {
      if (this.$refs.form.checkValidity) {
        this.logIn()
      } else {

      }
    },
    logIn: function () {
      const formData = new FormData()
      formData.append('email', this.address)
      formData.append('pword', this.pword)
      axios({
        method: 'post',
        url: './api/Users.php?action=login',
        data: formData
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.$store.dispatch('SET_USER', response.data.user)
        this.$nextTick(() => {
          this.$router.push({ name: 'Loader' })
        })
      })
    },
    reset: function (e) {
      e.preventDefault()
      this.address = ''
      this.pword = ''
      // Trick to reset/clear native browser form validation state
      this.show = false
      this.$nextTick(() => {
        this.show = true
      })
    }
  },
  mounted: function () {
    if (this.user !== null) {
      this.$store.dispatch('SET_ROUTE', 'Home')
    }
  }
}
</script>

<style scoped>
</style>
