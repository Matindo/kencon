<template>
  <b-container fluid>
    <b-row>
      <b-col w-100>
        <b-spinner variant="primary" centered></b-spinner>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Loader',
  computed: {
    ...mapGetters({
      response: 'RESPONSE'
    }),
    header: function () {
      if (this.response.isError) {
        return 'Error'
      } else {
        return 'Success'
      }
    },
    message: function () {
      const h = this.$createElement
      const messageVNode = h('div', { class: ['text-center'] }, [
        h('strong', this.header),
        h('p', { class: ['p-3'] }, [this.response.message])
      ])
      return messageVNode
    }
  },
  mounted: function () {
    if (this.header === 'Success') {
      this.$bvModal.msgBoxOk(this.message, {
        centered: true,
        variant: 'success'
      }).then(value => {
        this.$router.push({ name: 'User' })
      })
    } else {
      this.$router.push({ name: 'Home' })
    }
  }
}
</script>

<style scoped>
</style>
