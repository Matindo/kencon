<template>
  <b-tab :title="cat.category">
    <b-card-text>
      <b-tabs content-class="mt-1" justified>
        <Subcategory v-for="(sub, index) in cat.subcat
" :key="index" :sub="sub" />
      </b-tabs>
    </b-card-text>
  </b-tab>
</template>

<script>
import Subcategory from './Subcategory.vue'

export default {
  name: 'Category',
  props: {
    cat: Object
  },
  components: {
    Subcategory
  }
}
</script>

<style scoped>
</style>
