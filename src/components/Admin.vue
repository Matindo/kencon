<template>
  <b-container fluid>
    <b-row align-v="stretch">
      <b-col class="w-100">
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
                                      <b-button :class="visibleA ? null : 'collapsed'" :aria-expanded="visibleA ? 'true' : 'false'" :aria-controls="'collapse-add' + index2" @click="addStock(index, index1, index2)">Add</b-button>
                                    </b-form>
                                  </b-collapse>
                                  <b-button block v-b-toggle="'collapse-remove' + index2" variant="danger">Remove from Stock</b-button>
                                  <b-collapse :id="'collapse-remove' + index2" accordion="accordion2" role="tabpanel">
                                    <b-form inline>
                                      <label class="sr-only" for="removal">Quantity:</label>
                                      <b-input v-model="stockInfluence" id="removal" placeholder="e.g 5" type="number"></b-input>
                                      <b-form-text v-show="stockInfluence > item.quantity">Your deduction is more than the available stock</b-form-text>
                                      <b-button v-if="stockInfluence <= item.quantity" :class="visibleB ? null : 'collapsed'" :aria-expanded="visibleB ? 'true' : 'false'" :aria-controls="'collapse-remove' + index2" @click="reduceStock(index, index1, index2)">Remove</b-button>
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
            </b-card>
          </b-tab>
          <b-tab title="Sales">
            <b-card no-body>
              <b-tabs pills vertical card>
                <b-tab v-for="(office, index) in staff" :key="index" :title="office.office">
                  <b-card-text>
                    <h3>{{ office.name }}</h3>
                      <b-card v-for="salesperson in office.employees" :key="salesperson.idNo" class="accordion" id="accordionMain" role="tablist">
                        <b-card-header header-class="header">
                          <b-row>
                            <b-col class="w-40">
                              <img :src="require(`../assets/avatars/${salesperson.img}.png`)" style="height: 8rem; width: 8rem;">
                            </b-col>
                            <b-col class="w-60">
                              <h4>{{ salesperson.fullName }}</h4>
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
                                  <b-button :class="visible? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="collapse-sp" @click="editSalesperson(index, salesperson.idNo)">Edit</b-button>
                                </b-form>
                              </b-collapse>
                              <b-button block v-b-toggle="'accordion' + salesperson.idNo" variant="info" @click="fetchRecords(salesperson.num)">View Sales Records</b-button>
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
            </b-card>
          </b-tab>
        </b-tabs>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  data: function () {
    return {
      tempStaff: [],
      tempStock: [],
      stockInfluence: 0
    }
  },
  computed: {
    stock: function () {
      return this.$store.getters.STOCK
    },
    staff: function () {
      return this.$store.getters.STAFF
    }
  },
  methods: {
    addStock: function (i1, i2, i3) {
      this.tempStock[i1].subcat[i2].list[i3].quantity += Number(this.stockInfluence)
      this.$store.dispatch('UPDATE_STOCK', this.tempStock)
      this.stockInfluence = 0
      this.tempStock = this.stock
    },
    reduceStock: function (i1, i2, i3) {
      this.tempStock[i1].subcat[i2].list[i3].quantity -= this.stockInfluence
      this.$store.dispatch('UPDATE_STOCK', this.tempStock)
      this.stockInfluence = 0
      this.tempStock = this.stock
    },
    editSalesperson: function (index, id) {
      for (var i = 0; i < this.tempStaff[index].employees; i++) {
        if (this.tempStaff[index].employees[i].idNo === id) {
          this.tempStaff[index].employees[i].fullName = this.tempStaff[index].employees[i].fname + ' ' + this.tempStaff[index].employees[i].oname
          this.$store.dispatch('UPDATE_STAFF', this.tempStaff)
          this.tempStaff = this.staff
        }
      }
    }
  },
  mounted: function () {
    this.tempStaff = this.staff
    this.tempStock = this.stock
  }
}
</script>
