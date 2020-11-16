<template>
  <b-container fluid>
    <b-row fluid align-v="stretch" align-h="start">
      <b-col cols="8">
        <h3 center>Items</h3>
        <b-card no-body class="w-100">
          <b-tabs pills card vertical active-nav-item-class="font-weight-bold text-uppercase">
            <b-tab v-for="(item, index1) in stock" :key="index1" :title="item.category">
              <b-card no-body class="w-100">
                <h4>{{item.category}}</h4>
                <b-tabs card content-class="mt-3" justified>
                  <b-tab v-for="(sub, index2) in item.subcat" :key="index2" :title="sub.subcategory">
                    <b-card-text>
                      <b-card class="accordion" id="my-accordion" role="tablist" v-for="(spec, index3) in sub.list" :key="index3">
                        <b-card-header header-class="header">
                          <b-row>
                            <b-col class="w-40">
                              <img :src="require(`../assets/${spec.img}.png`)" :img-alt="spec.name" style="height: 8rem; width: 8rem;">
                            </b-col>
                            <b-col class="w-60">
                              <h5>{{spec.name}}</h5>
                              <p>In Stock: {{spec.quantity}}<br>Price: {{spec.price}}</p>
                              <b-button block variant="warning" v-b-toggle.collapse-buy>Purchase</b-button>
                              <b-collapse id="collapse-buy">
                                <b-form>
                                  <b-form-group>
                                    <label for="quantity">Units to Buy:</label><b-input id="quantity" type="number" v-model="quantity" placeholder="0"></b-input>
                                    <b-form-text v-show="quantity > spec.quantity">Your order is more than remaining stock quantity</b-form-text>
                                  </b-form-group>
                                  <b-button block v-if="quantity <= spec.quantity" :class="visible? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="collapse-buy" @click="buyItem(spec.id)" variant="success">Add to Cart</b-button>
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
      <b-col cols="4">
        <h3 center>Checkout</h3>
        <b-table responsive stripped sticky-header :items="cart"></b-table>
        <p center>Total: Ksh. {{ this.runningTotal }}</p>
        <b-button block variant="warning" @click="processCart()">CHECK-OUT</b-button>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  data: function () {
    return {
      cart: [],
      tempStock: [],
      quantity: 0,
      runningTotal: 0
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
          this.tempStock[i].quantity -= this.quantity
        }
      }
      this.quantity = 0
    },
    processCart: function () {
      this.$store.dispatch('ADD_SALE', this.cart)
      this.$store.dispatch('UPDATE_STOCK', this.tempStock)
      this.tempStock = this.$store.getters.STOCK
      this.cart.splice(0, this.cart.length)
      // print receipt
    }
  },
  computed: {
    stock: function () {
      return this.$store.getters.DISPLAY_STOCK
    }
  },
  mounted: function () {
    this.tempStock = this.$store.getters.STOCK
  }
}
</script>

<style scoped>
</style>
