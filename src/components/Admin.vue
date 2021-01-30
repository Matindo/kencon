<template>
  <div class="container-fluid">
    <div class="row align-items-start justify-content-around">
      <div class="col-12">
        <b-tabs justified>
          <b-tab title="Stock">
            <b-card no-body>
              <b-tabs vertical card pills>
                <b-tab v-for="(cat, index) in stock" :key="index" :title="cat.category">
                  <b-card-text>
                    <b-tabs content-class="mt-1" justified>
                      <b-tab v-for="(sub, index2) in cat.subcat" :key="index2" :title="sub.subcategory">
                        <Item v-for="(item, index3) in sub.list" :item="item" :key="index3"></Item>
                      </b-tab>
                    </b-tabs>
                  </b-card-text>
                </b-tab>
              </b-tabs>
              <b-button class="m-3" block variant="success" @click="$bvModal.show('modalAddStock')"><b-icon icon="plus-square" aria-hidden="true"></b-icon> Add New Item</b-button>
              <b-button variant="warning" block class="m-2" @click="sellItems"><b-icon icon="cart-fill" aria-hidden="true"></b-icon> Sell Items</b-button>
            </b-card>
          </b-tab>
          <b-tab title="Staff">
            <b-card no-body>
              <b-tabs pills vertical card>
                <b-tab v-for="(office, index) in staff" :key="index" :title="office.office">
                  <b-card-text>
                    <h3>{{ office.office }}</h3>
                    <Cashier v-for="(worker, index2) in office.employees" :key="index2" :member="worker"></Cashier>
                  </b-card-text>
                </b-tab>
              </b-tabs>
              <b-button class="m-3" variant="success" @click="$bvModal.show('modalAddStaff')"><b-icon icon="person-plus" aria-hidden="true"></b-icon> Add Staff Member</b-button>
            </b-card>
          </b-tab>
          <b-tab title="Users">
            <b-card class="w-100">
              <b-card-header header-class="header">
                <b-container fluid>
                  <b-row>
                    <b-col class="w-100 justified">
                      <b-button class="m-3" block variant="success" @click="$bvModal.show('modalAddUser')" centered><b-icon icon="person-plus-fill" aria-hidden="true"></b-icon> ADD USER</b-button>
                      <table class="table table-responsive stripped">
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
                              <b-button class="m-1 sm" variant="primary" @click="setUser(user)" v-b-modal="'modalEdit' + user.id"><b-icon icon="pen" aria-hidden="true"></b-icon>Edit</b-button>
                              <b-modal :id="'modalEdit' + user.id" title="Edit User Details" hide-footer>
                                <b-form>
                                  <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We'll never share your e-mail with anyone" :state="Boolean(currentUser.email)" invalid-feedback="Email is required">
                                    <b-form-input id="mail" v-model="currentUser.email" type="email" :state="Boolean(currentUser.email)" required></b-form-input>
                                  </b-form-group>
                                  <b-form-group id="input-group-3" label="Name:" label-for="name" :state="Boolean(currentUser.name)" invalid-feedback="Name is required">
                                    <b-form-input id="name" v-model="currentUser.name" :state="Boolean(currentUser.name)" required></b-form-input>
                                  </b-form-group>
                                  <b-form-group id="input-group-4" label="Role:" label-for="role" :state="Boolean(currentUser.role)" invalid-feedback="Role is required">
                                    <b-form-select id="role" v-model="currentUser.role" :options="options" :state="Boolean(currentUser.role)" required></b-form-select>
                                  </b-form-group>
                                </b-form>
                                <div class="text-center">
                                  <b-button variant="secondary" class="mr-3" @click="$bvModal.hide('modalEdit' + user.id)">Cancel</b-button>
                                  <b-button variant="primary" class="ml-3" @click="editUser">Edit User</b-button>
                                </div>
                              </b-modal>
                              <b-button class="m-1 sm" variant="danger" @click="deleteUser(user)" size="sm"><b-icon icon="trash-fill" aria-hidden="true"></b-icon>Delete</b-button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </b-col>
                  </b-row>
                </b-container>
              </b-card-header>
            </b-card>
          </b-tab>
        </b-tabs>
      </div>
    </div>
    <b-modal id="modalAddStaff" hide-footer title="Add New Staff">
      <b-form>
        <b-form-group label="First Name" label-for="1name" invalid-feedback="First Name is required" :state="Boolean(salesperson.fName)">
          <b-form-input v-model="salesperson.fName" :state="Boolean(salesperson.fName)" id="1name" required></b-form-input>
        </b-form-group>
        <b-form-group label="Other Name(s):" label-for="2name" :state="Boolean(salesperson.oName)" invalid-feedback="Other Name(s) are required">
          <b-form-input v-model="salesperson.oName" id="2name" :state="Boolean(salesperson.oName)" required></b-form-input>
        </b-form-group>
        <b-form-group label="ID" :state="Boolean(salesperson.idNo)" label-for="idn" invalid-feedback="ID is required">
          <b-form-input v-model="salesperson.idNo" id="idb" :state="Boolean(salesperson.idNo)" required></b-form-input>
        </b-form-group>
        <b-form-group label="E-mail" :state="Boolean(salesperson.email)" label-for="mail" invalid-feedback="Valid e-mail is required">
          <b-form-input v-model="salesperson.email" id="mail" :state="Boolean(salesperson.email)" type="e-mail" required></b-form-input>
        </b-form-group>
        <b-form-group label="Mobile No." :state="Boolean(salesperson.phone)" label-for="phone" invalid-feedback=" Valid mobile number is required">
          <b-form-input v-model="salesperson.phone" id="phone" :state="Boolean(salesperson.phone)" type="telephone" required></b-form-input>
        </b-form-group>
        <b-form-group label="Status" label-for="status" :state="Boolean(salesperson.status)" invalid-feedback="Select the staff status">
          <b-form-select v-model="salesperson.status" id="status" :options="statusOptions" :state="Boolean(salesperson.status)"></b-form-select>
        </b-form-group>
        <b-form-group label="Office" :state="Boolean(salesperson.office)" label-for="office" invalid-feedback="Office is required">
          <b-form-input v-model="salesperson.office" id="office" :state="Boolean(salesperson.office)" required></b-form-input>
        </b-form-group>
        <b-form-file v-model="staffImage" accept="image/*" :state="Boolean(staffImage)" placeholder="Choose file or drop it here..." drop-placeholder="Drop it here..."></b-form-file>
        <b-form-text>Selected file: {{ staffImage ? staffImage.name : '' }}</b-form-text>
      </b-form>
      <div class="modal-footer">
        <b-button @click="$bvModal.hide('modalAddStaff')">Cancel</b-button>
        <b-button variant="primary" @click="addStaff">Add Staff</b-button>
      </div>
    </b-modal>
    <b-modal id="modalAddStock" title="Add New Item" hide-footer>
      <b-form>
        <b-form-group label="Item Name" label-for="name" :state=
