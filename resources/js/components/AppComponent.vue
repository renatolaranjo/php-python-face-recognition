<template>
  <div id="app">
   <Header />
    <div class="container mt-3">
      <router-view/>
    </div>
  </div>
</template>
<script>
import Header from './HeaderComponent.vue';
export default {
  components: {
    Header
  }
}
</script>
<style scoped>
.container {
  -webkit-box-shadow: 3px 3px 5px 6px #ccc;  /* Safari 3-4, iOS 4.0.2 - 4.2, Android 2.3+ */
  -moz-box-shadow:    3px 3px 5px 6px #ccc;  /* Firefox 3.5 - 3.6 */
  box-shadow:         3px 3px 5px 6px #ccc;  /* Opera 10.5, IE 9, Firefox 4+, Chrome 6+, iOS 5 */
  padding: 30px;
}
</style>