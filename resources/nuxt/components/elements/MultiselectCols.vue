<template>
  <section>
    <h2 class="mb-0" v-if="cardName">{{cardName}}</h2>
    <div class="row">
      <div class="col-5">
        <label for="all">Not selected</label>
        <input type="text" id="all"
               class="form-control form-control-sm mb-2"
               v-model="itemNotSearch"
               placeholder="Search"
        />
        <select multiple="multiple"
                class="form-control form-control-sm multiselect-col"
                v-model="itemsNotSelected">
          <option :value="item.id" v-for="(item, i) in filteredNotItems" :key="i">{{item[nameField]}}</option>
        </select>
      </div>
      <div class="col-1 arrows">
        <button type="button"
                class="btn btn-secondary btn-sm mt-4"
                @click="addRelated"><i class="fas fa-chevron-right"></i></button>
        <br/>
        <button class="btn btn-secondary btn-sm"
                type="button"
                @click="delRelated"><i class="fas fa-chevron-left"></i></button>
      </div>
      <div class="col-5">
        <label for="selected">Selected</label>
        <input type="text" id="selected"
               placeholder="Search"
               class="form-control form-control-sm mb-2"
               v-model="itemNowSearch"
        />
        <select multiple="multiple"
                class="form-control form-control-sm multiselect-col"
                v-model="itemsNowSelected">
          <option :value="item.id" v-for="(item, i) in filteredNowItems" :key="i">{{item[nameField]}}</option>
        </select>

      </div>
    </div>

  </section>

</template>

<script>
  export default {
    name: "multiselectCols",
    props: ['items', 'selectedItems', 'cardName', 'moduleName', 'objName', 'keyName', 'nameField'],
    data() {
      return {
        itemNotSearch: '', //поиск по отсутствующим
        itemNowSearch: '', //поиск по присутствующим
        itemsNotSelect: this.items,
        itemsNotSelected: [], // выделенные в отсутствующих
        itemsNowSelect: [], //список присутствующих
        itemsNowSelected: [], //выделенные в присутствующих
      }
    },
    computed: {
      filteredNotItems() {
        return this.itemsNotSelect.filter(item => item.name.toLowerCase().includes(this.itemNotSearch))
      },
      filteredNowItems() {
        return this.itemsNowSelect.filter(item => item.name.toLowerCase().includes(this.itemNowSearch))
      },
    },
    created() {
      if (this.selectedItems) {
        this.itemsNowSelect = this.itemsNotSelect.filter(item => this.selectedItems.includes(item.id))
        this.itemsNotSelect = this.itemsNotSelect.filter(item => !this.selectedItems.includes(item.id))
      }

    },
    updated() {
      let val = []
      //извлекаем id объектов
      this.itemsNowSelect.forEach(x => {
        val.push(x.id)
      })
      //удаляем дубликаты
      val = [...new Set(val)]
      let moduleName = this.$props.moduleName
      let nameObj = this.$props.objName
      let key = this.$props.keyName

      this.$store.commit(`${moduleName}/SET_OBJ`, {name: this.$props.objName, key: this.$props.keyName, value: val})
    },
    methods: {
      addRelated() {
        let tmp = this.items.filter(item => this.itemsNotSelected.includes(item.id))
        for (let data of tmp) {
          this.itemsNowSelect.push(data)
        }
        this.itemsNotSelect = this.itemsNotSelect.filter(item => !this.itemsNotSelected.includes(item.id))

      },
      delRelated() {
        this.itemsNowSelect = this.itemsNowSelect.filter(item => !this.itemsNowSelected.includes(item.id))
        for (let data of this.itemsNowSelected) {
        }
        let tmp = this.items.filter(item => this.itemsNowSelected.includes(item.id))
        for (let data of tmp) {
          this.itemsNotSelect.push(data)
        }
        // this.catsNotSelect.sort((a,b) => (a.name > b.name) ? 1 : ((b.name > a.name) ? -1 : 0));
      },
    },
  }
</script>

<style scoped>

  .arrows {
    display: flex;
    flex-direction: column;
  }
  .multiselect-col {
    height: 150px!important;
  }
</style>
