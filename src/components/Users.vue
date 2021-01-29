<template>
  <b-card class="w-100">
    <b-card-header header-class="header">
      <b-container fluid>
        <b-row>
          <b-col class="w-100">
            <b-button variant="success" v-b-toggle.add-user-modal>ADD USER</b-button>
            <b-collapse id="add-user-modal">
              <b-form>
                <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We will never share yoir e-mail address with anyone" :state="Boolean(address)" invalid-feedback="Email is required">
                  <b-form-input id="mail" v-model="address" type="email" :state="Boolean(address)" required></b-form-input>
                </b-form-group>
                <b-form-group id="input-group-2" label="Password:" label-for="pass" :state="Boolean(pword)" invalid-feedback="Password is required">
                  <b-form-input id="pass" v-model="pword" type="password" :state="Boolean(pword)" required></b-form-input>
                </b-form-group>
                <b-form-group id="input-group-3" label="Name:" label-for="name" :state="Boolean(name)" invalid-feedback="Name is required">
                  <b-form-input id="name" v-model="name" :state="Boolean(name)" required></b-form-input>
                </b-form-group>
                <b-form-group id="input-group-4" label="Role:" label-for="role" :state="Boolean(role)" invalid-feedback="Role is required">
                  <b-form-select id="role" v-model="role" :options="options" :state="Boolean(role)" required></b-form-select>
                </b-form-group>
              </b-form>
              <div class="text-center mt-3">
                <b-button variant="secondary" class="mr-3" v-b-toggle.add-user-modal>Cancel</b-button><F11>
                <b-button variant="primary" class="ml-3" @click="addUser">Add User</b-button>
              </div>
            </b-collapse>
            <table class="table table-responsive stripped accordion" id="user-accordion" role="tablist">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>E-mail</th>
                  <th>Role</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in users" :key="index" role="tab">
                  <th scope="row">{{ user.id }}</th>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.role }}</td>
                  <td>
                    <b-button class="m-1 sm" variant="info" v-b-toggle="'user-accordion' + user.id" @click="setUser(user)"><b-icon icon="pencil" aria-hidden="true"></b-icon>Edit</b-button>
                    <b-button class="m-1 sm" variant="danger" @click="setUser(user);deleteUser;" size="sm"><b-icon icon="trash-fill" aria-hidden="true"></b-icon>Delete</b-button>
                  </td>
                  <b-collapse :id="'user-accordion' + user.id" accordion="user-accordion" role="tabpanel">
                    <td colspan="100">
                      <b-form>
                        <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We'll never share yoir e-mail address with anyone" :state="Boolean(currentUser.email)" invalid-feedback="Email is required">
                          <b-form-input id="mail" v-model="currentUser.email" type="email" :state="Boolean(currentUser.email)" required></b-form-input>
                        </b-form-group>
                        <b-form-group id="input-group-3" label="Name:" label-for="name" :state="Boolean(currentUser.name)" invalid-feedback="Name is required">
                          <b-form-input id="name" v-model="name" :state="Boolean(currentUser.name)" required></b-form-input>
                        </b-form-group>
                        <b-form-group id="input-group-4" label="Role:" label-for="role" :state="Boolean(currentUser.role)" invalid-feedback="Role is required">
                          <b-form-select id="role" v-model="currentUser.role" :options="options" :state="Boolean(currentUser.role)" required></b-form-select>
                        </b-form-group>
                      </b-form>                                                       <div class="text-center">
                        <b-button variant="secondary" class="mr-3" v-b-toggle="'user-accordion' + user.id">Cancel</b-button>
                        <b-button variant="primary" class="ml-3" @click="editUser">Edit User</b-button>
                      </div>
                    </td>
                  </b-collapse>
                </tr>
              </tbody>
            </table>
          </b-col>
        </b-row>
      </b-container>
    </b-card-header>
  </b-card>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Users',
  props: ['users'],
  data: function () {
    return {
      currentUser: null,
      options: [
        { value: null, text: 'Select the user\'s role' },
        { value: 'Cashier', text: 'Cashier' },
        { value: 'Admin', text: 'Administrator' }
      ],
      address: '',
      name: '',
      pword: '',
      role: null
    }
  },
  methods: {
    setUser: function (user) {
      this.currentUser = user
    },
    addUser: function () {
      const formData = new FormData()
      formData.append('name', this.name)
      formData.append('email', this.address)
      formData.append('role', this.role)
      formData.append('pword', this.pword)
      axios({
        method: 'post',
        url: './api/Users.php?action=create',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.name = ''
        this.role = null
        this.pword = ''
        this.address = ''
      })
      this.$router.push({ name: 'Loader' })
    },
    editUser: function () {
      const formData = new FormData()
      formData.append('id', this.currentUser.id)
      formData.append('name', this.currentUser.name)
      formData.append('email', this.currentUser.email)
      formData.append('role', this.currentUser.role)
      axios({
        method: 'post',
        url: './api/Users.php?action=update',
        data: formData
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.currentUser = null
      })
      this.$router.push({ name: 'Loader' })
    },
    deleteUser: function () {
      this.$bvModal.msgBoxConfirm('Are you sure you want to delete ' + this.currentUser.name + ' from the database?', {
        title: 'Delete User',
        size: 'sm',
        okVariant: 'danger',
        okTitle: 'YES',
        cancelTitle: 'NO',
        footerClass: 'p-2 center',
        centered: true
      }).then(value => {
        if (value) {
          const formData = new FormData()
          formData.append('id', this.currentUser.id)
          axios({
            method: 'post',
            url: './api/Users.php?action=delete',
            data: formData
          }).then((response) => {
            this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
            this.currentUser = null
          })
          this.$router.push({ name: 'Loader' })
        }
      })
    }
  },
  mounted: function () {
    this.loadUsers()
  }
}
</script>

<style scoped>
</style>
