<template>
  <b-tab :title="sub.subcategory">
    <Item v-for="(item, index3) in sub.list" :item="item" :key="index3" />
  </b-tab>
</template>

<script>
import Item from './Item.vue'

export default {
  name: 'Subcategory',
  props: ['sub'],
  components: {
    Item
  }
}
</script>

<style sciped>
</style>
