<template>
  <div class="container-fluid">
    <div class="row">
      <div class="justify-content-align justify-content-center">
        {{ code }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "error",
  props:['code' , "msg"],
  layout:"login"
}
</script>

<style scoped>

</style>