"Boolean(newItem.name)" invalid-feedback="Item Name is required"
>
          <b-form-input v-model="newItem.name" id="name" :state=
"Boolean(newItem.name)" required></b-form-input>
        </b-form-group>
        <b-form-group label="Quantity" label-for="quantity" :state="Boolean(newItem.quantity)" invalid-feedback="Quantity is req
uired">
          <b-form-input v-model="newItem.quantity" id="quantity" :state="Boolean(newItem.quantity)" type="number" required></b-form-input>
        </b-form-group>
        <b-form-group label="Price" label-for="price" :state="Boolean(newItem.price)" invalid-feedback="Price is required">
          <b-form-input v-model="newItem.price" id="price" :state="Boolean(newItem.price)" type="number" required></b-form-input
>
        </b-form-group>
        <b-form-group label-for="cat" label="Category" :state="Boolean(newItem.category)" invalid-feedback="Category is required">
          <b-form-input v-model="newItem.category" id="cat" :state="Boolean(newItem.category)" required></b-form-input>
        </b-form-group>
        <b-form-group label-for="subCat" label="Sub-category" :state="Boolean(newItem.subcategory)" invalid-feedback="Subcategory is required">
          <b-form-input v-model="newItem.subcategory" id="subCat" :state="Boolean(newItem.subcategory)" required></b-form-input>        </b-form-group>
        <b-form-file v-model="itemImage" accept="image/*" :state="Boolean(itemImage)" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." required></b-form-file>
        <b-form-text>Selected file: {{ itemImage ? itemImage.name : '' }}</b-form-text>
      </b-form>
      <div class="text-center mt-3">
        <b-button variant="secondary" class="m-3" @click="$bvModal.hide('modalAddStock')">Cancel</b-button>
        <b-button variant="primary" class="m-3" @click="addItem">Add Item</b-button>
      </div>
    </b-modal>
    <b-modal id="modalAddUser" title="Add New User" hide-footer>
      <b-form>
        <b-form-group id="input-group-1" label="E-mail address:" label-for="mail" description="We will never share your e-mail address with anyone" :state="Boolean(newUser.email)" invalid-feedback="Email is required">
          <b-form-input id="mail" v-model="newUser.email" type="email" :state="Boolean(newUser.email)" required></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-2" label="Password:" label-for="pass" :state="Boolean(newUser.pword)" invalid-feedback="Password is required">
          <b-form-input id="pass" v-model="newUser.pword" type="password" :state="Boolean(newUser.pword)" required></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-3" label="Name:" label-for="name" :state="Boolean(newUser.name)" invalid-feedback="Name is required">
          <b-form-input id="name" v-model="newUser.name" :state="Boolean(newUser.name)" required></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-4" label="Role" label-for="role" :state="Boolean(newUser.role)" invalid-feedback="Please select a role">
          <b-form-select id="role" v-model="newUser.role" :options="options" :state="Boolean(newUser.role)" required></b-form-select>
        </b-form-group>
      </b-form>
      <div class="text-center mt-3">
        <b-button variant="secondary" class="m-3" @click="$bvModal.hide('modalAddUser')">Cancel</b-button>
        <b-button variant="primary" class="m-3" @click="addUser">Add User</b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios'
