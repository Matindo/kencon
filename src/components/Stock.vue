<template>
  <b-card no-body>
    <b-tabs vertical card pills>
      <Category v-for="(cat, index) in stock" :key="index" :cat="cat" />
    </b-tabs>
    <b-button variant="success" v-b-modal.modal-stock-add>Add New Item</b-button>
    <b-modal ref="modal" @ok="handleOk" id="modal-stock-add" title="Add New Item">
      <form ref="form" @submit="handleSubmit">
        <b-form-group label="Item Name" label-for="name" :state="Boolean(newItem.name)" invalid-feedback="Item Name is required">
          <b-form-input v-model="newItem.name" id="name" :state="Boolean(newItem.name)" required></b-form-input>
        </b-form-group>
        <b-form-group label="Quantity" label-for="quantity" :state="Boolean(newItem.quantity)" invalid-feedback="Quantity is required">
          <b-form-input v-model="newItem.quantity" id="quantity" :state="Boolean(newItem.quantity)" type="number" required></b-form-input>
        </b-form-group>
        <b-form-group label="Price" label-for="price" :state="Boolean(newItem.price)" invalid-feedback="Price is required">
          <b-form-input v-model="newItem.price" id="price" :state="Boolean(newItem.price)" type="number" required></b-form-input>
        </b-form-group>
        <b-form-group label-for="cat" label="Category" :state="Boolean(newItem.category)" invalid-feedback="Category is required">
          <b-form-input v-model="newItem.category" id="cat" :state="Boolean(newItem.category)" required></b-form-input>
        </b-form-group>
        <b-form-group label-for="subCat" label="Sub-category" :state="Boolean(newItem.subcategory)" invalid-feedback="Subcategory is required">
          <b-form-input v-model="newItem.subcategory" id="subCat" :state="Boolean(newItem.subcategory)" required></b-form-input>
        </b-form-group>
        <b-form-file v-model="itemImage" accept="image
/*" :state="Boolean(itemImage)" placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..." invalid-feedback="Image-file is required" required></b-form-file>
        <p>Selected file: {{ itemImage ? itemImage
.name : '' }}</p>
      </form>
    </b-modal>
  </b-card>
</template>

<script>
import axios from 'axios'
import Category from './Category.vue'

export default {
  name: 'Stock',
  props: ['stock'],
  components: {
    Category
  },
  data: function () {
    return {
      newItem: {},
      itemImage: null
    }
  },
  methods: {
    checkFormValidity: function () {
      return this.$refs.form.checkValidity()
    },
    handleOk: function (bvModalEvt) {
      bvModalEvt.preventDefault()
      this.handleSubmit()
    },
    handleSubmit: function () {
      if (!this.checkFormValidity) {
        return
      }
      this.addNewitem()
    },
    addNewItem: function () {
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
        data: formData
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.newItem = null
      })
      this.$router.push({ name: 'Loader' })
    }
  }
}
</script>

<style scope>
</style>
