<template>
  <b-card class="w-100">
    <b-card-header header-class="header">
      <b-container fluid>
        <b-row>
          <b-col class="w-100">
            <b-button variant="success" v-b-modal.modal-add-user>ADD USER</b-button>
            <div class="table-responsive"><table class="table table-responsive stripped">
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
                <tr v-for="(user, index) in users" :key="index">
                  <th scope="row">{{ user.id }}</th>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.role }}</td>
                  <td>
                    <b-button class="pl-2" variant="info" @click="setUser(user);$bvModal.show('modal-edit-user');" size="sm"><b-icon icon="pencil" aria-hidden="true"></b-icon>Edit</b-button>
                    <b-button class="pl-2" variant="danger" @click="setUser(user);deleteUser;" size="sm"><b-icon icon="trash-fill" aria-hidden="true"></b-icon>Delete</b-button>
                  </td>
                </tr>
              </tbody>
            </table></div>
          </b-col>
        </b-row>
      </b-container>
    </b-card-header>
    <b-modal id="modal-edit-user" ref="modal" @ok="handleEditOk" title="Edit User Details">
      <form ref="form" @submit="handleEditSubmit">
        <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We'll never share yoir e-mail address with anyone" :state="Boolean(currentUser.address)" invalid-feedback="Email is required">
          <b-form-input id="mail" v-model="address" type="email" :state="Boolean(currentUseraddress)" required></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-3" label="Name:" label-for="name" :state="Boolean(currentUser.name)" invalid-feedback="Name is required">
          <b-form-input id="name" v-model="name" :state="Boolean(currentUser.name)" required></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-4" label="Role:" label-for="role" :state="Boolean(currentUser.role)" invalid-feedback="Role is required">
          <b-form-select id="role" v-model="currentUser.role" :options="options" :state="Boolean(currentUser.role)" required></b-form-select>
        </b-form-group>                                               </form>                                                       </b-modal>
    <b-modal ref="modal" id="modal-add-user" @ok="handleOk" title="Add New User" centered>
      <form ref="form" @submit="handleSubmit">
        <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We'll never share yoir e-mail address with anyone" :state="Boolean(address)" invalid-feedback="Email is required">                                                         <b-form-input id="mail" v-model="address" type="email" :state="Boolean(address)" required></b-form-input>                     </b-form-group>                                                 <b-form-group id="input-group-2" label="Password:" label-for="pass" :state="Boolean(pword)" invalid-feedback="Password is required">                                                              <b-form-input id="pass" v-model="pword" type="password" :state="Boolean(pword)" required></b-form-input>                      </b-form-group>                                                 <b-form-group id="input-group-3" label="Name:" label-for="name" :state="Boolean(name)" invalid-feedback="Name is required">                                                                       <b-form-input id="name" v-model="name" :state="Boolean(name)" required></b-form-input>                                        </b-form-group>                                                 <b-form-group id="input-group-4" label="Role:" label-for="role" :state="Boolean(role)" invalid-feedback="Role is required">                                                                       <b-form-select id="role" v-model="role" :options="options" :state="Boolean(role)" required></b-form-select>
        </b-form-group>                                               </form>
    </b-modal>
  </b-card>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Users',
  data: function () {
    return {
      users: [],
      options: [{ value: null, text: 'Select the user\'s role' }, { value: 'Cashier', text: 'Cashier' }, { value: 'Admin', text: 'Administrator' }],
      address: '',
      name: '',
      pword: '',
      role: null,
      currentUser: null
    }
  },
  methods: {
    setUser: function (user) {
      this.currentUser = user
    },
    checkFormValidity: function () {
      return this.$refs.form.checkValidity()
    },
    handleOk: function (bvModalEvt) {
      bvModalEvt.preventDefault()
      this.handleSubmit()
    },
    handleEditOk: function (bvModalEvt) {
      bvModalEvt.preventDefault()
      this.handleEditSubmit()
    },
    handleSubmit: function () {
      if (!this.checkFormValidity) {
        return
      }
      this.addUser()
    },
    handleEditSubmit: function () {
      if (!this.checkFormValidity) {
        return
      }
      this.editUser()
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
        data: formData
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
      formData.append('email', this.currentUser.address)
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
    this.$nextTick(() => {
      this.users = this.$store.getters.USERS
    })
  }
}

</script>

<style scoped>
</style>
