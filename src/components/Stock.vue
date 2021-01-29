<template>
  <b-card no-body>
    <b-tabs vertical card pills>
      <Category v-for="(cat, index) in stock" :key="index" :cat="cat" />
    </b-tabs>
    <b-button block variant="success" v-b-toggle.collapse-stock-add>Add New Item</b-button>
    <b-collapse id="collapse-stock-add">
      <b-form>
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
        <p>Selected file: {{ itemImage ? itemImage.name : '' }}</p>
      </b-form>
      <div class="text-center mt-3">
        <b-button variant="secondary" class="mr-3" v-b-toggle.collapse-stock-add>Cancel</b-button>
        <b-button variant="primary" class="ml-3" @click="addNewItem">Add New Item</b-button>
      </div>
    </b-collapse>
  </b-card>
</template>

<script>
import Category from './Category.vue'
import axios from 'axios'

export default {
  name: 'Stock',
  props: ['stock'],
  components: {
    Category
  },
  data: function () {
    return {
      itemImage: null,
      newItem: null
    }
  },
  methods: {
    addNewItem: function () {
      const formData = new FormData()
      formData.append('idNo', this.newStaff.idNo)
      formData.append('fname', this.newStaff.fName)
      formData.append('oname', this.newStaff.oName)
      formData.append('address', this.newStaff.email)
      formData.append('phone', this.newStaff.phone)
      formData.append('office', this.newStaff.office)
      formData.append('status', this.newStaff.status)
      formData.append('img', this.staffImage)
      axios({
        method: 'post',
        url: './api/Staff.php?action=create',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.newItem = null
      })
      this.$router.push({ name: 'Loader' })
    }
  },
  mounted: function () {
    this.loadStock()
  }
}
</script>

<style scope>
</style>
