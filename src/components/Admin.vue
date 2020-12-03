<template>
  <b-container fluid>
    <b-row align-v="stretch">
      <b-col class="w-100">
        <b-alert dismissable :variant="isErr ? 'danger' : 'success'" :show="message">{{ message }}
        </b-alert>
        <b-tabs justified>
          <b-tab title="Stock">
            <b-card no-body>
              <b-tabs vertical card pills>
                <b-tab v-for="(cat, index) in stock" :key="index" :title="cat.category">
                  <b-card-text>
                    <b-tabs content-class="mt-3" justified>
                      <b-tab v-for="(sub, index1) in cat.subcat " :key="index1" :title="sub.subcategory">
                          <b-card v-for="(item, index2) in sub.list" :key="index2" no-body class="accordion" id="accordion1" role="tablist">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                              <b-row class="w-100">
                                <b-col class="w-40">
                                  <img :src="require(`../assets/${item.img}.png`)" style="height: 8rem; width: 8rem;">
                                </b-col>
                                <b-col class="w-60 accordion" id="accordion2" role="tablist">
                                  <h4>{{ item.name }}</h4>
                                  <p>In Stock: {{ item.quantity }}</p>
                                  <b-button block v-b-toggle="'collapse-add' + index2" variant="success">Add To Stock</b-button>
                                  <b-collapse :id="'collapse-add' + index2" accordion="accordion2" role="tabpanel">
                                    <b-form inline>
                                      <label class="sr-only" for="addition">Quantity:</label>
                                      <b-input v-model="stockInfluence" id="addition" placeholder="e.g 100" type="number"></b-input>
                                      <b-button :class="visibleA ? null : 'collapsed'" :aria-expanded="visibleA ? 'true' : 'false'" :aria-controls="'collapse-add' + index2" @click="addStock(item.id)">Add</b-button>
                                    </b-form>
                                  </b-collapse>
                                  <b-button block v-b-toggle="'collapse-remove' + index2" variant="danger">Remove from Stock</b-button>
                                  <b-collapse :id="'collapse-remove' + index2" accordion="accordion2" role="tabpanel">
                                    <b-form inline>
                                      <label class="sr-only" for="removal">Quantity:</label>
                                      <b-input v-model="stockInfluence" id="removal" placeholder="e.g 5" type="number"></b-input>
                                      <b-form-text v-show="stockInfluence > item.quantity">Your deduction is more than the available stock</b-form-text>
                                      <b-button v-if="stockInfluence <= item.quantity" :class="visibleB ? null : 'collapsed'" :aria-expanded="visibleB ? 'true' : 'false'" :aria-controls="'collapse-remove' + index2" @click="reduceStock(item.id)">Remove</b-button>
                                    </b-form>
                                  </b-collapse>
                                  <b-button block v-b-toggle="'collapse-trend' + index2" variant="info">View Stock Trends</b-button>
                                </b-col>
                              </b-row>
                            </b-card-header>
                            <b-collapse :id="'collapse-trend' + index2" accordion="accordion1" role="tabpanel">
                              <h5>{{item.name}}</h5>
                            </b-collapse>
                          </b-card>
                      </b-tab>
                    </b-tabs>
                  </b-card-text>
                </b-tab>
              </b-tabs>
              <b-button block variant="success" v-b-toggle.collapse-stock-add>Add New Item</b-button>
              <b-collapse id="collapse-stock-add">
                <b-form>
                  <b-form-group><label for="name">Item Name:</label><b-input v-model="newItem.name" id="name" placeholder="e.g Silver gutter" type="text"></b-input></b-form-group>
                  <b-form-group><label for="quantity">Quantity:</label><b-input v-model="newItem.quantity" id="quantity" placeholder="e.g 50" type="number"></b-input></b-form-group>
                  <b-form-group><label for="price">Price:</label><b-input v-model="newItem.price" id="price" placeholder="e.g 2000" type="number"></b-input></b-form-group>
                  <b-form-group><label for="cat">Category:</label><b-input v-model="newItem.category" id="cat" placeholder="e.g Gutters" type="text"></b-input></b-form-group>
                  <b-form-group><label for="subCat">Sub-category:</label><b-input v-model="newItem.subcategory" id="subCat" placeholder="e.g Metallic" type="text"></b-input></b-form-group>
                  <b-form-file ref="itemimg" accept="image/*" @change="itemImgChange"></b-form-file>
                  <img :src="base64Img" style="height:150px; width:150px" />
                  <b-button :class="visible? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="collapse-stock-add" @click="addNewItem()">Add New Item</b-button>
                </b-form>
              </b-collapse>
            </b-card>
          </b-tab>
          <b-tab title="Sales">
            <b-card no-body>
              <b-tabs pills vertical card>
                <b-tab v-for="(office, index) in staff" :key="index" :title="office.office">
                  <b-card-text>
                    <h3>{{ office.name }}</h3>
                      <b-card v-for="salesperson in office.employees" :key="salesperson.num" class="accordion" id="accordionMain" role="tablist">
                        <b-card-header header-class="header">
                          <b-row>
                            <b-col class="w-40">
                              <img :src="require(`../assets/avatars/${salesperson.img}.png`)" style="height: 8rem; width: 8rem;">
                            </b-col>
                            <b-col class="w-60">
                              <h5>{{salesperson.oname}}, {{salesperson.fname}}</h5>
                              <p>ID:{{ salesperson.idNo }}</p>
                              <p>Work Status: {{ salesperson.status }}</p>
                              <b-button block v-b-toggle="'collapse-sp' + salesperson.idNo" variant="success">Edit Details</b-button>
                              <b-collapse :id="'collapse-sp' + salesperson.idNo">
                                <b-form inline>
                                  <label class="sr-only" for="1name">First Name:</label><b-input v-model="salesperson.fname" id="1name" placeholder="e.g Alex" type="text"></b-input>
                                  <label class="sr-only" for="2name">Other Names:</label><b-input v-model="salesperson.oname" id="2name" placeholder="e.g Doe Tomkins" type="text"></b-input>
                                  <label class="sr-only" for="idn">ID:</label><b-input v-model="salesperson.idNo" id="idn" placeholder="e.g 12345678" type="number"></b-input>
                                  <label class="sr-only" for="mail">E-mail:</label><b-input v-model="salesperson.email" id="mail" placeholder="e.g alextom@mail.com" type="e-mail"></b-input>
                                  <label class="sr-only" for="phone">Mobile No:</label><b-input v-model="salesperson.phone" id="phone" placeholder="e.g 254712345678" type="tel"></b-input>
                                  <label class="sr-only" for="status">Work Status:</label><b-input v-model="salesperson.status" id="status" placeholder="e.g On Duty" type="text"></b-input>
                                  <label class="sr-only" for="office">Office:</label><b-input v-model="salesperson.office" id="office" placeholder="e.g Main" type="text"></b-input>
                                  <b-form-file accept="image/*" @change="staffImgChange"></b-form-file>
                                  <img :src="base64Img" style="height:150px; width:150px" />
                                  <b-button :class="visible? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" :aria-controls="'collapse-sp' + salesperson.idNo" @click="editSalesperson(index, salesperson.num)">Edit</b-button>
                                </b-form>
                              </b-collapse>
                              <b-button block v-b-toggle="'accordion' + salesperson.idNo" variant="info" @click="fetchRecords(salesperson.idNo)">View Sales Records</b-button>
                            </b-col>
                          </b-row>
                        </b-card-header>
                        <b-collapse :id="'accordion' + salesperson.idNo" accordion="accordionMain" role="tabpanel">
                          <h5>Employee No: {{ salesperson.num }}</h5>
                        </b-collapse>
                      </b-card>
                  </b-card-text>
                </b-tab>
              </b-tabs>
            <b-button variant="success" block v-b-toggle.collapse-staff-add>Add Staff Member</b-button>
            <b-collapse id="collapse-staff-add">
              <b-form>
                <b-form-group><label for="1name">First Name:</label><b-input v-model="newStaff.fname" id="1name" placeholder="e.g Alex" type="text"></b-input></b-form-group>
                <b-form-group><label for="2name">Other Names:</label><b-input v-model="newStaff.oname" id="2name" placeholder="e.g Doe Tomkins" type="text"></b-input></b-form-group>
                <b-form-group><label for="idn">ID:</label><b-input v-model="newStaff.idNo" id="idn" placeholder="e.g 12345678" type="number"></b-input></b-form-group>
                <b-form-group><label for="mail">E-mail:</label><b-input v-model="newStaff.email" id="mail" placeholder="e.g alextom@mail.com" type="e-mail"></b-input></b-form-group>
                <b-form-group><label for="phone">Mobile No:</label><b-input v-model="newStaff.phone" id="phone" placeholder="e.g 254712345678" type="tel"></b-input></b-form-group>
                <b-form-group><label for="status">Work Status:</label><b-input v-model="newStaff.status" id="status" placeholder="e.g On Duty" type="text"></b-input></b-form-group>
                <b-form-group><label for="office">Office:</label><b-input v-model="newStaff.office" id="office" placeholder="e.g Main" type="text"></b-input></b-form-group>
                <b-form-file ref="userimg" accept="image/*" @change="staffImgChange"></b-form-file>
                <img :src="base64Img" style="height:150px; width:150px" />
                <b-button :class="visible? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="collapse-staff-add" @click="addNewStaff()">Add</b-button>
              </b-form>
            </b-collapse>
            </b-card>
          </b-tab>
        </b-tabs>
      </b-col>
    </b-row>
    <b-row>
      <b-col class="w-100">
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
              <b-form-input id="name" v-model="name" type="text"
 required></b-form-input>
            </b-form-group>
            <b-form-group id="input-group-4" label="Role:" label-for="role">
              <b-form-input id="role" v-model="role" type="text" required></b-form-input>
            </b-form-group>
            <b-form-group>
              <b-form-text v-if="isErr" variant="danger">{{ message }}</b-form-text>
            </b-form-group>
            <b-button  variant="primary" @click="addUser">Add User</b-button>
          </b-form>
        </b-collapse>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data: function () {
    return {
      stockInfluence: 0,
      staffSales: [],
      itemSales: [],
      newStaff: {},
      newItem: {},
      itemImage: null,
      staffImage: null,
      base64Img: null,
      address: '',
      name: '',
      pword: '',
      role: ''
    }
  },
  computed: {
    ...mapGetters({
      stock: 'DISPLAY_STOCK',
      tempStock: 'STOCK',
      staff: 'DISPLAY_STAFF',
      tempStaff: 'STAFF',
      message: 'MESSAGE',
      isErr: 'IS_ERROR'
    })
  },
  methods: {
    addStock: function (id) {
      for (var i = 0; i < this.tempStock.length; i++) {
        if (this.tempStock[i].id === id) {
          var currentItem = this.tempStock[i]
          currentItem.quantity += Number(this.stockInfluence)
          this.$store.dispatch('UPDATE_STOCK', currentItem)
          this.stockInfluence = 0
        }
      }
    },
    reduceStock: function (id) {
      for (var i = 0; i < this.tempStock.length; i++) {
        if (this.tempStock[i].id === id) {
          var currentItem = this.tempStock[i]
          currentItem.quantity -= this.stockInfluence
          this.$store.dispatch('UPDATE_STOCK', currentItem)
          this.stockInfluence = 0
        }
      }
    },
    editSalesperson: function (index, id) {
      for (var i = 0; i < this.staff[index].employees.length; i++) {
        if (this.staff[index].employees[i].num === id) {
          var sp = this.staff[index].employees[i]
          sp.img = this.staffImage
          this.$store.dispatch('UPDATE_STAFF', sp)
        }
      }
    },
    staffImgChange: function (event) {
      this.staffImage = event.target.files[0]
      this.encodeImage(this.staffImage)
    },
    itemImgChange: function (event) {
      this.itemImage = event.target.files[0]
      this.encodeImage(this.itemImage)
    },
    encodeImage: function (image) {
      if (image) {
        const reader = new FileReader()
        reader.onload = (e) => {
          this.base64Img = e.target.result
        }
        reader.readAsDataURL(image)
      }
    },
    addNewStaff: function () {
      this.newStaff.img = this.staffImage
      this.$store.dispatch('ADD_STAFF', this.newStaff)
    },
    addNewItem: function () {
      this.newItem.img = this.itemImage
      this.$store.dispatch('ADD_STOCK', this.newItem)
    },
    addUser: function () {
      this.$store.dispatch('ADD_USER', { name: this.name, email: this.address, role: this.role, pword: this.pword })
      this.name = ''
      this.role = ''
      this.pword = ''
      this.address = ''
    },
    removeItem: function (id) {
      this.$store.dispatch('DELETE_STOCK', id)
    },
    removeStaff: function (id) {
      this.$store.dispatch('DELETE_STAFF', id)
    },
    fetchStaffRecords: function (num) {
      this.staffSales = this.$store.getters.STAFF_SALES(num)
    },
    fetchItemSales: function (item) {
      this.itemSales = this.$store.getters.ITEM_SALES(item)
    },
    loadAll: function () {
      this.$store.dispatch('LOAD_STAFF')
      this.$store.dispatch('LOAD_STOCK')
      this.$store.dispatch('LOAD_SALES')
    }
  },
  mounted: function () {
    this.loadAll()
  }
}
</script>
