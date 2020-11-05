<template>
  <b-container fluid>
    <b-row fluid align-v="stretch" align-h="start">
      <b-col cols="8">
        <h3 center>Items</h3>
        <b-card no-body class="w-100">
          <b-tabs pills card vertical active-nav-item-class="font-weight-bold text-uppercase">
            <b-tab v-for="(item, index1) in stock" :key="index1" :title="item.category">
              <b-row align-v="stretch">
                <b-col>
                  <h4>{{item.category}}</h4>
                  <b-tabs card content-class="mt-3" justified class="accordion">
                    <b-tab v-for="(sub, index2) in item.subcat" :key="index2" :title="sub.subcategory" align-h="start" @click="visible1 = false">
                      <b-row align-h="start" no-gutters cols-sm="2" cols-md="3" cols-lg="4">
                        <b-col class="mb-1 mr-1 accordion" id="my-accordion" role="tablist" v-for="(spec, index3) in sub.list" :key="index3" v-b-toggle="'collapse-info' + index3" @click="currentIndex.splice(0, currentIndex.length); currentIndex.push(index1, index2, index3); setCurrentItem()">
                          <b-card overlay :img-src="require(`../assets/${spec.img}.png`)" :img-alt="spec.name" style="margin-bottom: 10px; color: white" fluid>
                            <b-card-text align-self="end"> {{ spec.name }} </b-card-text>
                          </b-card>
                        </b-col>
                      </b-row>
                      <hr>
                      <b-row>
                        <b-col>
                          <b-collapse :id="'collapse-info' + runningIndex" accordion="my-accordion" role="tabpanel">
                            <b-row v-if="currentItem">
                              <b-col class="w-50">
                                <b-img :src="require(`../assets/${currentItem.img}.png`)" fluid></b-img>
                              </b-col>
                              <b-col class="w-50">
                                <p>In Stock: {{currentItem.quantity}}</p>
                                <p>Price: {{ currentItem.price }}</p>
                                <b-button block variant="success" v-b-toggle.collapse-buy>Purchase</b-button>
                                <b-collapse id="collapse-buy">
                                  <b-form>
                                    <b-form-group>
                                      <label for="quantity">Units to Buy:</label><b-input id="quantity" type="number" v-model="quantity" placeholder="0"></b-input>
                                    </b-form-group>
                                    <b-button block :class="visible? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="collapse-buy" @click="buyItem()" variant="warning">Add to Cart</b-button>
                                  </b-form>
                                </b-collapse>
                              </b-col>
                            </b-row>
                          </b-collapse>
                        </b-col>
                      </b-row>
                    </b-tab>
                  </b-tabs>
                </b-col>
              </b-row>
            </b-tab>
          </b-tabs>
        </b-card>
      </b-col>
      <b-col cols="4">
        <h3 center>Checkout</h3>
        <b-table responsive stripped sticky-header :items="cart"></b-table>
        <p center>Total: Ksh. {{ this.runningTotal }}</p>
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
      runningTotal: 0,
      currentIndex: [0, 0, 0],
      runningIndex: null,
      currentItem: null
    }
  },
  methods: {
    buyItem: function () {
      var tot = this.currentItem.price * this.quantity
      this.runningTotal += tot
      this.cart.push({ item: this.currentItem.name, unitPrice: this.currentItem.price, quantity: this.quantity, total: tot })
      this.tempStock[this.currentIndex[0]].subcat[this.currentIndex[1]].list[this.currentIndex[2]].quantity -= this.quantity
      this.$store.dispatch('UPDATE_STOCK', this.tempStock)
      this.quantity = 0
      this.tempStock = this.stock
    },
    setCurrentItem: function () {
      this.currentItem = this.stock[this.currentIndex[0]].subcat[this.currentIndex[1]].list[this.currentIndex[2]]
      this.runningIndex = this.currentIndex[2]
    }
  },
  computed: {
    stock: function () {
      return this.$store.getters.STOCK
    }
  },
  mounted: function () {
    this.tempStock = this.stock
  }
}
</script>

<style scoped>
</style>
