<template>
  <div>
    <div class="trans-header">
      <h1>Translations</h1>
      <div class="trans-header__action">
        <!--надо для добавления группы -->
       <div class="mr-2" title="Add Group"
               @click="addGroupModal = !addGroupModal">
              <i class="fas fa-plus"></i> GROUP
       </div>

        <div class="mr-2" title="Add key"
             @click="addKeyModal = true; newKey.group = translateFilter.group">
          <i class="fas fa-plus"></i> KEY
        </div>
        <div  title="Export values to JSON file"
             @click="exportAll">
          <i class="fas fa-file-export"></i> EXPORT
        </div>

        <div  title="Export values to *.php file"
              @click="exportBackend">
          <i class="fas fa-file-export"></i> RENEW BACKEND
        </div>

      </div>
    </div>
    <!--    SEARCH-->
    <div class="search">
      <input type="text"
             class="form-control mr-2"
             placeholder="Search"
             v-model="translateFilter.search"
             @input="changeTranslateFilter('search', $event.target.value)"
      >
      <select class="form-control select-trans mr-2"
              v-model="translateFilter.group"
              @change="changeTranslateFilter('group', $event.target.value)">
        <option value="">All</option>
        <option :value="lang"
                v-for="(lang, idx) in $store.state.lang.translateGroups"
                :key="idx">{{lang}}
        </option>
      </select>
      <div class="input-group-append">
        <button class="btn btn-success"
                @click="changeTranslateFilter('search', translateFilter.search)">Search
        </button>
      </div>
    </div>
    <!-- TABLE -->
    <div class="table-responsive mt-4 tableFixHead" v-if="translateList.data && translateList.data.length">
      <table class="table table-hover">
        <thead>
        <tr class="table-header">
          <th>Key</th>
          <th>Group</th>
          <th>Estonian</th>
          <th>English</th>
          <th>Russian</th>
          <th>Del</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, idx) in translateList.data"
            :key="idx">
          <td>
            {{item.key}}
          </td>
          <td>{{item.group}}</td>
          <td>
            <input type="text"
                   class="form-control form-control-sm"
                   :value="item.ee"
                   @change="changeValue('ee', item.group, item.key, $event.target.value)">
          </td>
          <td>
            <input type="text"
                   class="form-control form-control-sm"
                   :value="item.en"
                   @change="changeValue('en', item.group, item.key, $event.target.value)">
          </td>
          <td>
            <input type="text"
                   class="form-control form-control-sm"
                   :value="item.ru"
                   @change="changeValue('ru', item.group, item.key, $event.target.value)">
          </td>
          <td><i class="fa fa-times text-danger ml-2"
                 title="Delete"
                 @click="deleteKey(item.key, item.group)"></i></td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="not-found" v-else>No results</div>
    <div class="qty" v-if=" translateList.data && translateList.data.length">
      <label>Quantity items</label>
      <select class="form-control qty mr-2"
              v-model="translateFilter.per_page"
              @change="changeTranslateFilter('per_page', $event.target.value)">
        <option value="15">15</option>
        <option value="30">30</option>
        <option value="50">50</option>
      </select>
    </div>
    <!--- PAGINATION -->
    <div class="text-center">
      <NavigationArrowsCircle v-if="translateList.data && translateList.data.length"
                              :current="translateList.current_page"
                              :from="translateList.current_page * translateList.per_page - translateList.per_page+1"
                              :perPage="translateList.per_page"
                              :total="translateList.total"
                              :key="translateList.current_page"
                              @goToPage="changeTranslateFilter('page', $event)"
      />
    </div>
    <!--    MODAL-->

    <!--Modal to adding group-->
    <Modal title="Add group"
           @closeModal="addGroupModal = $event"
           v-if="addGroupModal">
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <label for="new_group">Group</label>
            <input type="text" id="new_group" class="form-control mr-2" placeholder="new Group Name " v-model="newGroup.name">
          </div>
        </div>
      </div>
      <template v-slot:footer>
        <button type="button" class="btn btn-secondary" :disabled="!newGroup.name" @click="addGroup">Add Group
        </button>
        <button type="button" class="btn btn-danger" @click="addGroupModal = false">Cancel</button>
      </template>
    </Modal>

    <Modal title="Add key"
           @closeModal="addKeyModal = $event"
           v-if="addKeyModal">
      <div class="modal-body">
        <div class="row">
          <div class="col-5">
            <label>Group</label>
            <select class="form-control" v-model="newKey.group">
              <option value="all" disabled>Select group</option>
              <option :value="lang"
                      v-for="(lang, idx) in $store.state.lang.translateGroups"
                      :key="idx">{{lang}}
              </option>
            </select>
          </div>
          <div class="col-7">
            <label>Key</label>
            <input type="text"
                   class="form-control"
                   placeholder="Key name"
                   :disabled="!newKey.group"
                   v-model="newKey.key">
          </div>
          <div class="col-12">
            <label>Est translate</label>
            <input type="text"
                   class="form-control"
                   placeholder="Estonian"
                   :disabled="!newKey.key"
                   v-model="newKey.values.ee">
          </div>
          <div class="col-12">
            <label>Eng translate</label>
            <input type="text"
                   class="form-control"
                   placeholder="English"
                   :disabled="!newKey.key"
                   v-model="newKey.values.en">
          </div>
          <div class="col-12">
            <label>Rus translate</label>
            <input type="text"
                   class="form-control"
                   placeholder="Russian"
                   :disabled="!newKey.key"
                   v-model="newKey.values.ru">
          </div>
        </div>
      </div>

      <template v-slot:footer>
        <button type="button"
                class="btn btn-secondary"
                :disabled="!newKey.key"
                @click="addTranslateKey">Add key
        </button>
        <button type="button" class="btn btn-danger"
                @click="addKeyModal = false">Cancel
        </button>
      </template>
    </Modal>
  </div>
