<template>
  <b-container>
    <b-row>
      <b-col>
        <b-form @reset="reset" v-if="show">
          <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We'll never share yoir e-mail address with anyone">
            <b-form-input id="mail" v-model="address" type="email" placeholder="user@mail.com" required></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-2" label="Password:" label-for="pass">
            <b-form-input id="pass" v-model="pword" type="password" required></b-form-input>
          </b-form-group>
          <b-form-group v-if="showErr">
            <b-form-text v-for="(error, index) in errors" :key="index" variant="danger">{{ error }}</b-form-text>
          </b-form-group>
          <b-button  variant="primary" @click="logIn">Sign In</b-button>
          <b-button type="reset" variant="danger">Reset</b-button>
        </b-form>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  name: 'Login',
  data: function () {
    return {
      address: '',
      pword: '',
      showErr: false,
      show: true
    }
  },
  computed: {
    errors: function () {
      var errs = []
      if (this.$store.getters.USER_EXIST === false) {
        errs.push('Invalid Username!')
      }
      if (this.$store.getters.TRUE_PASSWORD === false) {
        errs.push('Incorrect password!')
      }
      return errs
    }
  },
  methods: {
    logIn: function () {
      this.$store.dispatch('SIGN_IN', { address: this.address, pword: this.pword })
      if (this.$store.getters.TRUE_PASSWORD) {
        this.$router.push({ path: '/user' })
      } else {
        this.$router.push({ path: '/' })
        this.showErr = true
      }
    },
    reset: function () {
      this.address = ''
      this.pword = ''
      // Trick to reset/clear native browser form validation state
      this.show = false
      this.$nextTick(() => {
        this.show = true
      })
    }
  }
}
</script>

<style scoped>
</style>
