<template>
  <b-container>
    <b-row>
      <b-col>
        <b-alert dismissable :variant="isError ? 'danger' : 'success'" :show="message">{{ message }}</b-alert>
        <b-form @reset="reset" v-if="show">
          <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We'll never share yoir e-mail address with anyone">
            <b-form-input id="mail" v-model="address" type="email" placeholder="user@mail.com" required></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-2" label="Password:" label-for="pass">
            <b-form-input id="pass" v-model="pword" type="password" required></b-form-input>
          </b-form-group>
          <b-button  variant="primary" block @click="logIn">Sign In</b-button>
          <b-button type="reset" block variant="danger">Reset</b-button>
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
      show: true,
      isError: false,
      message: null
    }
  },
  computed: {
    showErr: function () {
      var result = true
      if (this.errors.length > 0) { result = true }
      return result
    }
  },
  methods: {
    logIn: function () {
      this.$store.dispatch('SIGN_IN', { email: this.address, pword: this.pword })
      if (this.$store.getters.CURRENT_USER) {
        this.$router.push('/user')
      } else {
        this.isError = this.$store.getters.IS_ERROR
        this.message = this.$store.getters.MESSAGE
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