</template>

<script>
  import Modal from "../../../components/common/Modal";
  import NavigationArrowsCircle from "../../../components/common/NavigationArrowsCircle";

  export default {
    name: "trans",
    layout: "admin",
    middleware: ['auth'],
    components: {NavigationArrowsCircle, Modal},
    data() {
      return {
        addKeyModal: false,
        addGroupModal: false,
        groupForNewKey: null,
        newGroup:{
            name: "",
        },
        newKey: {
          group: null,
          key: '',
          values: {
            ee: '',
            en: '',
            ru: ''
          }
        }
      }
    },
    async asyncData({store, route}) {
      await store.dispatch('lang/fetchTranslateList')
      await store.dispatch('lang/fetchTranslateGroups')
    },
    created() {
      this.$store.commit('SET_OBJ',
        {
          name: 'breadcrumbs',
          value: [
            {name: 'Translations', url: ''},
          ]
        })
    },
    destroyed() {
      this.$store.commit('SET_OBJ', {name: 'breadcrumbs', value: []})
    },
    watch: {
      async translateFilter() {
        await this.$store.dispatch('lang/fetchTranslateList')
        scroll(0, 0)
      }
    },
    computed: {
      translateList() {
        return this.$store.state.lang.translateList
      },
      translateFilter() {
        return JSON.parse(JSON.stringify(this.$store.state.lang.translateFilter))
      }
    },
    methods: {
      deleteKey(key, group) {
        const obj = {key, group}
        this.$store.dispatch('lang/deleteTranslateKey', obj)
      },
      changeValue(lang, group, key, value) {
        const obj = {lang, group, key, value}
        this.$store.dispatch('lang/updateTranslateValue', obj)
      },
      async changeTranslateFilter(key, value) {
        if (key === 'group') {
          this.$store.commit('lang/SET_OBJ', {name: 'translateFilter', key: 'page', value: 1})
        }
        this.$store.commit('lang/SET_OBJ', {name: 'translateFilter', key, value})
      },

      addGroup(){
        this.addGroupModal = false;
        this.$store.dispatch('lang/createGroup', this.newGroup).then(
          resp => {
            this.newGroup = {
              name: ''
            }
            this.$store.commit("lang/SET_TRANSLATE_FILTER_GROUP" , resp.name);
          },
          error => {console.log(error)}
        )
      },
      searchTrans(){
        this.search.page = 1;
        this.$store.dispatch('lang/fetchTranslateList')
      },
      exportAll(){
        this.$store.dispatch('lang/exportTrans' ).then(res => {
          this.$toast.success("Started").goAway(3500)
        },
        error => {
          this.$toast.error("Oops.." + error.toString()).goAway(3500)
        } )
      },

      exportBackend(){
        this.$store.dispatch('lang/exportTransBackend' ).then(res => {
            this.$toast.success("Started").goAway(3500)
          },
          error => {
            this.$toast.error("Oops.." + error.toString()).goAway(3500)
          } )
      },

      addTranslateKey() {
        this.$store.dispatch('lang/createTranslateKey', this.newKey).then(res => {
          this.addKeyModal = false;
          this.search.group = this.newKey.group;
          this.$store.dispatch('lang/fetchTranslateList' , this.search);
          this.newKey = {
            group: null,
            key: '',
            values: {
              "en" : "",
              "ru" : "",
              "ee" : ""
            }
          };
        },error => {console.log(error);} )
      },
    }
  }
</script>

<style scoped>
  .search {
    display: flex;
  }

  .input-group > .form-control {
    /*flex: 1 1 auto;*/
  }

  .select-trans {
    width: 300px !important;
  }

  .qty {
    width: 100px;
  }

  .fa-times {
    cursor: pointer;
  }

  .trans-header, .trans-header__action {
    display: flex;
    justify-content: space-between;
  }


</style>
