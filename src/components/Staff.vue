<template>
  <b-card no-body>                                                  <b-tabs pills vertical card>                                      <b-tab v-for="(office, index) in staff" :key="index" :title="office.office">
        <b-card-text>
          <Cashier v-for="(worker, index2) in office.employees" :key="index2" :member="worker" />
        </b-card-text>
      </b-tab>
    </b-tabs>
    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalStaffAdd">Add Staff Member</button>
    <!-- Modal -->
    <div class="modal fade" id="modalStaffAdd" tabindex="-1" role="dialog" aria-labelledby="modalStaffAdd" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Add New Staff</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <b-form>
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
            </b-form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <b-button variant="primary" @click="addStaff">Add Staff</b-button>
          </div>
        </div>
      </div>
    </div>
  </b-card>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  name: 'Staff',
  data: function () {
    return {
      salesperson: null,
      staffImage: null,
      statusOptions: [{ value: null, text: 'Please select status' }, { value: 'On Duty', text: 'On Duty' }, { value: 'Off Duty', text: 'Off Duty' }]
    }
  },
  computed: {
    ...mapGetters({
      staff: 'STAFF'
    })
  },
  methods: {
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
      axios({
        method: 'post',
        url: './api/Staff.php?action=create',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.salesperson = null
      })
      this.$router.push({ name: 'Loader' })
    }
  },
  mounted: function () {
    this.loadStaff()
  }
}
</script>

<style scoped>
</style>
