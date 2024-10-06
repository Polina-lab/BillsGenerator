<template>
  <div>
    <div class="menu-wrapper">
      <button class="dropbtn btn btn-outline-secondary" 
              :disabled="isMenuDisabled" 
              @click="showMenu = !showMenu">{{ buttonLabel }}</button>
      <div v-if="showMenu" class="dropdown-content">
        <nested-menu-item v-for="child in menu1stLvlElements" :key="child.id" :node="child" 
                          @selectedCategory="showMenu = false; buttonLabel = $event; $emit('selectedCategory', $event)"
                          class="menu-item top-lvl"/>
      </div>
    </div>
  </div>
</template>

<script>
import NestedMenuItem from "./NestedMenuItem.vue"

export default {
  name: "DropdownNestedMenu",
  props: [ "menu1stLvlElements", "isMenuDisabled" ],
  components: { NestedMenuItem },
  data() {
    return {
      showMenu: false,
      buttonLabel: 'Select a category'
    }
  }
}
</script>

<style lang="scss" scoped>
.menu-wrapper{
  position: relative;
  display: inline-block;
}

.dropdown-content {
  position: absolute;
  background-color: white;
  min-width: 200px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 9;
}
</style>