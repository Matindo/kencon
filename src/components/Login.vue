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
        <b-button variant="success" block v-b-toggle.collapse-add-user>ADD USER</b-button>
        <b-collapse id="collapse-add-user">
          <b-form>
            <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We'll never share yoir e-mail address with anyone">
              <b-form-input id="mail" v-model="address" type="email" placeholder="user@mail.com" required></b-form-input>
            </b-form-group>
            <b-form-group id="input-group-2" label="Password:" label-for="pass">
              <b-form-input id="pass" v-model="pword" type="password" required></b-form-input>
            </b-form-group>
            <b-form-group id="input-group-3" label="Name:" label-for="name">
              <b-form-input id="name" v-model="name" type="text" required></b-form-input>
            </b-form-group>
            <b-form-group id="input-group-4" label="Role:" label-for="role">
              <b-form-input id="role" v-model="role" type="text" required></b-form-input>
            </b-form-group>
            <b-form-group v-if="showErr">
              <b-form-text v-for="(error, index) in errors" :key="index" variant="danger">{{ error }}</b-form-text>
            </b-form-group>
            <b-button  variant="primary" @click="addUser">Add User</b-button>
          </b-form>
        </b-collapse>
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
      name: '',
      role: '',
      show: true,
      errors: []
    }
  },
  computed: {
    showErr: function () {
      var result = false
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
        this.errors.push(this.$store.getters.ERROR)
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
    },
    addUser: function () {
      this.$store.dispatch('ADD_USER', { name: this.name, email: this.address, role: this.role, pword: this.pword })
      this.reset()
    }
  }
}
</script>
<style scoped>
</style>
