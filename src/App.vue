<template>
  <b-container id="app" fluid>
    <b-row fluid align-v="stretch" align-h="start">
      <b-col cols="8">
        <h3 center>Items</h3>
        <b-card no-body class="w-100">
          <b-tabs pills card vertical active-nav-item-class="font-weight-bold text-uppercase">
            <b-tab v-for="(item, index1) in stock" :key="index1" :title="item.category">
              <b-row align-v="stretch">
                <b-col>
                  <h4>{{item.category}}</h4>
                  <b-tabs card content-class="mt-3" justified>
                    <b-tab v-for="(sub, index2) in item.subcat" :key="index2" :title="sub.subcategory" align-h="left">
                      <b-row align-h="left"><b-col v-for="(spec, index3) in sub.list" :key="index3">
                        <b-card :img-src="`./assets/${spec.img}.jpg`" :img-alt="spec.name" text-variant="black" style="max-width: 8rem; max-height: 8rem; margin-bottom: 10px;" img-left>
                          <b-card-text> {{ spec.name }} </b-card-text>
                          <b-button block variant="info" v-b-toggle="'collapse-info' + index3" @click="currentIndex.splice(0, currentIndex.length); currentIndex.push(index1, index2, index3); setCurrentItem()">Buy Item</b-button>
                        </b-card>
                      </b-col></b-row>
                      <b-row>
                        <b-col>                                                      <b-collapse :id="'collapse-info' + runningIndex">
                            <b-row>
                              <b-col class="w-50">
                                <b-img :src="require(`./assets/${currentItem.img}.jpeg`)" ></b-img>
                              </b-col>
                              <b-col class="w-50">
                                <p>In Stock: 100</p>
                                <p>Price: 500</p>
                                <b-button block variant="success" v-b-toggle.collapse-buy>Purchase</b-button>
                                <b-collapse id="collapse-buy">
                                  <b-form>
                                    <b-form-group>
                                      <label for="quantity">Units to Buy:</label><b-input id="quantity" type="number" v-model="quantity" placeholder="0"></b-input>
                                    </b-form-group>
                                    <b-button block @click="buyItem()" variant="warning">Add To Cart</b-button>
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
            <p>RunningIndex: {{runningIndex}}</p>
            <p>CurrentItem: {{currentItem}}</p>
            <p>CurrentIndex: {{currentIndex}}</p>
            <p>Quantity: {{quantity}}</p>
            <p>Cart: {{cart}}</p>
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
              { name: 'Red', img: 'redplasticgutter', price: 3000 },
              { name: 'Green', img: 'greenplasticgutter', price: 3000 }]
          },
          {
            subcategory: 'Metallic',
            list: [
              { name: 'Silver', img: 'silvermetalgutter', price: 1800 },
              { name: 'Black', img: 'blackmetalgutter', price: 1600 }]
          }]
        },
        {
          category: 'Decra',
          subcat: [
            {
              subcategory: 'Shingles',
              list: [
                { name: 'Black', img: 'blackshingles', price: 500 },
                { name: 'Green', img: 'greenshingles', price: 500 },
                { name: 'Coffee', img: 'coffeeshingles', price: 500 }]
            },
            {
              subcategory: 'Classic',
              list: [
                { name: 'Black', img: 'blackclassicdecra', price: 600 },
                { name: 'Green', img: 'greenclassicdecra', price: 600 },
                { name: 'Red', img: 'redclassicdecra', price: 600 }]
            }]
        },
        {
          category: 'Curtain Rods',
          subcat: [
            {
              subcategory: '2mm',
              list: [
                { name: 'Red', img: 'red2mmrods', price: 250 },
                { name: 'Grey', img: 'grey2mmrods', price: 250 }]
            },
            {
              subcategory: '3mm',
              list: [
                { name: 'Red', img: 'red3mmrods', price: 300 },
                { name: 'Grey', img: 'grey3mmrods', price: 300 }]
            }]
        }],
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
      this.quantity = 0
    },
    setCurrentItem: function () {
      this.currentItem = this.stock[this.currentIndex[0]].subcat[this.currentIndex[1]].list[this.currentIndex[2]]
      this.runningIndex = this.currentIndex[2]
    }
  },
  computed: {
    getImage: function (name) {
      return require(`./assets/${name}.jpeg`)
    }
  }
}
</script>

<style scoped>
</style>
