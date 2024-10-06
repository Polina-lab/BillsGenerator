<template>
  <div class="breadcrumbs">
    <n-link to="/">Home</n-link>
    <template v-for="(item, idx) in $store.state.breadcrumbs">
      &nbsp;/&nbsp;
      <n-link :to="item.url" v-if="item.url"
              :key="idx">{{item.name}}
      </n-link>
      <span v-else>{{item.name}}</span>
    </template>
  </div>
</template>

<script>
  export default {
    name: "Breadcrumbs",
  }
</script>

<style scoped lang="scss">
  @import '/assets/scss/var';

  .breadcrumbs {
    display: flex;
    align-items: flex-end;
    width: 100%;
    padding: 5px 25px;
    background: #ffffff;
    font-size: 13px;
  }

  i {
    font-size: 19px;
    margin-right: 25px;
    cursor: pointer;
    transition: 0.5s;
  }

  i.active {
    transform: rotate(180deg)
  }

</style>
