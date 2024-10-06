<template>
  <div class="search-input">
    <input type="text" class="form-control"
           v-model="queryStr"
           @input="searchForList"
           @change="selectedValue">
    <div class="search-input__clear"
         v-if="queryStr.length"
         @click="clearSearch">
      <i class="fas fa-times text-danger"></i></div>
    <div v-ouside-click="clearSearch" class="search-list" v-if="list.length">
      <div class="search-list__item" @click="selectItem"
           :data-value="item[value]"
           v-for="(item, idx) in list"
           :data-element_id="idx"
           :key="idx">
           {{ item.name ? item.name : item.full_address }}
      </div>
    </div>
  </div>
</template>

<script>
import { OutsideClick } from "../../assets/js/utils/OutsideClick.js";

export default {
  name: "InputSearch",
  props: ['dispatchModule', 'dispatchName', 'name', 'value'],
  directives:{ OutsideClick },
  data() {
    return {
      queryStr: '',
      list: [],
      selected: {}
    }
  },
  methods: {
    async searchForList() {
      if (this.queryStr.length >= 3) {
        const list = await this.$store.dispatch(
          `${this.dispatchModule}/${this.dispatchName}`, this.queryStr)
        if (Array.isArray(list)) this.list = list
      }
    },
    selectItem(e) {
      let selectedItem = this.list[e.target.dataset.element_id];
      if (selectedItem.name!==undefined) { // name is defined => this is client
            this.$emit('SelectedClient', selectedItem)
      } else if(selectedItem.full_address!==undefined){ // full address is defined => this is address
            this.$emit('SelectedAddress', selectedItem)
      }
      this.clearSearch();
    },
    clearSearch() {
      this.queryStr = ''
      this.list = []
    },
    selectedValue() {
      // console.log('emit up', this.selected)
    }
  }
}
</script>

<style scoped>
.search-input {
  position: relative;
}

.search-input__clear {
  position: absolute;
  color: #000000;
}

.search-input__clear {
  font-size: 18px;
  right: 18px;
  top: 7px;
  cursor: pointer;
}

.search-list {
  width: 100%;
  z-index: 100;
  position: absolute;
  border: 1px solid #80bdff;
  border-radius: 5px;
  outline: 0;
  overflow: hidden;
}

.search-list__item {
  background: #ffffff;
  transition: all .3s;
  padding: 5px 10px;
  color: #000000;
  border-bottom: 1px solid #80bdff;
  cursor: pointer;
}

.search-list__item:hover {
  background: #e0e0e0;
}

</style>
