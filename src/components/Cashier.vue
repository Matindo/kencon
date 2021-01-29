<template>
  <b-card>
    <b-card-header header-class="header">
      <b-container fluid>
        <b-row>
          <b-col class="w-40">
            <img :src="member.img" style="height: 8rem; width: 8rem;">
          </b-col>
          <b-col class="w-60">
            <h5>{{member.fName}} {{member.oName}}</h5>
            <p>Work Status: {{ member.status }}</p>
            <b-button class="m-1" variant="primary" v-b-modal="'modalEdit' + member.num" @click="setSp(member)">Edit Staff Details</b-button>
            <b-modal :id="'modalEdit' + member.num" title="Edit Staff Details" hide-footer>
              <b-form>
                <b-form-group label="First Name" label-for="1name" invalid-feedback="First Name is required" :state="Boolean(sp.fName)">
                  <b-form-input v-model="sp.fName" :state="Boolean(sp.fName)" id="1name" required></b-form-input>
                </b-form-group>
                <b-form-group label="Other Name(s):" label-for="2name" :state="Boolean(sp.oName)" invalid-feedback="Other Name(s) are required">
                  <b-form-input v-model="sp.oName" id="2name" :state="Boolean(sp.oName)" required></b-form-input>
                </b-form-group>
                <b-form-group label="ID" :state="Boolean(sp.idNo)" label-for="idn" invalid-feedback="ID is required">
                  <b-form-input v-model="sp.idNo" id="idn" :state="Boolean(sp.idNo)" required></b-form-input>
                </b-form-group>
                <b-form-group label="E-mail" :state="Boolean(sp.email)" label-for="mail" invalid-feedback="Valid e-mail is required">
                  <b-form-input v-model="sp.email" id="mail" :state="Boolean(sp.email)" type="e-mail" required></b-form-input>
                </b-form-group>
                <b-form-group label="Mobile No." :state="Boolean(sp.phone)" label-for="phone" invalid-feedback=" Valid mobile number is required">
                  <b-form-input v-model="sp.phone" id="phone" :state="Boolean(sp.phone)" type="telephone" required></b-form-input>
                </b-form-group>
                <b-form-group label="Work Status" :state="Boolean(sp.status)" label-for="status" invalid-feedback="Work Status is required">
                  <b-form-select v-model="sp.status" id="status" :options="statusOptions" :state="Boolean(sp.status)"></b-form-select>
                </b-form-group>
                <b-form-group label="Office" :state="Boolean(sp.office)" label-for="office" invalid-feedback="Office is required">
                  <b-form-input v-model="sp.office" id="office" :state="Boolean(sp.office)" required></b-form-input>
                </b-form-group>
              </b-form>
              <div class="modal-footer">
                <b-button variant="secondary" class="m-1" @click="$bvModal.hide('modalEdit' + member.num)">Cancel</b-button>
                <b-button variant="primary" @click="editStaff">Edit Details</b-button>
              </div>
            </b-modal>
            <b-button class="m-1" variant="danger" @click="deleteStaff(member)">Delete Staff</b-button>
            <b-button block v-b-toggle="'accordion' + member.idNo" variant="secondary" @click="fetchRecords(member)">View Sales Records</b-button>
          </b-col>
        </b-row>
      </b-container>
    </b-card-header>
    <b-collapse :id="'accordion' + member.idNo">
      <h5>{{ member.oName }}'s Sales</h5>
      <b-button class="float-right" variant="primary" @click="fetchRecords(member)">Refresh Records</b-button>
      <b-table responsive stripped sticky-header :items="staffSales"></b-table>
    </b-collapse>
  </b-card>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Cashier',
  props: ['member'],
  data: function () {
    return {
      staffSales: [],
      sp: null
    }
  },
  methods: {
    setSp: function (sp) {
      this.sp = sp
    },
    editStaff: function () {
      const formData = new FormData()
      formData.append('id', this.sp.num)
      formData.append('idNo', this.sp.idNo)
      formData.append('fname', this.sp.fName)
      formData.append('oname', this.sp.oName)
      formData.append('address', this.sp.email)
      formData.append('phone', this.sp.phone)
      formData.append('office', this.sp.office)
      formData.append('status', this.sp.status)
      axios({
        method: 'post',
        url: './api/Staff.php?action=update',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then((response) => {
        this.$store.dispatch('SET_RESPONSE', { error: response.data.error, message: response.data.message })
        this.sp = null
      })
      this.$router.push({ name: 'Loader' })
    },
    fetchRecords: function (sp) {
      this.staffSales.splice(0, this.staffSales.length)
      this.staffSales = this.$store.getters.STAFF_SALES(sp.oName)
    },
    deleteStaff: function (sp) {
      this.$bvModal.msgBoxConfirm('Are you sure you want to delete ' + sp.oName + ' permanently?', {
        title: 'Delete Staff Member',
        okVariant: 'danger',
        okTitle: 'YES',
        cancelTitle: 'NO',
        footerClass: 'p-2 center text-center',
        centered: true
      }).then(value => {
        if (value) {
          const formData = new FormData()
          formData.append('id', sp.num)
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
