<template>
  <b-card no-body>
    <b-card-header header-tag="header" class="p-1" role="tab">
      <b-row class="w-100">
        <b-col class="w-40">
          <img :src="item.img" style="height: 8rem; width: 8rem;">
        </b-col>
        <b-col class="w-60">
          <h4>{{ item.name }}</h4>
          <p>In Stock: {{ item.quantity }}</p>
          <b-button v-b-modal="'modal-add' + item.name" variant="success">Add To Stock</b-button>
          <b-modal :id="'modal-add' + item.name" hide-header hide-footer>
            <b-form>
              <b-form-group label="Quantity To Add:" :state="Boolean(stockInfluence)" label-for="addition" invalid-feedback="Quantity is required">
                <b-form-input v-model="stockInfluence" id="addition" :state="Boolean(stockInfluence)" type="number" required></b-form-input>
              </b-form-group>
            </b-form>
            <div class="text-center mt-2">
              <b-button variant="secondary" class="m-3" @click="$bvModal.hide('modal-add' + item.name)">Cancel</b-button>
              <b-button variant="primary" class="m-3" @click="addStock">Add Stock</b-button>
            </div>
          </b-modal>
          <b-button variant="danger" v-b-modal="'modal-rem' + item.name">Reduce Stock</b-button>
          <b-modal :id="'modal-rem' + item.name" hide-header hide-footer>
            <b-form>
              <b-form-group label="Quantity To Deduct" :state="Boolean(stockInfluence)" label-for="removal" invalid-feedback="Quantity is required">
                <b-form-input v-model="stockInfluence" id="removal" :state="Boolean(stockInfluence)" type="number" required></b-form-input>
                <b-form-text v-show="stockInfluence > item.quantity">Your deduction is more than the available stock</b-form-text>
              </b-form-group>
            </b-form>
            <div class="text-center mt-2">
              <b-button variant="secondary" class="m-3" @click="$bvModal.hide('modal-rem' + item.name)">Cancel</b-button>
              <b-button variant="primary" class="m-3" @click="reduceStock" :disabled="stockInfluence > item.quantity">Remove From Stock</b-button>
            </div>
          </b-modal>
          <b-button block variant="secondary" v-b-modal="'modalEdit' + item.name">Edit Item</b-button>
          <b-modal :id="'modalEdit' + item.name" title="Edit Item Details" hide-footer>
            <b-form>
              <b-form-group label="Item Name" label-for="name" :state="Boolean(item.name)" invalid-feedback="Item Name is required">
                <b-form-input v-model="item.name" id="name" :state="Boolean(item.name)" required></b-form-input>
              </b-form-group>
              <b-form-group label="Quantity" label-for="quantity" :state="Boolean(item.quantity)" invalid-feedback="Quantity is required">
                <b-form-input v-model="item.quantity" id="quantity" :state="Boolean(item.quantity)" type="number" required></b-form-input>
              </b-form-group>
              <b-form-group label="Price" label-for="price" :state="Boolean(item.price)" invalid-feedback="Price is required">
                <b-form-input v-model="item.price" id="price" :state="Boolean(item.price)" type="number" required></b-form-input>
              </b-form-group>
              <b-form-group label-for="cat" label="Category" :state="Boolean(item.category)" invalid-feedback="Category is required">
                <b-form-input v-model="item.category" id="cat" :state="Boolean(item.category)" required></b-form-input>
              </b-form-group>
              <b-form-group label-for="subCat" label="Sub-category" :state="Boolean(item.subcategory)" invalid-feedback="Subcategory is required">
                <b-form-input v-model="item.subcategory" id="subCat" :state="Boolean(item.subcategory)" required></b-form-input>
              </b-form-group>
            </b-form>
            <div class="text-center mt-2">
              <b-button variant="secondary" class="m-3" @click="$bvModal.hide('modalEdit' + item.name)">Cancel</b-button>
              <b-button variant="primary" class="m-3" @click="editItem">Edit Item</b-button>
            </div>
          </b-modal>
          <b-button block v-b-toggle="'collapse-trend' + item.name" variant="info" @click="fetchItemSales(item)">View Stock Trends</b-button>
        </b-col>
      </b-row>
    </b-card-header>
    <b-collapse :id="'collapse-trend' + item.name">
      <h5>{{item.name}} Sales</h5>
      <b-button class="float-right" variant="info" @click="fetchItemSales(item)">Refresh Records</b-button>
      <b-table responsive stripped sticky-header :items="itemSales"></b-table>
    </b-collapse>
  </b-card>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Item',
  props: ['item'],
  data: function () {
    return {
      itemSales: [],
      stockInfluence: 0
    }
  },
  methods: {
    editItem: function () {
      const formData = new FormData()
      formData.append('id', this.item.id)
      formData.append('name', this.item.name)
      formData.append('quantity', this.item.quantity)
      formData.append('price', this.item.price)
      formData.append('category', this.item.category)
      formData.append('subcategory', this.item.subcategory)
      axios({
        method: 'post',
        url: './api/Stock.php?action=update',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
      })
      this.$router.push({ name: 'Loader' })
    },
    addStock: function () {
      this.item.quantity += parseFloat(this.stockInfluence)
      const formData = new FormData()
      formData.append('id', this.item.id)
      formData.append('quantity', this.item.quantity)
      axios({
        method: 'post',
        url: './api/Stock.php?action=update2',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.stockInfluence = 0
        this.$bvModal.hide('my-modal')
      })
      this.$router.push({ name: 'Loader' })
    },
    reduceStock: function () {
      this.item.quantity -= parseFloat(this.stockInfluence)
      const formData = new FormData()
      formData.append('id', this.item.id)
      formData.append('quantity', this.item.quantity)
      axios({
        method: 'post',
        url: './api/Stock.php?action=update2',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.stockInfluence = 0
      })
      this.$router.push({ name: 'Loader' })
    },
    fetchItemSales: function (item) {
      this.itemSales.splice(0, this.itemSales.length)
      this.itemSales = this.$store.getters.ITEM_SALES(item.name)
    }
  }
}
</script>

<style scoped>
</style>
