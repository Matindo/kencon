<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
    }
  }
}
</script>

<style scoped>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
#nav {

  text-align: center;
}
ul {
  list-style-type: dotted;
  padding: 0;
}
 li {
  display: inline-block;
  margin: 0 10;
}
</style>
