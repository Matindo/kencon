<template>
  <b-container fluid>
    <b-row fluid align-v="stretch" align-h="start">
      <b-col cols="8">
        <h3 center>Items</h3>
        <b-card no-body class="w-100">
          <b-tabs pills card vertical active-nav-item-class="font-weight-bold text-uppercase">
            <b-tab v-for="(item, index) in stock" :key="index" :title="item.category">
              <b-row align-v="stretch">
                <b-col>
                  <b-tabs content-class="mt-3" justified>
                    <b-tab v-for="(sub, index) in item.subcat" :key="index" :title="sub.subcategory">
                      <b-row align-h="start">
                        <b-col v-for="(spec, index) in sub.list" :key="index">
                          <b-card overlay :img-src="spec.img" :img-alt="spec.img" text-variant="black" class="mb-2" style="width: 5rem; height: 5rem"  @click="selectItem()">
                            <b-card-text> {{ spec.name }} </b-card-text>
                            </b-card>
                        </b-col>
                      </b-row>
                    </b-tab>
                  </b-tabs>
                </b-col>
                <div class="w-100"></div>
                <b-col>
                  <b-row fluid>
                    <b-col class="w-50">
                      <img :src="getImage(spec.img)"></img>
                    </b-col>
                    <b-col class="w-50">
                      <p>In Stock: {{ spec.quantity }}</p>
                      <p>Price: Ksh. {{ spec.price }}</p>
                      <b-button block variant="success" v-b-toggle.collapse-buy>Purchase</b-button>
                      <b-collapse id="collapse-buy">
                        <b-form>
                          <b-form-group>
                            <label for="quantity">Units to Buy:</label><b-input id="quantity" type="number" v-model="quantity" placeholder="0"></b-input>
                          </b-form-group>
                          <b-button :class="visible ? null : 'collapsed'" :aria-expanded="visible ? 'true' : 'false'" aria-controls="collapse-buy" @click="buyItem(index, spec.name, spec.price)">Add To Cart</b-button> 
                        </b-form>
                      </b-collapse>
                    </b-col>
                  </b-row>
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
      cart: [
      ],
      stock: [
        {
          category: 'Gutters',
          subcat: [{
            subcategory: 'Plastic',
            list: [
              { name: 'Red', img: 'redplasticgutter.jpeg', price: 3000 },
              { name: 'Green', img: 'greenplasticgutter.jpeg', price: 3000 }]
          },
          {
            subcategory: 'Metallic',
            list: [
              { name: 'Silver', img: 'silvermetalgutter.jpeg', price: 1800 },
              { name: 'Black', img: 'blackmetalgutter.jpeg', price: 1600 }]
          }]
        },
        {
          category: 'Decra',
          subcat: [
            {
              subcategory: 'Shingles',
              list: [
                { name: 'Black', img: 'blackshingles.jpeg', price: 500 },
                { name: 'Green', img: 'greenshingles.jpeg', price: 500 },
                { name: 'Coffee', img: 'coffeeshingles.jpeg', price: 500 }]
            },
            {
              subcategory: 'Classic',
              list: [
                { name: 'Black', img: 'blackclassicdecra.jpeg', price: 600 },
                { name: 'Green', img: 'greenclassicdecra.jpeg', price: 600 },
                { name: 'Red', img: 'redclassicdecra.jpeg', price: 600 }]
            }]
        },
        {
          category: 'Curtain Rods',
          subcat: [
            {
              subcategory: '2mm',
              list: [
                { name: 'Red', img: 'red2mmrods.jpeg', price: 250 },
                { name: 'Grey', img: 'grey2mmrods.jpeg', price: 250 }]
            },
            {
              subcategory: '3mm',
              list: [
                { name: 'Red', img: 'red3mmrods.jpeg', price: 300 },
                { name: 'Grey', img: 'grey3mmrods.jpeg', price: 300 }]
            }]
        }],
      quantity: 0,
      runningTotal: 0
    }
  },
  methods: {
    buyItem: function (index, name, price) {
      var tot = price * this.quantity
      this.runningTotal += tot
      this.cart.push({ item: name, unitPrice: price, quantity: this.quantity, total: tot })
      this.quantity = 0
    }
  },
  computed: {
    getImage: function (img) {
      return require('../assets/' + img)
    }
  }
}
</script>

<style scoped>
</style>
