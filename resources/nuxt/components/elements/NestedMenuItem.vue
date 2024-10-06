<template>
  <div>
    <div class="row justify-content-center p-2">
      <div class="col-9 btn btn-action" @click="$emit('selectedCategory', node.name)">{{ node.name }}</div>
      <button v-if="node.children.length > 0" class="btn btn-action col-2 pl-1" @click="isChildrenVisible = !isChildrenVisible">
        <i class="fas fa-chevron-right"></i>
      </button>
      <div v-if="isChildrenVisible" class="col-12">
        <NestedMenuItem v-for="child in node.children" :key="child.id" :node="child" @selectedCategory="$emit('selectedCategory', $event)"
                        class="menu-item nested-lvl"/>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NestedMenuItem",
  props: ["node"],
  data () {
    return {
      isChildrenVisible: false
    }
  }
}
</script>

<style>

</style>