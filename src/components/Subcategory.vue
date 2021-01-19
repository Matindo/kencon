<template>
  <b-tab :title="sub.subcategory">
    <Item v-for="(item, index) in sub.list" :item="item" :key="index" />
  </b-tab>
</template>

<script>
import Item from './Item.vue'

export default {
  name: 'Subcategory',
  props: {
    sub: Object
  },
  components: {
    Item
  }
}
</script>

<style sciped>
</style>
