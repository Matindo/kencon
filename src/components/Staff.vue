<template>
  <b-card no-body>                                                  <b-tabs pills vertical card>                                      <Branch v-for="(branch, index) in staff" :key="index" :office="branch" />                                                      </b-tabs>
    <b-button variant="primary" block v-b-modal.modal-staff-add>Add Staff Member</b-button>
  <b-modal id="modal-staff-add" ref="modal" title="Add Staff Member" @ok="handleOk">
    <form ref="form" @submit="addStaff">
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
      <b-form-file v-model="staffImage" accept="image/*" :state="Boolean(staffImage)" placeholder="Choose file or drop it here..." drop-placeholder="Drop it here..."></b-form-file>
      <p>Selected file: {{ staffImage ? staffImage.name : '' }}</p>
    </form>
  </b-modal>
</b-card>
</template>

<script>
import Branch from './Branch.vue'
import axios from 'axios'

export default {
  name: 'Staff',
  components: {
    Branch
  },
  data: function () {
    return {
      staff: [],
      salesperson: null,
      staffImage: null
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
      this.addStaff()
    },
    addStaff: function () {
      const formData = new FormData()
      formData.append('idNo', this.salesperson.idNo)
      formData.append('fname', this.salesperson.fName)
      formData.append('oname', this.salesperson.oName)
      formData.append('address', this.salesperson.email)
      formData.append('phone', this.salesperson.phone)
      formData.append('office', this.salesperson.office)
      formData.append('status', this.salesperson.status)
      formData.append('img', this.staffImage)
      axios({ method: 'post', url: './api/Staff.php?action=create', data: formData, headers: { 'Content-Type': 'multipart/form-data' } }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
      })
      this.$router.push({ name: 'Loader' })
    }
  },
  mounted: function () {
    this.staff: this.$store.getters.DISPLAY_STAFF
  }
}
</script>

<style scoped>
</style>
