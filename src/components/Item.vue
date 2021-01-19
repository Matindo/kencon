<template>
  <b-card no-body>
    <b-card-header header-tag="header" class="p-1" role="tab">
      <b-row class="w-100">
        <b-col class="w-40">
          <img :src="item.img" style="height: 8rem; width: 8rem;">
        </b-col>
        <b-col class="w-60 accordion">
          <h4>{{ item.name }}</h4>
          <p>In Stock: {{ item.quantity }}</p>
          <b-button block v-b-modal="modal-add" variant="success">Add To Stock</b-button>
          <b-modal ref="modal" id="modal-add" @ok="handleAddOk" title="Add Stock">
            <form ref="form" @submit.stop.prevent="handleAddSubmit">
              <b-form-group label="Quantity" :state="Boolean(stockInfluence)" label-for="addition" invalid-feedback="Quantity is required">
                <b-form-input v-model="stockInfluence" id="addition" :state="Boolean(stockInfluence)" type="number" required></b-form-input>
              </b-form-group>
            </form>
          </b-modal>
          <b-button variant="danger" v-b-modal="modal-remove">Remove from Stock</b-button>
          <b-modal ref="modal" id="modal-remove" @ok="handleRemOk" title="Deduct Stock">
            <form ref="form" @submit.stop.prevent="handleRemSubmit">
              <b-form-group label="Quantity" :state="Boolean(stockInfluence)" label-for="removal" invalid-feedback="Quantity is required">
                <b-form-input v-model="stockInfluence" id="removal" :state="Boolean(stockInfluence)" type="number" required></b-form-input>
                <b-form-text v-show="stockInfluence > item.quantity">Your deduction is more than the available stock</b-form-text>
              </b-form-group>
            </form>                                                       </b-modal>
          <b-button v-b-toggle="'collapse-trend' + item.name" variant="info" @click="fetchItemSales">View Stock Trends</b-button>
        </b-col>
      </b-row>
    </b-card-header>
    <b-collapse :id="'collapse-trend' + item.name">
      <h5>{{item.name}} Sales</h5>
      <b-button class="float-right" variant="info" @click="fetchItemSales">Refresh Records</b-button>
      <b-table responsive stripped sticky-header :items="itemSales"></b-table>
    </b-collapse>
  </b-card>
</template>

<script>
import axios from 'axios'
import moment from 'moment'

export default {
  name: 'Item',
  props: {
    item: Object
  },
  data: function () {
    return {
      stockInfluence: 0,
      itemSales: []
    }
  },
  filters: {
    formatDate: function (value) {
      if (value) {
        return moment(String(value)).format('lll')
      }
    }
  },
  methods: {
    checkFormValidity: function () {
      return this.$refs.form.checkValidity()
    },
    handleAddOk: function (bvModalEvt) {
      bvModalEvt.preventDefault()
      this.handleSubmit()
    },
    handleAddSubmit: function () {
      if (!this.checkFormValidity) {
        return
      }
      this.addStock()
    },
    handleRemOk: function (bvModalEvt) {
      bvModalEvt.preventDefault()
      this.handleRemSubmit()
    },
    handleRemSubmit: function () {
      if (!this.checkFormValidity) {
        return
      }
      this.reduceStock()
    },
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
    fetchItemSales: function () {
      this.itemSales.splice(0, this.itemSales.length)
      var sales = this.$store.getters.ITEM_SALES(this.item.name)
      for (var i = 0; i < sales.length; i++) {
        sales[i].time = this.$options.filters.formatDate(sales[i].time)
      }
      this.itemSales = sales
    }
  }
}
</script>

<style scoped>
</style>
