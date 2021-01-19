<template>
  <div>
    <Terminal v-if="currentUser.role === 'Cashier'" />
    <Admin v-if="currentUser.role === 'Admin'" />
    <b-button block variant="danger" @click="exit">Sign Out</b-button>
  </div>
</template>

<script>
import Admin from '@/components/Admin.vue'
import Terminal from '@/components/Terminal.vue'
import { mapGetters } from 'vuex'

export default {
  name: 'User',
  components: { Admin, Terminal },
  data: function () {
    return {
    }
  },
  computed: {
    ...mapGetters({
      currentUser: 'CURRENT_USER'
    })
  },
  methods: {
    exit: function () {
      this.$store.dispatch('RESET')
      this.$router.push({ name: 'Home' })
    }
  },
  mounted: function () {
    if (this.currentUser === null) {
      this.$router.push({ name: 'Home' })
    }
  }
}
</script>