import Cashier from './Cashier.vue'
import Item from './Item.vue'
import { mapGetters } from 'vuex'

export default {
  name: 'Admin',
  components: { Cashier, Item },
  computed: {
    ...mapGetters({
      staff: 'DISPLAY_STAFF',
      stock: 'DISPLAY_STOCK',
      users: 'USERS'
    })
  },
  data: function () {
    return {
      salesperson: {
        fName: 'Jon', oName: 'Doe', idNo: '12345678', email: 'example@mail.com', phone: '0100123456', status: null, office: 'Main'
      },
      newItem: {
        name: 'Item name', quantity: 1, price: 1, category: 'category', subcategory: 'subcategory'
      },
      newUser: {
        email: 'email', pword: 'password', name: 'name', role: null
      },
      currentUser: {
        email: '-', name: '-', role: '-', id: '-'
      },
      options: [
        { value: null, text: 'Select the user\'s role' },
        { value: 'Cashier', text: 'Cashier' },
        { value: 'Admin', text: 'Administrator' }],
      statusOptions: [
        { value: null, text: 'Please select status' },
        { value: 'On Duty', text: 'On Duty' },
        { value: 'Off Duty', text: 'Off Duty' }
      ],
      staffImage: 'image.png',
      itemImage: 'image.png'
    }
  },
  methods: {
    setUser: function (user) {
      this.currentUser = user
    },
    addStaff: function () {
      const formData = new FormData()
      formData.append('idNo', this.salesperson.idNo)
      formData.append('fname', this.salesperson.fName)
      formData.append('oname', this.salesperson.oName)
      formData.append('address', this.salesperson.email)
      formData.append('phone', this.salesperson.phone)
      formData.append('office', this.salesperson.office)
      formData.append('status', this.salesperson.status)
      formData.append('img', this.staffImage)
      axios({
        method: 'post',
        url: './api/Staff.php?action=create',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
      })
      this.$router.push({ name: 'Loader' })
    },
    addItem: function () {
      const formData = new FormData()
      formData.append('name', this.newItem.name)
      formData.append('quantity', this.newItem.quantity)
      formData.append('price', this.newItem.price)
      formData.append('category', this.newItem.category)
      formData.append('subcategory', this.newItem.subcategory)
      formData.append('img', this.itemImage)
      axios({
        method: 'post',
        url: './api/Stock.php?action=create',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
      })
      this.$router.push({ name: 'Loader' })
    },
    addUser: function () {
      const formData = new FormData()
      formData.append('name', this.newUser.name)
      formData.append('email', this.newUser.email)
      formData.append('role', this.newUser.role)
      formData.append('pword', this.newUser.pword)
      axios({
        method: 'post',
        url: './api/Users.php?action=create',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
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
      })
      this.$router.push({ name: 'Loader' })
    },
    deleteUser: function (user) {
      this.$bvModal.msgBoxConfirm('Are you sure you want to delete ' + user.name + ' from the database?', {
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
          formData.append('id', user.id)
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
    },
    sellItems: function () {
      this.$router.push({ name: 'Trader' })
    }
  }
}
</script>

<style scoped>
</style>
