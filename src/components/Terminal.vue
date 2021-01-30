<template>
  <b-container fluid>
    <b-row fluid align-v="stretch" align-h="start">
      <b-col cols="7">
        <h3 center><b-icon icon="card-list" aria-hidden="true"></b-icon> Items</h3>
        <b-card no-body class="w-100">
          <b-tabs pills card vertical active-nav-item-class="font-weight-bold text-uppercase">
            <b-tab v-for="(item, index1) in stock" :key="index1" :title="item.category">
              <b-card no-body class="w-100">
                <b-tabs card content-class="mt-3" justified>
                  <b-tab v-for="(sub, index2) in item.subcat" :key="index2" :title="sub.subcategory">
                    <b-card-text>
                      <b-card class="accordion" id="my-accordion" role="tablist" v-for="(spec, index3) in sub.list" :key="index3">
                        <b-card-header header-class="header">
                          <b-row>
                            <b-col class="w-40">
                              <img :src="spec.img" :img-alt="spec.name" style="height: 8rem; width: 8rem;">
                            </b-col>
                            <b-col class="w-60">
                              <h5>{{spec.name}}</h5>
                              <p>In Stock: {{spec.quantity}}<br>Price: {{spec.price}}</p>
                              <b-button block variant="warning" v-b-toggle="'collapse-buy' + spec.id"><b-icon icon="cart-plus" aria-hidden="true"></b-icon> Purchase</b-button>
                              <b-collapse :id="'collapse-buy' + spec.id">
                                <b-form>
                                  <b-form-group label-for="quantity" label="Units to Buy:" :state="Boolean(quantity)" invalid-feedback="Quantity cannot be empty">
                                    <b-form-input id="quantity" type="number" v-model="quantity" :state="Boolean(quantity)"></b-form-input>
                                    <b-form-text v-show="quantity > spec.quantity">Your order is more than remaining stock quantity</b-form-text>
                                  </b-form-group>
                                  <b-button @click="buyItem(spec.id)" v-if="quantity <= spec.quantity" :class="visible? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="'collapse-buy' + spec.id" variant="success">Add to Cart</b-button>
                                </b-form>
                              </b-collapse>
                            </b-col>
                          </b-row>
                        </b-card-header>
                      </b-card>
                    </b-card-text>
                  </b-tab>
                </b-tabs>
              </b-card>
            </b-tab>
          </b-tabs>
        </b-card>
      </b-col>
      <b-col cols="5">
        <h3 centered><b-icon icon="cart3" aria-hidden="true"></b-icon> Checkout</h3>
        <b-table responsive stripped sticky-header :items="cart"></b-table>
        <p center>Total: Ksh. {{ this.runningTotal }}</p>
        <b-button block variant="warning" @click="processCart()">CHECK-OUT</b-button>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  name: 'Terminal',
  data: function () {
    return {
      cart: [],
      quantity: 0,
      runningTotal: 0,
      sale: null,
      successSales: {
        items: [],
        total: 0
      }
    }
  },
  methods: {
    buyItem: function (id) {
      var item = null
      for (var i = 0; i < this.tempStock.length; i++) {
        if (this.tempStock[i].id === id) {
          item = this.tempStock[i]
          var tot = item.price * this.quantity
          this.runningTotal += tot
          this.cart.push({ item: item.name, unitPrice: item.price, quantity: this.quantity, total: tot })
        }
      }
      this.quantity = 0
    },
    processCart: function () {
      for (var i = 0; i < this.cart.length; i++) {
        this.sale = this.cart[i]
        this.sale.salesperson = this.cashier.oName
        var tempItem = null
        for (var j = 0; j < this.tempStock.length; j++) {
          if (this.sale.item === this.tempStock[j].name) {
            tempItem = this.tempStock[j]
          }
        }
        tempItem.quantity -= parseFloat(this.sale.quantity)
        const formData = new FormData()
        formData.append('item', this.sale.item)
        formData.append('quantity', this.sale.quantity)
        formData.append('salesperson', this.sale.salesperson)
        formData.append('price', this.sale.total)
        axios({
          method: 'post',
          url: './api/Sales.php?action=create',
          data: formData,
          headers: { 'Content-Type': 'multipart/form-data' }
        }).then((response) => {
          this.$bvToast.toast(response.data.message, {
            title: 'Sale update',
            autoHideDelay: 1000,
            toaster: 'b-toaster-top-center',
            variant: 'primary'
          })
        })
        const formData2 = new FormData()
        formData2.append('id', tempItem.id)
        formData2.append('quantity', tempItem.quantity)
        axios({
          method: 'post',
          url: './api/Stock.php?action=update2',
          data: formData2,
          headers: { 'Content-Type': 'multipart/form-data' }
        }).then((res) => {
          this.$bvToast.toast(res.data.message, {
            title: 'Checkout update',
            autoHideDelay: 1000,
            toaster: 'b-toaster-top-center',
            variant: 'primary'
          })
        })
        this.successSales.items.push(this.sale)
        this.successSales.total += parseFloat(this.sale.total)
      }
      this.$store.dispatch('SET_RESPONSE', { error: false, message: 'Successful Sale' })
      this.cart.splice(0, this.cart.length)
      this.runningTotal = 0
      this.processReceipt()
    },
    processReceipt: function () {
      const h = this.$createElement
      const titleVNode = h('div', { class: ['text-center', 'w-100', 'inline-block', 'text-success'] }, [h('b-icon', { props: { icon: 'cart-check' }, class: ['centered'] }), h('h5', { class: ['text-underline'] }, 'Thank You')])
      const messageVNode = h('div', { class: ['text-center', 'w-100'] }, [
        h('p', { class: ['text-center'] }, [h('div', { class: ['table-responsive'] }, [h('b-table', {
          props: {
            items: this.successSales.items
          }
        })
        ]),
        h('p', { class: ['float-right', 'mt-0', 'pb-5'] }, [
          'Total: Ksh.',
          h('strong', this.successSales.total)
        ])
        ])
      ])
      this.$bvModal.msgBoxOk([messageVNode], {
        title: [titleVNode],
        size: 'sm',
        centered: true
      }).then(value => {
        if (value) {
          this.successSales.items.splice(0, this.successSales.items.length)
          this.successSales.total = 0
          this.$router.push({ name: 'Loader' })
        }
      })
    }
  },
  computed: {
    ...mapGetters({
      stock: 'DISPLAY_STOCK',
      tempStock: 'STOCK',
      staff: 'STAFF',
      user: 'CURRENT_USER'
    }),
    cashier: function () {
      var cash = null
      for (var i = 0; i < this.staff.length; i++) {
        if (this.user.email === this.staff[i].email) {
          cash = this.staff[i]
        }
      }
      return cash
    }
  },
  mounted: function () {
    console.log(this.stock)
    console.log(this.staff)
  }
}
</script>

<style scoped>
</style>
