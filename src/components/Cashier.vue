<template>
  <b-card>
    <b-card-header header-class="header">
      <b-container fluid>
        <b-row>
          <b-col class="w-40">
            <img :src="salesperson.img" style="height: 8rem; width: 8rem;">
          </b-col>
          <b-col class="w-60">
            <h5>{{salesperson.fName}} {{salesperson.oName}}</h5>
            <p>Work Status: {{ salesperson.status }}</p>
            <b-button v-b-modal="modal-edit" variant="primary">Edit Staff Details</b-button>
            <b-button variant="danger" @click="deleteStaff">Delete Staff</b-button>
            <b-button block v-b-toggle="'accordion' + salesperson.idNo" variant="secondary" @click="fetchRecords">View Sales Records</b-button>
          </b-col>
        </b-row>
      </b-container>
    </b-card-header>
    <b-collapse :id="'accordion' + salesperson.idNo">
      <h5>Employee {{ salesperson.num }}'s Sales</h5>
      <b-button class="float-right" variant="primary" @click="fetchRecords">Refresh Records</b-button>
      <b-table responsive stripped sticky-header :items="staffSales"></b-table>
    </b-collapse>
    <b-modal id="modal-edit" ref="modal" title="Edit Staff Details" @ok="handleOk">
      <form ref="form" @submit="handleSubmit">
        <b-form-group label="First Name" label-for="1name" invalid-feedback="First Name is required" :state="Boolean(salesperson.fName)">
          <b-form-input v-model="salesperson.fName" :state="Boolean(salesperson.fName)" id="1name" required></b-form-input>
        </b-form-group>
        <b-form-group label="Other Name(s):" label-for="2name" :state="Boolean(salesperson.oName)" invalid-feedback="Other Name(s) are required">
          <b-form-input v-model="salesperson.oName" id="2name" :state="Boolean(salesperson.oName)" required></b-form-input>
        </b-form-group>
        <b-form-group label="ID" :state="Boolean(salesperson.idNo)" label-for="idn" invalid-feedback="ID is required">
          <b-form-input v-model="salesperson.idNo" id="idn" :state="Boolean(salesperson.idNo)" required></b-form-input>
        </b-form-group>
        <b-form-group label="E-mail" :state="Boolean(salesperson.email)" label-for="mail" invalid-feedback="Valid e-mail is required">
          <b-form-input v-model="salesperson.email" id="mail" :state="Boolean(salesperson.email)" type="e-mail" required></b-form-input>
        </b-form-group>
        <b-form-group label="Mobile No." :state="Boolean(salesperson.phone)" label-for="phone" invalid-feedback=" Valid mobile number is required">
          <b-form-input v-model="salesperson.phone" id="phone" :state="Boolean(salesperson.phone)" type="telephone" required></b-form-input>
        </b-form-group>
        <b-form-group label="Work Status" :state="Boolean(salesperson.status)" label-for="status" invalid-feedback="Work Status is required">
          <b-form-select v-model="salesperson.status" id="status" :options="statusOptions" :state="Boolean(salesperson.status)"></b-form-select>
        </b-form-group>
        <b-form-group label="Office" :state="Boolean(salesperson.office)" label-for="office" invalid-feedback="Office is required">
          <b-form-input v-model="salesperson.office" id="office" :state="Boolean(salesperson.office)" required></b-form-input>
        </b-form-group>
      </form>
    </b-modal>
  </b-card>
</template>

<script>
import axios from 'axios'
import moment from 'moment'

export default {
  name: 'Cashier',
  props: ['salesperson'],
  data: function () {
    return {
      staffSales: null
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
    handleOk: function (bvModalEvt) {
      bvModalEvt.preventDefault()
      this.handleSubmit()
    },
    handleSubmit: function () {
      if (!this.checkFormValidity) {
        return
      }
      this.editSalesperson()
    },
    editSalesperson: function () {
      const formData = new FormData()
      formData.append('id', this.salesperson.num)
      formData.append('idNo', this.salesperson.idNo)
      formData.append('fname', this.salesperson.fName)
      formData.append('oname', this.salesperson.oName)
      formData.append('address', this.salesperson.email)
      formData.append('phone', this.salesperson.phone)
      formData.append('office', this.salesperson.office)
      formData.append('status', this.salesperson.status)
      axios({
        method: 'post',
        url: './api/Staff.php?action=update',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.$store.dispatch('LOAD_STAFF')
      })
      this.$store.dispatch('SET_ROUTE', 'User')
      this.$router.push({ name: 'Loader' })
    },
    fetchRecords: function () {
      this.staffSales = null
      var sales = this.$store.getters.STAFF_SALES(this.salesperson.fName)
      for (var i = 0; i < sales.length; i++) {
        sales[i].time = this.$options.filters.formatDate(sales[i].time)
      }
      this.staffSales = sales
    },
    deleteStaff: function () {
      this.$bvModal.msgBoxConfirm('Are you sure you want to delete ' + this.salesperson.fName + ' permanently?', {
        title: 'Delete Staff Member',
        okVariant: 'danger',
        okTitle: 'YES',
        cancelTitle: 'NO',
        footerClass: 'p-2 center text-center',
        centered: true
      }).then(value => {
        if (value) {
          const formData = new FormData()
          formData.append('id', this.salesperson.id)
          axios({
            method: 'post',
            url: './api/Staff.php?action=delete',
            data: formData
          }).then((response) => {
            this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
            this.$router.push({ name: 'Loader' })
          })
        }
      })
    }
  }
}
</script>

<style scoped>
</style>
